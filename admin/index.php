<?php
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';

function e($value)
{
	return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function tableHasColumn(mysqli $conn, $table, $column)
{
	$table = $conn->real_escape_string($table);
	$column = $conn->real_escape_string($column);
	$sql = "SHOW COLUMNS FROM `$table` LIKE '$column'";
	$result = $conn->query($sql);
	return $result && $result->num_rows > 0;
}

function ensureDir($path)
{
	if (!is_dir($path)) {
		mkdir($path, 0755, true);
	}
}

function saveUploadedFiles($field, $targetDir, $allowedExtensions, $maxBytes, &$errors)
{
	$storedPaths = [];

	if (!isset($_FILES[$field]) || empty($_FILES[$field]['name'][0])) {
		return $storedPaths;
	}

	ensureDir($targetDir);

	foreach ($_FILES[$field]['tmp_name'] as $i => $tmpPath) {
		if (!is_uploaded_file($tmpPath)) {
			continue;
		}

		$original = $_FILES[$field]['name'][$i] ?? '';
		$size = (int) ($_FILES[$field]['size'][$i] ?? 0);
		$err = (int) ($_FILES[$field]['error'][$i] ?? UPLOAD_ERR_NO_FILE);
		$ext = strtolower((string) pathinfo($original, PATHINFO_EXTENSION));

		if ($err !== UPLOAD_ERR_OK) {
			$errors[] = "Upload failed for " . e($original) . ".";
			continue;
		}

		if (!in_array($ext, $allowedExtensions, true)) {
			$errors[] = "Invalid file type for " . e($original) . ".";
			continue;
		}

		if ($size <= 0 || $size > $maxBytes) {
			$errors[] = "File too large for " . e($original) . ".";
			continue;
		}

		$safeBase = preg_replace('/[^a-zA-Z0-9_-]/', '_', (string) pathinfo($original, PATHINFO_FILENAME));
		$safeBase = $safeBase === '' ? 'file' : $safeBase;
		$filename = uniqid($safeBase . '_', true) . '.' . $ext;
		$targetPath = rtrim($targetDir, '/\\') . DIRECTORY_SEPARATOR . $filename;

		if (!move_uploaded_file($tmpPath, $targetPath)) {
			$errors[] = "Could not save " . e($original) . ".";
			continue;
		}

		$relative = str_replace('\\', '/', str_replace(realpath(__DIR__ . '/..') . DIRECTORY_SEPARATOR, '', realpath($targetPath)));
		if ($relative === '' || $relative === false) {
			$relative = 'uploads/' . $filename;
		}
		$storedPaths[] = '../' . ltrim($relative, '/');
	}

	return $storedPaths;
}

function splitStoredMedia($csv)
{
	$items = array_map('trim', explode(',', (string) $csv));
	$items = array_values(array_filter($items, function ($item) {
		return $item !== '';
	}));
	return $items;
}

function isImageMedia($path)
{
	$ext = strtolower((string) pathinfo((string) $path, PATHINFO_EXTENSION));
	return in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true);
}

function removeStoredUploads($paths, $allowedFolder)
{
	$baseRoot = realpath(__DIR__ . '/..');
	$allowedRoot = realpath(__DIR__ . '/../' . trim($allowedFolder, '/'));
	if (!$baseRoot || !$allowedRoot) {
		return;
	}

	foreach ((array) $paths as $path) {
		$trimmed = ltrim(str_replace('\\', '/', (string) $path), '/');
		$trimmed = preg_replace('#^\.\./#', '', $trimmed);
		if ($trimmed === '') {
			continue;
		}

		$absolute = realpath($baseRoot . '/' . $trimmed);
		if (!$absolute || !is_file($absolute)) {
			continue;
		}

		if (strpos($absolute, $allowedRoot) === 0) {
			unlink($absolute);
		}
	}
}

function setFlash($type, $message)
{
	$_SESSION['admin_flash'][] = ['type' => $type, 'message' => $message];
}

function hasPermission($permissions, $key)
{
	return !empty($permissions[$key]);
}

function buildPostRedirectUrl()
{
	$section = preg_replace('/[^a-z0-9_-]/i', '', (string) ($_POST['return_section'] ?? ''));
	$scroll = (int) ($_POST['return_scroll'] ?? 0);
	$parts = [];

	if ($section !== '') {
		$parts[] = 'section=' . rawurlencode($section);
	}
	if ($scroll > 0) {
		$parts[] = 'scroll=' . $scroll;
	}

	return 'index.php' . (!empty($parts) ? ('?' . implode('&', $parts)) : '');
}

$schema = [
	'events_hi' => tableHasColumn($conn, 'events', 'event_name_hi') && tableHasColumn($conn, 'events', 'event_location_hi') && tableHasColumn($conn, 'events', 'event_description_hi'),
	'announcements_hi' => tableHasColumn($conn, 'announcements', 'title_hi') && tableHasColumn($conn, 'announcements', 'content_hi'),
	'moderators_can_homepage' => tableHasColumn($conn, 'moderators', 'can_homepage'),
	'moderators_can_careers' => tableHasColumn($conn, 'moderators', 'can_careers'),
	'moderators_can_officers' => tableHasColumn($conn, 'moderators', 'can_officers'),
	'moderators_can_in_the_news' => tableHasColumn($conn, 'moderators', 'can_in_the_news'),
	'moderators_can_collaborators_sponsors' => tableHasColumn($conn, 'moderators', 'can_collaborators_sponsors'),
	'moderators_can_impact' => tableHasColumn($conn, 'moderators', 'can_impact'),
	'moderators_can_affiliates' => tableHasColumn($conn, 'moderators', 'can_affiliates'),
	'moderators_can_reach' => tableHasColumn($conn, 'moderators', 'can_reach'),
	'admins_privileges' => tableHasColumn($conn, 'moderators', 'is_super_admin')
		&& tableHasColumn($conn, 'moderators', 'can_manage_admins')
		&& tableHasColumn($conn, 'moderators', 'can_user_approval')
		&& tableHasColumn($conn, 'moderators', 'can_announcements')
		&& tableHasColumn($conn, 'moderators', 'can_articles')
		&& tableHasColumn($conn, 'moderators', 'can_events')
		&& tableHasColumn($conn, 'moderators', 'can_gallery'),
	'moderators_can_certificates' => tableHasColumn($conn, 'moderators', 'can_certificates'),
	'moderators_display_name' => tableHasColumn($conn, 'moderators', 'display_name'),
];

$schema['moderators_page_privileges'] = $schema['moderators_can_homepage']
	&& $schema['moderators_can_careers']
	&& $schema['moderators_can_officers']
	&& $schema['moderators_can_in_the_news']
	&& $schema['moderators_can_collaborators_sponsors']
	&& $schema['moderators_can_impact']
	&& $schema['moderators_can_affiliates']
	&& $schema['moderators_can_reach'];

if (empty($_SESSION['admin_csrf'])) {
	$_SESSION['admin_csrf'] = bin2hex(random_bytes(32));
}

if (isset($_GET['logout']) && $_GET['logout'] === '1') {
	unset($_SESSION['admin_logged_in'], $_SESSION['admin_id']);
	setFlash('success', 'Logged out successfully.');
	header('Location: index.php');
	exit();
}

$currentAdmin = null;
$isLoggedIn = !empty($_SESSION['admin_logged_in']) && !empty($_SESSION['admin_id']);

if ($isLoggedIn) {
	$stmt = $conn->prepare('SELECT * FROM moderators WHERE id = ? LIMIT 1');
	$stmt->bind_param('i', $_SESSION['admin_id']);
	$stmt->execute();
	$result = $stmt->get_result();
	$currentAdmin = $result ? $result->fetch_assoc() : null;
	$stmt->close();

	if (!$currentAdmin) {
		unset($_SESSION['admin_logged_in'], $_SESSION['admin_id']);
		$isLoggedIn = false;
		setFlash('error', 'Your admin session is no longer valid. Please login again.');
	}
}

$isSuperAdmin = false;
$permissions = [
	'manage_admins' => false,
	'user_approval' => false,
	'announcements' => false,
	'articles' => false,
	'events' => false,
	'gallery' => false,
	'certificates' => false,
	'homepage' => false,
	'careers' => false,
	'officers' => false,
	'in_the_news' => false,
	'collaborators_sponsors' => false,
	'impact' => false,
	'affiliates' => false,
	'reach' => false,
];

if ($currentAdmin) {
	if ($schema['admins_privileges']) {
		$isSuperAdmin = (int) ($currentAdmin['is_super_admin'] ?? 0) === 1;
		$permissions = [
			'manage_admins' => (int) ($currentAdmin['can_manage_admins'] ?? 0) === 1,
			'user_approval' => (int) ($currentAdmin['can_user_approval'] ?? 0) === 1,
			'announcements' => (int) ($currentAdmin['can_announcements'] ?? 0) === 1,
			'articles' => (int) ($currentAdmin['can_articles'] ?? 0) === 1,
			'events' => (int) ($currentAdmin['can_events'] ?? 0) === 1,
			'gallery' => (int) ($currentAdmin['can_gallery'] ?? 0) === 1,
			'certificates' => $schema['moderators_can_certificates']
				? ((int) ($currentAdmin['can_certificates'] ?? 0) === 1)
				: ((int) ($currentAdmin['can_events'] ?? 0) === 1),
			'homepage' => $schema['moderators_can_homepage'] ? ((int) ($currentAdmin['can_homepage'] ?? 0) === 1) : false,
			'careers' => $schema['moderators_can_careers'] ? ((int) ($currentAdmin['can_careers'] ?? 0) === 1) : false,
			'officers' => $schema['moderators_can_officers'] ? ((int) ($currentAdmin['can_officers'] ?? 0) === 1) : false,
			'in_the_news' => $schema['moderators_can_in_the_news'] ? ((int) ($currentAdmin['can_in_the_news'] ?? 0) === 1) : false,
			'collaborators_sponsors' => $schema['moderators_can_collaborators_sponsors'] ? ((int) ($currentAdmin['can_collaborators_sponsors'] ?? 0) === 1) : false,
			'impact' => $schema['moderators_can_impact'] ? ((int) ($currentAdmin['can_impact'] ?? 0) === 1) : false,
			'affiliates' => $schema['moderators_can_affiliates'] ? ((int) ($currentAdmin['can_affiliates'] ?? 0) === 1) : false,
			'reach' => $schema['moderators_can_reach'] ? ((int) ($currentAdmin['can_reach'] ?? 0) === 1) : false,
		];
	} else {
		$isSuperAdmin = ((int) ($currentAdmin['id'] ?? 0) === 1) || (($currentAdmin['role'] ?? '') === 'super_admin');
		$permissions = [
			'manage_admins' => $isSuperAdmin,
			'user_approval' => true,
			'announcements' => true,
			'articles' => true,
			'events' => true,
			'gallery' => true,
			'certificates' => true,
			'homepage' => false,
			'careers' => false,
			'officers' => false,
			'in_the_news' => false,
			'collaborators_sponsors' => false,
			'impact' => false,
			'affiliates' => false,
			'reach' => false,
		];
	}

	if ($isSuperAdmin) {
		foreach ($permissions as $k => $v) {
			$permissions[$k] = true;
		}
	}
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$action = $_POST['action'] ?? '';
	$postRedirectUrl = buildPostRedirectUrl();

	if ($action === 'admin_login') {
		$username = trim((string) ($_POST['username'] ?? ''));
		$password = (string) ($_POST['password'] ?? '');

		if ($username === '' || $password === '') {
			setFlash('error', 'Username and password are required.');
		} else {
			$stmt = $conn->prepare('SELECT * FROM moderators WHERE username = ? LIMIT 1');
			$stmt->bind_param('s', $username);
			$stmt->execute();
			$result = $stmt->get_result();
			$admin = $result ? $result->fetch_assoc() : null;
			$stmt->close();

			if ($admin && password_verify($password, (string) $admin['password'])) {
				$_SESSION['admin_logged_in'] = true;
				$_SESSION['admin_id'] = (int) $admin['id'];
				setFlash('success', 'Welcome back, ' . e($admin['username']) . '.');
			} else {
				setFlash('error', 'Invalid username or password.');
			}
		}

		header('Location: index.php');
		exit();
	}

	if (!$isLoggedIn || !$currentAdmin) {
		setFlash('error', 'Please login first.');
		header('Location: ' . $postRedirectUrl);
		exit();
	}

	if (!hash_equals($_SESSION['admin_csrf'], (string) ($_POST['csrf'] ?? ''))) {
		setFlash('error', 'Invalid request token. Please retry.');
		header('Location: ' . $postRedirectUrl);
		exit();
	}

	if ($action === 'create_admin') {
		if (!hasPermission($permissions, 'manage_admins')) {
			setFlash('error', 'You do not have permission to create admins.');
		} else {
			$username = trim((string) ($_POST['new_username'] ?? ''));
			$password = (string) ($_POST['new_password'] ?? '');
			$displayName = trim((string) ($_POST['display_name'] ?? ''));
			$role = $isSuperAdmin && !empty($_POST['is_super_admin']) ? 'super_admin' : 'moderator';
			$isSuper = ($isSuperAdmin && !empty($_POST['is_super_admin'])) ? 1 : 0;

			if ($username === '' || $password === '') {
				setFlash('error', 'New admin username and password are required.');
			} elseif (strlen($username) < 4 || strlen($username) > 50) {
				setFlash('error', 'Username must be between 4 and 50 characters.');
			} elseif (strlen($password) < 8) {
				setFlash('error', 'Password must be at least 8 characters.');
			} else {
				$hash = password_hash($password, PASSWORD_DEFAULT);

				$columns = ['username', 'password', 'role'];
				$values = [$username, $hash, $role];
				$types = 'sss';

				if ($schema['moderators_display_name']) {
					$columns[] = 'display_name';
					$values[] = $displayName;
					$types .= 's';
				}

				if ($schema['admins_privileges']) {
					$columns[] = 'is_super_admin';
					$values[] = $isSuper;
					$types .= 'i';

					$columns[] = 'can_manage_admins';
					$values[] = 0;
					$types .= 'i';

					$columns[] = 'can_user_approval';
					$values[] = 0;
					$types .= 'i';

					$columns[] = 'can_announcements';
					$values[] = 0;
					$types .= 'i';

					$columns[] = 'can_articles';
					$values[] = 0;
					$types .= 'i';

					$columns[] = 'can_events';
					$values[] = 0;
					$types .= 'i';

					$columns[] = 'can_gallery';
					$values[] = 0;
					$types .= 'i';

					if ($schema['moderators_can_certificates']) {
						$columns[] = 'can_certificates';
						$values[] = 0;
						$types .= 'i';
					}

					if ($schema['moderators_can_homepage']) {
						$columns[] = 'can_homepage';
						$values[] = 0;
						$types .= 'i';
					}
					if ($schema['moderators_can_careers']) {
						$columns[] = 'can_careers';
						$values[] = 0;
						$types .= 'i';
					}
					if ($schema['moderators_can_officers']) {
						$columns[] = 'can_officers';
						$values[] = 0;
						$types .= 'i';
					}
					if ($schema['moderators_can_in_the_news']) {
						$columns[] = 'can_in_the_news';
						$values[] = 0;
						$types .= 'i';
					}
					if ($schema['moderators_can_collaborators_sponsors']) {
						$columns[] = 'can_collaborators_sponsors';
						$values[] = 0;
						$types .= 'i';
					}
					if ($schema['moderators_can_impact']) {
						$columns[] = 'can_impact';
						$values[] = 0;
						$types .= 'i';
					}
					if ($schema['moderators_can_affiliates']) {
						$columns[] = 'can_affiliates';
						$values[] = 0;
						$types .= 'i';
					}
					if ($schema['moderators_can_reach']) {
						$columns[] = 'can_reach';
						$values[] = 0;
						$types .= 'i';
					}
				}

				$placeholders = implode(', ', array_fill(0, count($columns), '?'));
				$sql = 'INSERT INTO moderators (' . implode(', ', $columns) . ') VALUES (' . $placeholders . ')';
				$stmt = $conn->prepare($sql);
				$stmt->bind_param($types, ...$values);

				if ($stmt->execute()) {
					setFlash('success', 'New admin account created.');
				} else {
					setFlash('error', 'Could not create admin account. Username may already exist.');
				}
				$stmt->close();
			}
		}
	}

	if ($action === 'update_privileges') {
		if (!hasPermission($permissions, 'manage_admins')) {
			setFlash('error', 'You do not have permission to manage privileges.');
		} elseif (!$schema['admins_privileges']) {
			setFlash('error', 'Privilege columns are missing. Run the SQL migration first.');
		} else {
			$targetId = (int) ($_POST['target_admin_id'] ?? 0);
			if ($targetId <= 0) {
				setFlash('error', 'Please select a valid admin.');
			} else {
				$canManageAdmins = !empty($_POST['can_manage_admins']) ? 1 : 0;
				$canUserApproval = !empty($_POST['can_user_approval']) ? 1 : 0;
				$canAnnouncements = !empty($_POST['can_announcements']) ? 1 : 0;
				$canArticles = !empty($_POST['can_articles']) ? 1 : 0;
				$canEvents = !empty($_POST['can_events']) ? 1 : 0;
				$canGallery = !empty($_POST['can_gallery']) ? 1 : 0;
				$canCertificates = !empty($_POST['can_certificates']) ? 1 : 0;
				$canHomepage = !empty($_POST['can_homepage']) ? 1 : 0;
				$canCareers = !empty($_POST['can_careers']) ? 1 : 0;
				$canOfficers = !empty($_POST['can_officers']) ? 1 : 0;
				$canInTheNews = !empty($_POST['can_in_the_news']) ? 1 : 0;
				$canCollaboratorsSponsors = !empty($_POST['can_collaborators_sponsors']) ? 1 : 0;
				$canImpact = !empty($_POST['can_impact']) ? 1 : 0;
				$canAffiliates = !empty($_POST['can_affiliates']) ? 1 : 0;
				$canReach = !empty($_POST['can_reach']) ? 1 : 0;

				$setFields = [
					'can_manage_admins = ?',
					'can_user_approval = ?',
					'can_announcements = ?',
					'can_articles = ?',
					'can_events = ?',
					'can_gallery = ?',
				];
				$types = 'iiiiii';
				$params = [$canManageAdmins, $canUserApproval, $canAnnouncements, $canArticles, $canEvents, $canGallery];

				if ($schema['moderators_can_certificates']) {
					$setFields[] = 'can_certificates = ?';
					$types .= 'i';
					$params[] = $canCertificates;
				}

				if ($schema['moderators_can_homepage']) {
					$setFields[] = 'can_homepage = ?';
					$types .= 'i';
					$params[] = $canHomepage;
				}
				if ($schema['moderators_can_careers']) {
					$setFields[] = 'can_careers = ?';
					$types .= 'i';
					$params[] = $canCareers;
				}
				if ($schema['moderators_can_officers']) {
					$setFields[] = 'can_officers = ?';
					$types .= 'i';
					$params[] = $canOfficers;
				}
				if ($schema['moderators_can_in_the_news']) {
					$setFields[] = 'can_in_the_news = ?';
					$types .= 'i';
					$params[] = $canInTheNews;
				}
				if ($schema['moderators_can_collaborators_sponsors']) {
					$setFields[] = 'can_collaborators_sponsors = ?';
					$types .= 'i';
					$params[] = $canCollaboratorsSponsors;
				}
				if ($schema['moderators_can_impact']) {
					$setFields[] = 'can_impact = ?';
					$types .= 'i';
					$params[] = $canImpact;
				}
				if ($schema['moderators_can_affiliates']) {
					$setFields[] = 'can_affiliates = ?';
					$types .= 'i';
					$params[] = $canAffiliates;
				}
				if ($schema['moderators_can_reach']) {
					$setFields[] = 'can_reach = ?';
					$types .= 'i';
					$params[] = $canReach;
				}

				$types .= 'i';
				$params[] = $targetId;

				$sql = 'UPDATE moderators SET ' . implode(', ', $setFields) . ' WHERE id = ? AND is_super_admin = 0';
				$stmt = $conn->prepare($sql);
				$stmt->bind_param($types, ...$params);
				$stmt->execute();
				if ($stmt->affected_rows >= 0) {
					setFlash('success', 'Privileges updated successfully.');
				} else {
					setFlash('error', 'Could not update privileges.');
				}
				$stmt->close();
			}
		}
	}

	if ($action === 'approve_submission') {
		if (!hasPermission($permissions, 'user_approval')) {
			setFlash('error', 'You do not have permission for user approvals.');
		} else {
			$submissionId = (int) ($_POST['submission_id'] ?? 0);
			$name = trim((string) ($_POST['name'] ?? ''));
			$preferredName = trim((string) ($_POST['preferred_name'] ?? ''));
			$pronouns = trim((string) ($_POST['pronouns'] ?? ''));
			$fatherName = trim((string) ($_POST['father_name'] ?? ''));
			$post = trim((string) ($_POST['post'] ?? ''));
			$reference = trim((string) ($_POST['reference'] ?? ''));
			$address = trim((string) ($_POST['address'] ?? ''));
			$occupation = trim((string) ($_POST['occupation'] ?? ''));
			$mobileNo = trim((string) ($_POST['mobile_no'] ?? ''));
			$email = trim((string) ($_POST['email'] ?? ''));
			$city = trim((string) ($_POST['city'] ?? ''));

			if ($submissionId <= 0 || $name === '' || $email === '' || $post === '') {
				setFlash('error', 'Submission ID, name, post, and email are required.');
			} else {
				$conn->begin_transaction();
				try {
					$update = $conn->prepare('UPDATE submissions SET name = ?, preferred_name = ?, pronouns = ?, father_name = ?, post = ?, reference = ?, address = ?, occupation = ?, mobile_no = ?, email = ?, city = ? WHERE id = ?');
					$update->bind_param('sssssssssssi', $name, $preferredName, $pronouns, $fatherName, $post, $reference, $address, $occupation, $mobileNo, $email, $city, $submissionId);
					$update->execute();
					$update->close();

					$check = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
					$check->bind_param('s', $email);
					$check->execute();
					$existing = $check->get_result();
					$hasUser = $existing && $existing->num_rows > 0;
					$check->close();

					if ($hasUser) {
						throw new RuntimeException('A user with this email is already approved.');
					}

					$insert = $conn->prepare('INSERT INTO users (name, preferred_name, pronouns, father_name, post, reference, address, occupation, mobile_no, email, photo, city, password) SELECT name, preferred_name, pronouns, father_name, post, reference, address, occupation, mobile_no, email, photo, city, password FROM submissions WHERE id = ? LIMIT 1');
					$insert->bind_param('i', $submissionId);
					$insert->execute();
					$insert->close();

					$status = $conn->prepare("UPDATE submissions SET status = 'approved' WHERE id = ?");
					$status->bind_param('i', $submissionId);
					$status->execute();
					$status->close();

					$conn->commit();
					setFlash('success', 'Submission approved and user added.');
				} catch (Throwable $th) {
					$conn->rollback();
					setFlash('error', 'Approval failed: ' . $th->getMessage());
				}
			}
		}
	}

	if ($action === 'dismiss_submission') {
		if (!hasPermission($permissions, 'user_approval')) {
			setFlash('error', 'You do not have permission for user approvals.');
		} else {
			$submissionId = (int) ($_POST['submission_id'] ?? 0);
			if ($submissionId > 0) {
				$stmt = $conn->prepare("UPDATE submissions SET status = 'dismissed' WHERE id = ?");
				$stmt->bind_param('i', $submissionId);
				$stmt->execute();
				$stmt->close();
				setFlash('success', 'Submission dismissed.');
			} else {
				setFlash('error', 'Invalid submission selected.');
			}
		}
	}

	if ($action === 'move_user_exmember') {
		if (!hasPermission($permissions, 'user_approval')) {
			setFlash('error', 'You do not have permission for this action.');
		} else {
			$userId = (int) ($_POST['user_id'] ?? 0);
			$newPost = trim((string) ($_POST['new_post'] ?? ''));
			$validPosts = ['resigned', 'removed', 'promoted', 'demoted'];

			if ($userId <= 0 || !in_array($newPost, $validPosts, true)) {
				setFlash('error', 'Invalid user/post selection.');
			} else {
				$conn->begin_transaction();
				try {
					$insert = $conn->prepare('INSERT INTO exmembers (name, email, post) SELECT name, email, ? FROM users WHERE id = ? LIMIT 1');
					$insert->bind_param('si', $newPost, $userId);
					$insert->execute();
					$insert->close();

					$delete = $conn->prepare('DELETE FROM users WHERE id = ? LIMIT 1');
					$delete->bind_param('i', $userId);
					$delete->execute();
					$delete->close();

					$conn->commit();
					setFlash('success', 'User moved to ex-members list.');
				} catch (Throwable $th) {
					$conn->rollback();
					setFlash('error', 'Could not move user: ' . $th->getMessage());
				}
			}
		}
	}

	if ($action === 'approve_article') {
		if (!hasPermission($permissions, 'articles')) {
			setFlash('error', 'You do not have article approval access.');
		} else {
			$articleId = (int) ($_POST['article_id'] ?? 0);
			if ($articleId > 0) {
				$stmt = $conn->prepare("UPDATE articles SET status = 'approved' WHERE id = ?");
				$stmt->bind_param('i', $articleId);
				$stmt->execute();
				$stmt->close();
				setFlash('success', 'Article approved.');
			}
		}
	}

	if ($action === 'dismiss_article') {
		if (!hasPermission($permissions, 'articles')) {
			setFlash('error', 'You do not have article dismissal access.');
		} else {
			$articleId = (int) ($_POST['article_id'] ?? 0);
			if ($articleId > 0) {
				$stmt = $conn->prepare("UPDATE articles SET status = 'dismissed' WHERE id = ?");
				$stmt->bind_param('i', $articleId);
				$stmt->execute();
				$stmt->close();
				setFlash('success', 'Article dismissed.');
			}
		}
	}

	if ($action === 'add_event') {
		if (!hasPermission($permissions, 'events')) {
			setFlash('error', 'You do not have event management access.');
		} else {
			$eventName = trim((string) ($_POST['event_name'] ?? ''));
			$eventNameHi = trim((string) ($_POST['event_name_hi'] ?? ''));
			$eventDate = trim((string) ($_POST['event_date'] ?? ''));
			$eventLocation = trim((string) ($_POST['event_location'] ?? ''));
			$eventLocationHi = trim((string) ($_POST['event_location_hi'] ?? ''));
			$eventDescription = trim((string) ($_POST['event_description'] ?? ''));
			$eventDescriptionHi = trim((string) ($_POST['event_description_hi'] ?? ''));

			$errors = [];
			if ($eventName === '' || $eventDate === '' || $eventLocation === '' || $eventDescription === '') {
				$errors[] = 'All English event fields are required.';
			}
			if ($schema['events_hi'] && ($eventNameHi === '' || $eventLocationHi === '' || $eventDescriptionHi === '')) {
				$errors[] = 'All Hindi event fields are required.';
			}

			$uploaded = saveUploadedFiles('event_images', __DIR__ . '/../uploads/events', ['jpg', 'jpeg', 'png', 'gif', 'webp'], 5 * 1024 * 1024, $errors);
			if (!empty($errors)) {
				setFlash('error', implode(' ', $errors));
			} else {
				$images = implode(',', $uploaded);
				if ($schema['events_hi']) {
					$stmt = $conn->prepare('INSERT INTO events (event_name, event_name_hi, event_date, event_location, event_location_hi, event_description, event_description_hi, event_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
					$stmt->bind_param('ssssssss', $eventName, $eventNameHi, $eventDate, $eventLocation, $eventLocationHi, $eventDescription, $eventDescriptionHi, $images);
				} else {
					$stmt = $conn->prepare('INSERT INTO events (event_name, event_date, event_location, event_description, event_image) VALUES (?, ?, ?, ?, ?)');
					$stmt->bind_param('sssss', $eventName, $eventDate, $eventLocation, $eventDescription, $images);
				}

				if ($stmt->execute()) {
					setFlash('success', 'Event published successfully.');
				} else {
					setFlash('error', 'Could not publish event.');
				}
				$stmt->close();
			}
		}
	}

	if ($action === 'update_event') {
		if (!hasPermission($permissions, 'events')) {
			setFlash('error', 'You do not have event management access.');
		} else {
			$eventId = (int) ($_POST['event_id'] ?? 0);
			$eventName = trim((string) ($_POST['event_name'] ?? ''));
			$eventNameHi = trim((string) ($_POST['event_name_hi'] ?? ''));
			$eventDate = trim((string) ($_POST['event_date'] ?? ''));
			$eventLocation = trim((string) ($_POST['event_location'] ?? ''));
			$eventLocationHi = trim((string) ($_POST['event_location_hi'] ?? ''));
			$eventDescription = trim((string) ($_POST['event_description'] ?? ''));
			$eventDescriptionHi = trim((string) ($_POST['event_description_hi'] ?? ''));

			$errors = [];
			if ($eventId <= 0 || $eventName === '' || $eventDate === '' || $eventLocation === '' || $eventDescription === '') {
				$errors[] = 'Event id and all English fields are required.';
			}
			if ($schema['events_hi'] && ($eventNameHi === '' || $eventLocationHi === '' || $eventDescriptionHi === '')) {
				$errors[] = 'All Hindi event fields are required.';
			}

			$stmt = $conn->prepare('SELECT event_image FROM events WHERE id = ? LIMIT 1');
			$stmt->bind_param('i', $eventId);
			$stmt->execute();
			$current = $stmt->get_result()->fetch_assoc();
			$stmt->close();

			if (!$current) {
				$errors[] = 'Event not found.';
			}

			$currentMedia = splitStoredMedia($current['event_image'] ?? '');
			$removeMedia = array_values(array_filter(array_map('trim', (array) ($_POST['remove_event_media'] ?? [])), function ($v) {
				return $v !== '';
			}));
			$removeMedia = array_values(array_intersect($currentMedia, $removeMedia));

			$newUploads = saveUploadedFiles('event_images_new', __DIR__ . '/../uploads/events', ['jpg', 'jpeg', 'png', 'gif', 'webp'], 5 * 1024 * 1024, $errors);
			if (!empty($errors)) {
				setFlash('error', implode(' ', $errors));
			} else {
				$keptMedia = array_values(array_diff($currentMedia, $removeMedia));
				$finalMedia = implode(',', array_merge($keptMedia, $newUploads));

				if ($schema['events_hi']) {
					$stmt = $conn->prepare('UPDATE events SET event_name = ?, event_name_hi = ?, event_date = ?, event_location = ?, event_location_hi = ?, event_description = ?, event_description_hi = ?, event_image = ? WHERE id = ?');
					$stmt->bind_param('ssssssssi', $eventName, $eventNameHi, $eventDate, $eventLocation, $eventLocationHi, $eventDescription, $eventDescriptionHi, $finalMedia, $eventId);
				} else {
					$stmt = $conn->prepare('UPDATE events SET event_name = ?, event_date = ?, event_location = ?, event_description = ?, event_image = ? WHERE id = ?');
					$stmt->bind_param('sssssi', $eventName, $eventDate, $eventLocation, $eventDescription, $finalMedia, $eventId);
				}

				if ($stmt->execute()) {
					removeStoredUploads($removeMedia, 'uploads/events');
					setFlash('success', 'Event updated successfully.');
				} else {
					setFlash('error', 'Could not update event.');
				}
				$stmt->close();
			}
		}
	}

	if ($action === 'add_announcement') {
		if (!hasPermission($permissions, 'announcements')) {
			setFlash('error', 'You do not have announcement access.');
		} else {
			$title = trim((string) ($_POST['title'] ?? ''));
			$titleHi = trim((string) ($_POST['title_hi'] ?? ''));
			$content = trim((string) ($_POST['content'] ?? ''));
			$contentHi = trim((string) ($_POST['content_hi'] ?? ''));

			$errors = [];
			if ($title === '' || $content === '') {
				$errors[] = 'English title and content are required.';
			}
			if ($schema['announcements_hi'] && ($titleHi === '' || $contentHi === '')) {
				$errors[] = 'Hindi title and content are required.';
			}

			$uploaded = saveUploadedFiles('announcement_media', __DIR__ . '/../uploads/announcements', ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf'], 10 * 1024 * 1024, $errors);
			if (!empty($errors)) {
				setFlash('error', implode(' ', $errors));
			} else {
				$media = implode(',', $uploaded);
				if ($schema['announcements_hi']) {
					$stmt = $conn->prepare('INSERT INTO announcements (title, title_hi, content, content_hi, images) VALUES (?, ?, ?, ?, ?)');
					$stmt->bind_param('sssss', $title, $titleHi, $content, $contentHi, $media);
				} else {
					$stmt = $conn->prepare('INSERT INTO announcements (title, content, images) VALUES (?, ?, ?)');
					$stmt->bind_param('sss', $title, $content, $media);
				}

				if ($stmt->execute()) {
					setFlash('success', 'Announcement saved. Attempting English email broadcast.');
					$stmt->close();

					$mailerPathBase = __DIR__ . '/../includes/PHPMailer/';
					$credsFile = __DIR__ . '/../../Safe/emailPasswordAnnouncement.php';
					if (file_exists($mailerPathBase . 'PHPMailer.php') && file_exists($mailerPathBase . 'SMTP.php') && file_exists($mailerPathBase . 'Exception.php') && file_exists($credsFile)) {
						require_once $mailerPathBase . 'PHPMailer.php';
						require_once $mailerPathBase . 'SMTP.php';
						require_once $mailerPathBase . 'Exception.php';

						$emailCredentials = include $credsFile;
						$emails = [];
						$usersResult = $conn->query('SELECT email FROM users WHERE email IS NOT NULL AND email <> ""');
						if ($usersResult) {
							while ($row = $usersResult->fetch_assoc()) {
								$emails[] = $row['email'];
							}
						}

						if (!empty($emails)) {
							$mail = new PHPMailer\PHPMailer\PHPMailer(true);
							try {
								$mail->isSMTP();
								$mail->Host = 'smtp.gmail.com';
								$mail->SMTPAuth = true;
								$mail->Username = $emailCredentials['email_username'] ?? '';
								$mail->Password = $emailCredentials['email_password'] ?? '';
								$mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
								$mail->Port = 587;
								$mail->setFrom('satrangisalamss@gmail.com', 'Satrangi Salaam Notifications');
								$mail->Subject = 'New Announcement: Read Now';
								$mail->isHTML(true);

								$cleanContent = nl2br(e($content));
								$mail->Body = '<div style="font-family:Arial,sans-serif;padding:16px"><h2 style="margin:0 0 8px">New Announcement</h2><h3 style="margin:0 0 8px">' . e($title) . '</h3><p>' . $cleanContent . '</p><p><a href="https://www.satrangisalaam.in/public/announcements">Read on website</a></p></div>';
								$mail->AltBody = "New Announcement\n\n" . $title . "\n\n" . $content . "\n\nRead on website: https://www.satrangisalaam.in/public/announcements";

								foreach ($uploaded as $file) {
									$real = realpath(__DIR__ . '/../' . ltrim(str_replace('../', '', $file), '/'));
									if ($real) {
										$mail->addAttachment($real);
									}
								}

								foreach ($emails as $email) {
									$mail->addBCC($email);
								}

								$mail->send();
								setFlash('success', 'Announcement email sent to registered users (English only).');
							} catch (Throwable $th) {
								setFlash('error', 'Announcement saved, but email failed: ' . $th->getMessage());
							}
						} else {
							setFlash('error', 'Announcement saved, but no user emails were found.');
						}
					} else {
						setFlash('error', 'Announcement saved, but email configuration was not found.');
					}
				} else {
					setFlash('error', 'Could not save announcement.');
					$stmt->close();
				}
			}
		}
	}

	if ($action === 'update_announcement') {
		if (!hasPermission($permissions, 'announcements')) {
			setFlash('error', 'You do not have announcement access.');
		} else {
			$announcementId = (int) ($_POST['announcement_id'] ?? 0);
			$title = trim((string) ($_POST['title'] ?? ''));
			$titleHi = trim((string) ($_POST['title_hi'] ?? ''));
			$content = trim((string) ($_POST['content'] ?? ''));
			$contentHi = trim((string) ($_POST['content_hi'] ?? ''));

			$errors = [];
			if ($announcementId <= 0 || $title === '' || $content === '') {
				$errors[] = 'Announcement id, English title and content are required.';
			}
			if ($schema['announcements_hi'] && ($titleHi === '' || $contentHi === '')) {
				$errors[] = 'Hindi title and content are required.';
			}

			$stmt = $conn->prepare('SELECT images FROM announcements WHERE id = ? LIMIT 1');
			$stmt->bind_param('i', $announcementId);
			$stmt->execute();
			$current = $stmt->get_result()->fetch_assoc();
			$stmt->close();

			if (!$current) {
				$errors[] = 'Announcement not found.';
			}

			$currentMedia = splitStoredMedia($current['images'] ?? '');
			$removeMedia = array_values(array_filter(array_map('trim', (array) ($_POST['remove_announcement_media'] ?? [])), function ($v) {
				return $v !== '';
			}));
			$removeMedia = array_values(array_intersect($currentMedia, $removeMedia));

			$newUploads = saveUploadedFiles('announcement_media_new', __DIR__ . '/../uploads/announcements', ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf'], 10 * 1024 * 1024, $errors);
			if (!empty($errors)) {
				setFlash('error', implode(' ', $errors));
			} else {
				$keptMedia = array_values(array_diff($currentMedia, $removeMedia));
				$finalMedia = implode(',', array_merge($keptMedia, $newUploads));

				if ($schema['announcements_hi']) {
					$stmt = $conn->prepare('UPDATE announcements SET title = ?, title_hi = ?, content = ?, content_hi = ?, images = ? WHERE id = ?');
					$stmt->bind_param('sssssi', $title, $titleHi, $content, $contentHi, $finalMedia, $announcementId);
				} else {
					$stmt = $conn->prepare('UPDATE announcements SET title = ?, content = ?, images = ? WHERE id = ?');
					$stmt->bind_param('sssi', $title, $content, $finalMedia, $announcementId);
				}

				if ($stmt->execute()) {
					removeStoredUploads($removeMedia, 'uploads/announcements');
					setFlash('success', 'Announcement updated successfully.');
				} else {
					setFlash('error', 'Could not update announcement.');
				}
				$stmt->close();
			}
		}
	}

	if ($action === 'upload_gallery') {
		if (!hasPermission($permissions, 'gallery')) {
			setFlash('error', 'You do not have gallery access.');
		} else {
			$errors = [];
			$uploaded = saveUploadedFiles('gallery_images', __DIR__ . '/../uploads/gallery', ['jpg', 'jpeg', 'png', 'gif', 'webp'], 5 * 1024 * 1024, $errors);

			if (!empty($errors)) {
				setFlash('error', implode(' ', $errors));
			} elseif (empty($uploaded)) {
				setFlash('error', 'Please choose at least one valid gallery image.');
			} else {
				$stmt = $conn->prepare('INSERT INTO gallery (image_path) VALUES (?)');
				foreach ($uploaded as $path) {
					$imageName = basename($path);
					$stmt->bind_param('s', $imageName);
					$stmt->execute();
				}
				$stmt->close();
				setFlash('success', 'Gallery images uploaded.');
			}
		}
	}

	if ($action === 'delete_gallery') {
		if (!hasPermission($permissions, 'gallery')) {
			setFlash('error', 'You do not have gallery access.');
		} else {
			$idsRaw = $_POST['gallery_ids'] ?? [];
			$ids = array_values(array_filter(array_map('intval', (array) $idsRaw), function ($v) {
				return $v > 0;
			}));

			if (empty($ids)) {
				setFlash('error', 'No gallery images selected.');
			} else {
				$idList = implode(',', $ids);
				$result = $conn->query("SELECT id, image_path FROM gallery WHERE id IN ($idList)");
				if ($result) {
					while ($row = $result->fetch_assoc()) {
						$file = realpath(__DIR__ . '/../uploads/gallery/' . $row['image_path']);
						if ($file && is_file($file)) {
							unlink($file);
						}
					}
				}
				$conn->query("DELETE FROM gallery WHERE id IN ($idList)");
				setFlash('success', 'Selected gallery images deleted.');
			}
		}
	}

	if ($action === 'approve_donation_certificate') {
		if (!hasPermission($permissions, 'certificates')) {
			setFlash('error', 'You do not have certificate access.');
		} else {
			$id = (int) ($_POST['donation_id'] ?? 0);
			$name = trim((string) ($_POST['name'] ?? ''));
			$amount = (int) ($_POST['amount'] ?? 0);
			$email = trim((string) ($_POST['email'] ?? ''));
			$whatsapp = trim((string) ($_POST['whatsapp'] ?? ''));

			$stmt = $conn->prepare('UPDATE donations SET name = ?, amount = ?, email = ?, whatsapp = ? WHERE id = ?');
			$stmt->bind_param('sissi', $name, $amount, $email, $whatsapp, $id);
			$stmt->execute();
			$stmt->close();

			header('Location: ../includes/generate_donation.php?donation_id=' . $id);
			exit();
		}
	}

	if ($action === 'reject_donation_certificate') {
		if (!hasPermission($permissions, 'certificates')) {
			setFlash('error', 'You do not have certificate access.');
		} else {
			$id = (int) ($_POST['donation_id'] ?? 0);
			$stmt = $conn->prepare('DELETE FROM donations WHERE id = ? LIMIT 1');
			$stmt->bind_param('i', $id);
			$stmt->execute();
			$stmt->close();
			setFlash('success', 'Donation request rejected.');
		}
	}

	if ($action === 'approve_experience_certificate') {
		if (!hasPermission($permissions, 'certificates')) {
			setFlash('error', 'You do not have certificate access.');
		} else {
			$id = (int) ($_POST['experience_id'] ?? 0);
			$name = trim((string) ($_POST['name'] ?? ''));
			$post = trim((string) ($_POST['post'] ?? ''));
			$period = trim((string) ($_POST['period'] ?? ''));
			$email = trim((string) ($_POST['email'] ?? ''));
			$whatsapp = trim((string) ($_POST['whatsapp'] ?? ''));

			$stmt = $conn->prepare('UPDATE experience SET name = ?, post = ?, period = ?, email = ?, whatsapp = ? WHERE id = ?');
			$stmt->bind_param('sssssi', $name, $post, $period, $email, $whatsapp, $id);
			$stmt->execute();
			$stmt->close();

			header('Location: ../includes/generate_experience.php?experience_id=' . $id);
			exit();
		}
	}

	if ($action === 'reject_experience_certificate') {
		if (!hasPermission($permissions, 'certificates')) {
			setFlash('error', 'You do not have certificate access.');
		} else {
			$id = (int) ($_POST['experience_id'] ?? 0);
			$stmt = $conn->prepare('DELETE FROM experience WHERE id = ? LIMIT 1');
			$stmt->bind_param('i', $id);
			$stmt->execute();
			$stmt->close();
			setFlash('success', 'Experience request rejected.');
		}
	}

	if ($action === 'approve_participation_certificate') {
		if (!hasPermission($permissions, 'certificates')) {
			setFlash('error', 'You do not have certificate access.');
		} else {
			$id = (int) ($_POST['participation_id'] ?? 0);
			$name = trim((string) ($_POST['name'] ?? ''));
			$eventName = trim((string) ($_POST['event_name'] ?? ''));
			$eventDate = trim((string) ($_POST['event_date'] ?? ''));
			$collaborators = trim((string) ($_POST['collaborators'] ?? ''));
			$email = trim((string) ($_POST['email'] ?? ''));
			$whatsapp = trim((string) ($_POST['whatsapp'] ?? ''));

			$stmt = $conn->prepare('UPDATE participation SET name = ?, event_name = ?, event_date = ?, collaborators = ?, email = ?, whatsapp = ? WHERE id = ?');
			$stmt->bind_param('ssssssi', $name, $eventName, $eventDate, $collaborators, $email, $whatsapp, $id);
			$stmt->execute();
			$stmt->close();

			header('Location: ../includes/generate_participation.php?participation_id=' . $id);
			exit();
		}
	}

	if ($action === 'reject_participation_certificate') {
		if (!hasPermission($permissions, 'certificates')) {
			setFlash('error', 'You do not have certificate access.');
		} else {
			$id = (int) ($_POST['participation_id'] ?? 0);
			$stmt = $conn->prepare('DELETE FROM participation WHERE id = ? LIMIT 1');
			$stmt->bind_param('i', $id);
			$stmt->execute();
			$stmt->close();
			setFlash('success', 'Participation request rejected.');
		}
	}

	if ($action === 'generate_collaboration_certificate') {
		if (!hasPermission($permissions, 'certificates')) {
			setFlash('error', 'You do not have certificate access.');
		} else {
			$name = trim((string) ($_POST['name'] ?? ''));
			$works = trim((string) ($_POST['works'] ?? ''));
			$res = $conn->query('SELECT IFNULL(MAX(certificate_no), 0) + 1 AS new_certificate_no FROM collaboration_certificates');
			$row = $res ? $res->fetch_assoc() : ['new_certificate_no' => 1];
			$certificateNo = (int) ($row['new_certificate_no'] ?? 1);

			$stmt = $conn->prepare('INSERT INTO collaboration_certificates (name, works, certificate_no) VALUES (?, ?, ?)');
			$stmt->bind_param('ssi', $name, $works, $certificateNo);
			$stmt->execute();
			$stmt->close();

			header('Location: ../includes/generate_collaboration_certificate.php?certificate_no=' . $certificateNo);
			exit();
		}
	}

	if ($action === 'generate_performance_certificate') {
		if (!hasPermission($permissions, 'certificates')) {
			setFlash('error', 'You do not have certificate access.');
		} else {
			$name = trim((string) ($_POST['name'] ?? ''));
			$works = trim((string) ($_POST['works'] ?? ''));
			$res = $conn->query('SELECT IFNULL(MAX(certificate_no), 0) + 1 AS new_certificate_no FROM performance');
			$row = $res ? $res->fetch_assoc() : ['new_certificate_no' => 1];
			$certificateNo = (int) ($row['new_certificate_no'] ?? 1);

			$stmt = $conn->prepare('INSERT INTO performance (name, works, certificate_no) VALUES (?, ?, ?)');
			$stmt->bind_param('ssi', $name, $works, $certificateNo);
			$stmt->execute();
			$stmt->close();

			header('Location: ../includes/generate_performance.php?certificate_no=' . $certificateNo);
			exit();
		}
	}

	header('Location: ' . $postRedirectUrl);
	exit();
}

$flashMessages = $_SESSION['admin_flash'] ?? [];
unset($_SESSION['admin_flash']);

$pendingSubmissions = [];
$dismissedSubmissions = [];
$approvedUsers = [];
$pendingArticles = [];
$galleryImages = [];
$admins = [];
$pendingDonations = [];
$pendingExperience = [];
$pendingParticipation = [];
$recentEvents = [];
$recentAnnouncements = [];

if ($isLoggedIn && hasPermission($permissions, 'user_approval')) {
	$res = $conn->query("SELECT * FROM submissions WHERE status = 'pending' ORDER BY submission_date DESC");
	if ($res) {
		$pendingSubmissions = $res->fetch_all(MYSQLI_ASSOC);
	}

	$res = $conn->query("SELECT * FROM submissions WHERE status = 'dismissed' ORDER BY submission_date DESC");
	if ($res) {
		$dismissedSubmissions = $res->fetch_all(MYSQLI_ASSOC);
	}

	$res = $conn->query('SELECT * FROM users ORDER BY approveddate DESC');
	if ($res) {
		$approvedUsers = $res->fetch_all(MYSQLI_ASSOC);
	}
}

if ($isLoggedIn && hasPermission($permissions, 'articles')) {
	$res = $conn->query("SELECT * FROM articles WHERE status = 'pending' ORDER BY created_at DESC");
	if ($res) {
		$pendingArticles = $res->fetch_all(MYSQLI_ASSOC);
	}
}

if ($isLoggedIn && hasPermission($permissions, 'events')) {
	$res = $conn->query('SELECT * FROM events ORDER BY id DESC LIMIT 50');
	if ($res) {
		$recentEvents = $res->fetch_all(MYSQLI_ASSOC);
	}
}

if ($isLoggedIn && hasPermission($permissions, 'announcements')) {
	$res = $conn->query('SELECT * FROM announcements ORDER BY id DESC LIMIT 50');
	if ($res) {
		$recentAnnouncements = $res->fetch_all(MYSQLI_ASSOC);
	}
}

if ($isLoggedIn && hasPermission($permissions, 'gallery')) {
	$res = $conn->query('SELECT id, image_path, uploaded_at FROM gallery ORDER BY id DESC LIMIT 120');
	if ($res) {
		$galleryImages = $res->fetch_all(MYSQLI_ASSOC);
	}
}

if ($isLoggedIn && hasPermission($permissions, 'manage_admins')) {
	$res = $conn->query('SELECT * FROM moderators ORDER BY username ASC');
	if ($res) {
		$admins = $res->fetch_all(MYSQLI_ASSOC);
	}
}

if ($isLoggedIn && hasPermission($permissions, 'certificates')) {
	$res = $conn->query("SELECT * FROM donations WHERE status = 'pending' ORDER BY id DESC");
	if ($res) {
		$pendingDonations = $res->fetch_all(MYSQLI_ASSOC);
	}

	$res = $conn->query("SELECT * FROM experience WHERE status = 'pending' ORDER BY id DESC");
	if ($res) {
		$pendingExperience = $res->fetch_all(MYSQLI_ASSOC);
	}

	$res = $conn->query("SELECT * FROM participation WHERE status = 'pending' ORDER BY id DESC");
	if ($res) {
		$pendingParticipation = $res->fetch_all(MYSQLI_ASSOC);
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin Panel | Satrangi Salaam</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/style.css">
	<link rel="stylesheet" href="../css/admin.css">
</head>
<body>
<div class="admin-bg-shape admin-bg-shape--a"></div>
<div class="admin-bg-shape admin-bg-shape--b"></div>

<?php include '../includes/nav.php'; ?>

<main class="admin-shell">
	<?php if (!$isLoggedIn): ?>
		<section class="login-card">
			<div class="login-card__head">
				<p class="eyebrow">Secure Workspace</p>
				<h1>Admin Login</h1>
				<p>Use your admin credentials to enter the privilege-based panel.</p>
			</div>

			<?php foreach ($flashMessages as $flash): ?>
				<div class="alert alert--<?php echo e($flash['type']); ?>"><?php echo e($flash['message']); ?></div>
			<?php endforeach; ?>

			<form method="post" class="grid-one">
				<input type="hidden" name="action" value="admin_login">
				<label>
					Username
					<input type="text" name="username" required autocomplete="username">
				</label>
				<label>
					Password
					<span class="pw-field">
						<input type="password" name="password" required autocomplete="current-password" data-password-input>
						<button type="button" class="pw-toggle" data-password-toggle aria-label="Show password">Show</button>
					</span>
				</label>
				<button class="btn btn--primary" type="submit">Login</button>
			</form>
		</section>
	<?php else: ?>
		<header class="admin-topbar">
			<div>
				<p class="eyebrow">Privilege-Based Control</p>
				<h1>Admin Panel</h1>
				<p class="muted">Signed in as <strong><?php echo e($schema['moderators_display_name'] && !empty($currentAdmin['display_name']) ? $currentAdmin['display_name'] : $currentAdmin['username']); ?></strong><?php echo $isSuperAdmin ? ' (Super Admin)' : ''; ?></p>
			</div>
			<div class="topbar-actions">
				<a class="btn" href="../index.php">View Website</a>
				<a class="btn btn--danger" href="index.php?logout=1">Logout</a>
			</div>
		</header>

		<?php foreach ($flashMessages as $flash): ?>
			<div class="alert alert--<?php echo e($flash['type']); ?>"><?php echo e($flash['message']); ?></div>
		<?php endforeach; ?>

		<section class="perm-chips">
			<?php foreach ($permissions as $key => $allowed): ?>
				<span class="chip <?php echo $allowed ? 'chip--on' : 'chip--off'; ?>"><?php echo e(str_replace('_', ' ', ucfirst($key))); ?></span>
			<?php endforeach; ?>
		</section>

		<?php if (hasPermission($permissions, 'manage_admins')): ?>
			<details class="admin-section" id="section-admin-privileges">
				<summary>Admin Accounts & Privileges</summary>
				<div class="admin-section__body two-col">
					<div class="card">
						<h3>Create New Admin</h3>
						<form method="post" class="grid-one">
							<input type="hidden" name="csrf" value="<?php echo e($_SESSION['admin_csrf']); ?>">
							<input type="hidden" name="action" value="create_admin">
							<label>
								Display Name
								<input type="text" name="display_name" placeholder="Optional">
							</label>
							<label>
								Username
								<input type="text" name="new_username" required>
							</label>
							<label>
								Password
								<span class="pw-field">
									<input type="password" name="new_password" required data-password-input>
									<button type="button" class="pw-toggle" data-password-toggle aria-label="Show password">Show</button>
								</span>
							</label>
							<?php if ($schema['admins_privileges'] && $isSuperAdmin): ?>
								<label class="inline-check"><input type="checkbox" name="is_super_admin" value="1"> Make super admin</label>
							<?php endif; ?>
							<button class="btn btn--primary" type="submit">Create Admin</button>
						</form>
					</div>

					<div class="card">
						<h3>Assign Section Privileges</h3>
						<form method="post" class="grid-one">
							<input type="hidden" name="csrf" value="<?php echo e($_SESSION['admin_csrf']); ?>">
							<input type="hidden" name="action" value="update_privileges">
							<label>
								Select Admin
								<select name="target_admin_id" required data-privileges-select>
									<option value="">Choose admin</option>
									<?php foreach ($admins as $admin): ?>
										<?php if (!empty($admin['is_super_admin'])) { continue; } ?>
										<option
											value="<?php echo (int) $admin['id']; ?>"
											data-can-manage-admins="<?php echo !empty($admin['can_manage_admins']) ? '1' : '0'; ?>"
											data-can-user-approval="<?php echo !empty($admin['can_user_approval']) ? '1' : '0'; ?>"
											data-can-announcements="<?php echo !empty($admin['can_announcements']) ? '1' : '0'; ?>"
											data-can-articles="<?php echo !empty($admin['can_articles']) ? '1' : '0'; ?>"
											data-can-events="<?php echo !empty($admin['can_events']) ? '1' : '0'; ?>"
											data-can-gallery="<?php echo !empty($admin['can_gallery']) ? '1' : '0'; ?>"
											data-can-certificates="<?php echo !empty($admin['can_certificates']) ? '1' : '0'; ?>"
											data-can-homepage="<?php echo !empty($admin['can_homepage']) ? '1' : '0'; ?>"
											data-can-careers="<?php echo !empty($admin['can_careers']) ? '1' : '0'; ?>"
											data-can-officers="<?php echo !empty($admin['can_officers']) ? '1' : '0'; ?>"
											data-can-in-the-news="<?php echo !empty($admin['can_in_the_news']) ? '1' : '0'; ?>"
											data-can-collaborators-sponsors="<?php echo !empty($admin['can_collaborators_sponsors']) ? '1' : '0'; ?>"
											data-can-impact="<?php echo !empty($admin['can_impact']) ? '1' : '0'; ?>"
											data-can-affiliates="<?php echo !empty($admin['can_affiliates']) ? '1' : '0'; ?>"
											data-can-reach="<?php echo !empty($admin['can_reach']) ? '1' : '0'; ?>"
										>
											<?php echo e($admin['username']); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</label>

							<div class="checks-grid" data-privileges-checklist>
								<label class="inline-check"><input type="checkbox" name="can_user_approval" value="1"> User Application Approval</label>
								<label class="inline-check"><input type="checkbox" name="can_announcements" value="1"> Announcements</label>
								<label class="inline-check"><input type="checkbox" name="can_articles" value="1"> Article Approval</label>
								<label class="inline-check"><input type="checkbox" name="can_events" value="1"> Events</label>
								<label class="inline-check"><input type="checkbox" name="can_gallery" value="1"> Gallery</label>
								<?php if ($schema['moderators_can_certificates']): ?>
									<label class="inline-check"><input type="checkbox" name="can_certificates" value="1"> Certificates</label>
								<?php endif; ?>
								<?php if ($schema['moderators_page_privileges']): ?>
									<label class="inline-check"><input type="checkbox" name="can_homepage" value="1"> Homepage</label>
									<label class="inline-check"><input type="checkbox" name="can_careers" value="1"> Careers</label>
									<label class="inline-check"><input type="checkbox" name="can_officers" value="1"> Officers</label>
									<label class="inline-check"><input type="checkbox" name="can_in_the_news" value="1"> In The News</label>
									<label class="inline-check"><input type="checkbox" name="can_collaborators_sponsors" value="1"> Collaborators & Sponsors</label>
									<label class="inline-check"><input type="checkbox" name="can_impact" value="1"> Impact</label>
									<label class="inline-check"><input type="checkbox" name="can_affiliates" value="1"> Affiliates</label>
									<label class="inline-check"><input type="checkbox" name="can_reach" value="1"> Reach</label>
								<?php else: ?>
									<p class="muted">Add page privilege columns in moderators table to enable separate checkboxes for Homepage, Careers, Officers, In The News, Collaborators & Sponsors, Impact, Affiliates, and Reach.</p>
								<?php endif; ?>
								<label class="inline-check"><input type="checkbox" name="can_manage_admins" value="1"> Manage Admins</label>
							</div>
							<button class="btn btn--primary" type="submit">Update Privileges</button>
						</form>
					</div>
				</div>
			</details>
		<?php endif; ?>

		<?php if (hasPermission($permissions, 'user_approval')): ?>
			<details class="admin-section" id="section-user-approval">
				<summary>User Application Approval</summary>
				<div class="admin-section__body">
					<div class="card">
						<h3>Pending Submissions (<?php echo count($pendingSubmissions); ?>)</h3>
						<?php if (empty($pendingSubmissions)): ?>
							<p class="muted">No pending submissions.</p>
						<?php else: ?>
							<?php foreach ($pendingSubmissions as $submission): ?>
								<form method="post" class="submission-box">
									<input type="hidden" name="csrf" value="<?php echo e($_SESSION['admin_csrf']); ?>">
									<input type="hidden" name="submission_id" value="<?php echo (int) $submission['id']; ?>">
									<?php
									$photoPath = trim((string) ($submission['photo'] ?? ''));
									$photoSrc = '';
									if ($photoPath !== '') {
										$normalizedPhoto = ltrim(str_replace('\\', '/', $photoPath), '/');
										$photoSrc = strpos($normalizedPhoto, '../') === 0 ? $normalizedPhoto : ('../' . $normalizedPhoto);
									}
									?>

									<div class="submission-photo-wrap">
										<p class="submission-photo-label">Applicant Photo</p>
										<?php if ($photoSrc !== ''): ?>
											<a href="<?php echo e($photoSrc); ?>" target="_blank" rel="noopener noreferrer" class="submission-photo-link">
												<img src="<?php echo e($photoSrc); ?>" alt="Applicant photo" class="submission-photo-img" loading="lazy">
											</a>
										<?php else: ?>
											<p class="muted">No photo uploaded.</p>
										<?php endif; ?>
									</div>

									<div class="submission-grid">
										<label>Name <input type="text" name="name" value="<?php echo e($submission['name'] ?? ''); ?>" required></label>
										<label>Preferred Name <input type="text" name="preferred_name" value="<?php echo e($submission['preferred_name'] ?? ''); ?>"></label>
										<label>Pronouns <input type="text" name="pronouns" value="<?php echo e($submission['pronouns'] ?? ''); ?>"></label>
										<label>Father Name <input type="text" name="father_name" value="<?php echo e($submission['father_name'] ?? ''); ?>"></label>
										<label>Post <input type="text" name="post" value="<?php echo e($submission['post'] ?? ''); ?>" required></label>
										<label>Reference <input type="text" name="reference" value="<?php echo e($submission['reference'] ?? ''); ?>"></label>
										<label>Address <input type="text" name="address" value="<?php echo e($submission['address'] ?? ''); ?>"></label>
										<label>Occupation <input type="text" name="occupation" value="<?php echo e($submission['occupation'] ?? ''); ?>"></label>
										<label>Mobile <input type="text" name="mobile_no" value="<?php echo e($submission['mobile_no'] ?? ''); ?>"></label>
										<label>Email <input type="email" name="email" value="<?php echo e($submission['email'] ?? ''); ?>" required></label>
										<label>City <input type="text" name="city" value="<?php echo e($submission['city'] ?? ''); ?>"></label>
									</div>

									<div class="submission-actions">
										<button class="btn btn--primary" type="submit" name="action" value="approve_submission">Approve</button>
										<button class="btn btn--danger" type="submit" name="action" value="dismiss_submission">Dismiss</button>
									</div>
								</form>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>

					<div class="two-col">
						<div class="card">
							<h3>Approved Users (<?php echo count($approvedUsers); ?>)</h3>
							<?php if (empty($approvedUsers)): ?>
								<p class="muted">No approved users found.</p>
							<?php else: ?>
								<?php foreach ($approvedUsers as $user): ?>
									<form method="post" class="row-stack">
										<input type="hidden" name="csrf" value="<?php echo e($_SESSION['admin_csrf']); ?>">
										<input type="hidden" name="action" value="move_user_exmember">
										<input type="hidden" name="user_id" value="<?php echo (int) $user['id']; ?>">
										<div>
											<strong><?php echo e($user['name']); ?></strong>
											<p class="muted"><?php echo e($user['email']); ?></p>
										</div>
										<div class="inline-controls">
											<select name="new_post" required>
												<option value="resigned">Resigned</option>
												<option value="removed">Removed</option>
												<option value="promoted">Promoted</option>
												<option value="demoted">Demoted</option>
											</select>
											<button class="btn" type="submit">Move</button>
										</div>
									</form>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>

						<div class="card">
							<h3>Dismissed Forms (<?php echo count($dismissedSubmissions); ?>)</h3>
							<?php if (empty($dismissedSubmissions)): ?>
								<p class="muted">No dismissed forms.</p>
							<?php else: ?>
								<?php foreach ($dismissedSubmissions as $dismissed): ?>
									<div class="row-stack">
										<strong><?php echo e($dismissed['name']); ?></strong>
										<p class="muted"><?php echo e($dismissed['email']); ?></p>
									</div>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</details>
		<?php endif; ?>

		<?php if (hasPermission($permissions, 'articles')): ?>
			<details class="admin-section" id="section-articles" open>
				<summary>Approve Submitted Articles</summary>
				<div class="admin-section__body">
					<div class="card">
						<h3>Pending Articles (<?php echo count($pendingArticles); ?>)</h3>
						<?php if (empty($pendingArticles)): ?>
							<p class="muted">No pending articles.</p>
						<?php else: ?>
							<?php foreach ($pendingArticles as $article): ?>
								<article class="submission-box">
									<h4><?php echo e($article['title']); ?></h4>
									<p class="muted">By <?php echo e($article['name']); ?> | <?php echo e($article['contact']); ?></p>
									<p><?php echo nl2br(e($article['content'])); ?></p>
									<div class="submission-actions">
										<form method="post">
											<input type="hidden" name="csrf" value="<?php echo e($_SESSION['admin_csrf']); ?>">
											<input type="hidden" name="action" value="approve_article">
											<input type="hidden" name="article_id" value="<?php echo (int) $article['id']; ?>">
											<button class="btn btn--primary" type="submit">Approve</button>
										</form>
										<form method="post">
											<input type="hidden" name="csrf" value="<?php echo e($_SESSION['admin_csrf']); ?>">
											<input type="hidden" name="action" value="dismiss_article">
											<input type="hidden" name="article_id" value="<?php echo (int) $article['id']; ?>">
											<button class="btn btn--danger" type="submit">Dismiss</button>
										</form>
									</div>
								</article>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</div>
			</details>
		<?php endif; ?>

		<?php if (hasPermission($permissions, 'announcements')): ?>
			<details class="admin-section" id="section-announcements" open>
				<summary>Announcements</summary>
				<div class="admin-section__body">
					<?php if (!$schema['announcements_hi']): ?>
						<p class="warn-box">Hindi columns are not present yet. Run the SQL query from the implementation note to enable bilingual announcement storage.</p>
					<?php endif; ?>
					<form method="post" enctype="multipart/form-data" class="card grid-one">
						<input type="hidden" name="csrf" value="<?php echo e($_SESSION['admin_csrf']); ?>">
						<input type="hidden" name="action" value="add_announcement">

						<h3>Publish Announcement</h3>
						<label>Title (English) <input type="text" name="title" required></label>
						<label>Content (English) <textarea name="content" rows="6" required></textarea></label>
						<label>Title (Hindi) <input type="text" name="title_hi" <?php echo $schema['announcements_hi'] ? 'required' : ''; ?>></label>
						<label>Content (Hindi) <textarea name="content_hi" rows="6" <?php echo $schema['announcements_hi'] ? 'required' : ''; ?>></textarea></label>
						<label>Images / PDFs (optional) <input type="file" name="announcement_media[]" multiple data-preview-target="preview-announcement-new"></label>
						<div id="preview-announcement-new" class="upload-preview" aria-live="polite"></div>
						<button class="btn btn--primary" type="submit">Publish Announcement</button>
					</form>

					<details class="card admin-subsection" id="section-announcements-manage">
						<summary>Manage Existing Announcements</summary>
						<div class="admin-subsection__body">
							<?php if (empty($recentAnnouncements)): ?>
								<p class="muted">No announcements found.</p>
							<?php else: ?>
								<?php foreach ($recentAnnouncements as $announcement): ?>
									<form method="post" enctype="multipart/form-data" class="card grid-one submission-box">
										<input type="hidden" name="csrf" value="<?php echo e($_SESSION['admin_csrf']); ?>">
										<input type="hidden" name="action" value="update_announcement">
										<input type="hidden" name="announcement_id" value="<?php echo (int) $announcement['id']; ?>">

										<h4>Announcement #<?php echo (int) $announcement['id']; ?></h4>
										<label>Title (English) <input type="text" name="title" value="<?php echo e($announcement['title'] ?? ''); ?>" required></label>
										<label>Content (English) <textarea name="content" rows="5" required><?php echo e($announcement['content'] ?? ''); ?></textarea></label>
										<label>Title (Hindi) <input type="text" name="title_hi" value="<?php echo e($announcement['title_hi'] ?? ''); ?>" <?php echo $schema['announcements_hi'] ? 'required' : ''; ?>></label>
										<label>Content (Hindi) <textarea name="content_hi" rows="5" <?php echo $schema['announcements_hi'] ? 'required' : ''; ?>><?php echo e($announcement['content_hi'] ?? ''); ?></textarea></label>

										<?php $announcementMedia = splitStoredMedia($announcement['images'] ?? ''); ?>
										<?php if (!empty($announcementMedia)): ?>
											<div class="existing-media-grid">
												<?php foreach ($announcementMedia as $mediaPath): ?>
													<label class="existing-media-item">
														<input type="checkbox" name="remove_announcement_media[]" value="<?php echo e($mediaPath); ?>">
														<span>Remove</span>
														<?php if (isImageMedia($mediaPath)): ?>
															<img src="<?php echo e($mediaPath); ?>" alt="Announcement media" loading="lazy">
														<?php else: ?>
															<a href="<?php echo e($mediaPath); ?>" target="_blank" rel="noopener noreferrer" class="media-file-link"><?php echo e(basename($mediaPath)); ?></a>
														<?php endif; ?>
													</label>
												<?php endforeach; ?>
											</div>
										<?php else: ?>
											<p class="muted">No media attached to this announcement.</p>
										<?php endif; ?>

										<label>Add new images / PDFs <input type="file" name="announcement_media_new[]" multiple data-preview-target="preview-announcement-<?php echo (int) $announcement['id']; ?>"></label>
										<div id="preview-announcement-<?php echo (int) $announcement['id']; ?>" class="upload-preview" aria-live="polite"></div>
										<button class="btn btn--primary" type="submit">Save Announcement Changes</button>
									</form>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>
					</details>
				</div>
			</details>
		<?php endif; ?>

		<?php if (hasPermission($permissions, 'events')): ?>
			<details class="admin-section" id="section-events" open>
				<summary>Events</summary>
				<div class="admin-section__body">
					<?php if (!$schema['events_hi']): ?>
						<p class="warn-box">Hindi event columns are not present yet. Run the SQL query from the implementation note to enable bilingual event storage.</p>
					<?php endif; ?>
					<form method="post" enctype="multipart/form-data" class="card grid-one">
						<input type="hidden" name="csrf" value="<?php echo e($_SESSION['admin_csrf']); ?>">
						<input type="hidden" name="action" value="add_event">

						<h3>Publish Event</h3>
						<label>Event Name (English) <input type="text" name="event_name" required></label>
						<label>Event Name (Hindi) <input type="text" name="event_name_hi" <?php echo $schema['events_hi'] ? 'required' : ''; ?>></label>
						<label>Event Date <input type="date" name="event_date" required></label>
						<label>Location (English) <input type="text" name="event_location" required></label>
						<label>Location (Hindi) <input type="text" name="event_location_hi" <?php echo $schema['events_hi'] ? 'required' : ''; ?>></label>
						<label>Description (English) <textarea name="event_description" rows="6" required></textarea></label>
						<label>Description (Hindi) <textarea name="event_description_hi" rows="6" <?php echo $schema['events_hi'] ? 'required' : ''; ?>></textarea></label>
						<label>Event Images (optional) <input type="file" name="event_images[]" multiple data-preview-target="preview-event-new"></label>
						<div id="preview-event-new" class="upload-preview" aria-live="polite"></div>
						<button class="btn btn--primary" type="submit">Publish Event</button>
					</form>

					<details class="card admin-subsection" id="section-events-manage">
						<summary>Manage Existing Events</summary>
						<div class="admin-subsection__body">
							<?php if (empty($recentEvents)): ?>
								<p class="muted">No events found.</p>
							<?php else: ?>
								<?php foreach ($recentEvents as $event): ?>
									<form method="post" enctype="multipart/form-data" class="card grid-one submission-box">
										<input type="hidden" name="csrf" value="<?php echo e($_SESSION['admin_csrf']); ?>">
										<input type="hidden" name="action" value="update_event">
										<input type="hidden" name="event_id" value="<?php echo (int) $event['id']; ?>">

										<h4>Event #<?php echo (int) $event['id']; ?></h4>
										<label>Event Name (English) <input type="text" name="event_name" value="<?php echo e($event['event_name'] ?? ''); ?>" required></label>
										<label>Event Name (Hindi) <input type="text" name="event_name_hi" value="<?php echo e($event['event_name_hi'] ?? ''); ?>" <?php echo $schema['events_hi'] ? 'required' : ''; ?>></label>
										<label>Event Date <input type="date" name="event_date" value="<?php echo e($event['event_date'] ?? ''); ?>" required></label>
										<label>Location (English) <input type="text" name="event_location" value="<?php echo e($event['event_location'] ?? ''); ?>" required></label>
										<label>Location (Hindi) <input type="text" name="event_location_hi" value="<?php echo e($event['event_location_hi'] ?? ''); ?>" <?php echo $schema['events_hi'] ? 'required' : ''; ?>></label>
										<label>Description (English) <textarea name="event_description" rows="5" required><?php echo e($event['event_description'] ?? ''); ?></textarea></label>
										<label>Description (Hindi) <textarea name="event_description_hi" rows="5" <?php echo $schema['events_hi'] ? 'required' : ''; ?>><?php echo e($event['event_description_hi'] ?? ''); ?></textarea></label>

										<?php $eventMedia = splitStoredMedia($event['event_image'] ?? ''); ?>
										<?php if (!empty($eventMedia)): ?>
											<div class="existing-media-grid">
												<?php foreach ($eventMedia as $mediaPath): ?>
													<label class="existing-media-item">
														<input type="checkbox" name="remove_event_media[]" value="<?php echo e($mediaPath); ?>">
														<span>Remove</span>
														<img src="<?php echo e($mediaPath); ?>" alt="Event image" loading="lazy">
													</label>
												<?php endforeach; ?>
											</div>
										<?php else: ?>
											<p class="muted">No event images attached.</p>
										<?php endif; ?>

										<label>Add new images <input type="file" name="event_images_new[]" multiple data-preview-target="preview-event-<?php echo (int) $event['id']; ?>"></label>
										<div id="preview-event-<?php echo (int) $event['id']; ?>" class="upload-preview" aria-live="polite"></div>
										<button class="btn btn--primary" type="submit">Save Event Changes</button>
									</form>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>
					</details>
				</div>
			</details>
		<?php endif; ?>

		<?php if (hasPermission($permissions, 'gallery')): ?>
			<details class="admin-section" id="section-gallery" open>
				<summary>Gallery Upload & Delete</summary>
				<div class="admin-section__body two-col">
					<form method="post" enctype="multipart/form-data" class="card grid-one">
						<input type="hidden" name="csrf" value="<?php echo e($_SESSION['admin_csrf']); ?>">
						<input type="hidden" name="action" value="upload_gallery">
						<h3>Upload Gallery Images</h3>
						<label>Select images <input type="file" name="gallery_images[]" multiple required data-preview-target="preview-gallery-new"></label>
						<div id="preview-gallery-new" class="upload-preview" aria-live="polite"></div>
						<button class="btn btn--primary" type="submit">Upload</button>
					</form>

					<form method="post" class="card grid-one">
						<input type="hidden" name="csrf" value="<?php echo e($_SESSION['admin_csrf']); ?>">
						<input type="hidden" name="action" value="delete_gallery">
						<h3>Delete Gallery Images</h3>
						<?php if (empty($galleryImages)): ?>
							<p class="muted">No gallery images available.</p>
						<?php else: ?>
							<div class="gallery-grid">
								<?php foreach ($galleryImages as $img): ?>
									<label class="gallery-item">
										<input type="checkbox" name="gallery_ids[]" value="<?php echo (int) $img['id']; ?>">
										<img src="../uploads/gallery/<?php echo rawurlencode($img['image_path']); ?>" alt="Gallery image">
									</label>
								<?php endforeach; ?>
							</div>
							<button class="btn btn--danger" type="submit">Delete Selected</button>
						<?php endif; ?>
					</form>
				</div>
			</details>
		<?php endif; ?>

		<?php if (hasPermission($permissions, 'certificates')): ?>
			<details class="admin-section" id="section-certificates" open>
				<summary>Certificates</summary>
				<div class="admin-section__body">
					<div class="card">
						<h3>Pending Donation Certificates (<?php echo count($pendingDonations); ?>)</h3>
						<?php if (empty($pendingDonations)): ?>
							<p class="muted">No pending donation certificates.</p>
						<?php else: ?>
							<?php foreach ($pendingDonations as $row): ?>
								<form method="post" class="submission-box">
									<input type="hidden" name="csrf" value="<?php echo e($_SESSION['admin_csrf']); ?>">
									<input type="hidden" name="donation_id" value="<?php echo (int) $row['id']; ?>">
									<div class="submission-grid">
										<label>Name <input type="text" name="name" value="<?php echo e($row['name']); ?>" required></label>
										<label>Amount <input type="number" min="1" name="amount" value="<?php echo (int) $row['amount']; ?>" required></label>
										<label>Email <input type="email" name="email" value="<?php echo e($row['email']); ?>" required></label>
										<label>WhatsApp <input type="text" name="whatsapp" value="<?php echo e($row['whatsapp']); ?>" required></label>
									</div>
									<div class="submission-actions">
										<button class="btn btn--primary" type="submit" name="action" value="approve_donation_certificate">Approve & Generate</button>
										<button class="btn btn--danger" type="submit" name="action" value="reject_donation_certificate">Reject</button>
									</div>
								</form>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>

					<div class="card">
						<h3>Pending Experience Certificates (<?php echo count($pendingExperience); ?>)</h3>
						<?php if (empty($pendingExperience)): ?>
							<p class="muted">No pending experience certificates.</p>
						<?php else: ?>
							<?php foreach ($pendingExperience as $row): ?>
								<form method="post" class="submission-box">
									<input type="hidden" name="csrf" value="<?php echo e($_SESSION['admin_csrf']); ?>">
									<input type="hidden" name="experience_id" value="<?php echo (int) $row['id']; ?>">
									<div class="submission-grid">
										<label>Name <input type="text" name="name" value="<?php echo e($row['name']); ?>" required></label>
										<label>Post <input type="text" name="post" value="<?php echo e($row['post']); ?>" required></label>
										<label>Period <input type="text" name="period" value="<?php echo e($row['period']); ?>" required></label>
										<label>Email <input type="email" name="email" value="<?php echo e($row['email']); ?>" required></label>
										<label>WhatsApp <input type="text" name="whatsapp" value="<?php echo e($row['whatsapp']); ?>" required></label>
									</div>
									<div class="submission-actions">
										<button class="btn btn--primary" type="submit" name="action" value="approve_experience_certificate">Approve & Generate</button>
										<button class="btn btn--danger" type="submit" name="action" value="reject_experience_certificate">Reject</button>
									</div>
								</form>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>

					<div class="card">
						<h3>Pending Participation Certificates (<?php echo count($pendingParticipation); ?>)</h3>
						<?php if (empty($pendingParticipation)): ?>
							<p class="muted">No pending participation certificates.</p>
						<?php else: ?>
							<?php foreach ($pendingParticipation as $row): ?>
								<form method="post" class="submission-box">
									<input type="hidden" name="csrf" value="<?php echo e($_SESSION['admin_csrf']); ?>">
									<input type="hidden" name="participation_id" value="<?php echo (int) $row['id']; ?>">
									<div class="submission-grid">
										<label>Name <input type="text" name="name" value="<?php echo e($row['name']); ?>" required></label>
										<label>Event Name <input type="text" name="event_name" value="<?php echo e($row['event_name']); ?>" required></label>
										<label>Event Date <input type="date" name="event_date" value="<?php echo e($row['event_date']); ?>" required></label>
										<label>Collaborators <input type="text" name="collaborators" value="<?php echo e($row['collaborators']); ?>"></label>
										<label>Email <input type="email" name="email" value="<?php echo e($row['email']); ?>" required></label>
										<label>WhatsApp <input type="text" name="whatsapp" value="<?php echo e($row['whatsapp']); ?>" required></label>
									</div>
									<div class="submission-actions">
										<button class="btn btn--primary" type="submit" name="action" value="approve_participation_certificate">Approve & Generate</button>
										<button class="btn btn--danger" type="submit" name="action" value="reject_participation_certificate">Reject</button>
									</div>
								</form>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>

					<div class="two-col">
						<form method="post" class="card grid-one">
							<input type="hidden" name="csrf" value="<?php echo e($_SESSION['admin_csrf']); ?>">
							<input type="hidden" name="action" value="generate_collaboration_certificate">
							<h3>Generate Collaboration Certificate</h3>
							<label>Name <input type="text" name="name" required></label>
							<label>Works (450 chars max) <textarea name="works" rows="5" maxlength="450" required></textarea></label>
							<button class="btn btn--primary" type="submit">Generate</button>
						</form>

						<form method="post" class="card grid-one">
							<input type="hidden" name="csrf" value="<?php echo e($_SESSION['admin_csrf']); ?>">
							<input type="hidden" name="action" value="generate_performance_certificate">
							<h3>Generate Performance Certificate</h3>
							<label>Name <input type="text" name="name" required></label>
							<label>Works (450 chars max) <textarea name="works" rows="5" maxlength="450" required></textarea></label>
							<button class="btn btn--primary" type="submit">Generate</button>
						</form>
					</div>
				</div>
			</details>
		<?php endif; ?>

		<?php if (hasPermission($permissions, 'homepage')): ?>
		<details class="admin-section" id="section-homepage">
			<summary>Homepage</summary>
			<div class="admin-section__body"><div class="coming-card">Homepage section controls will be added in an upcoming update.</div></div>
		</details>
		<?php endif; ?>

		<?php if (hasPermission($permissions, 'careers')): ?>
		<details class="admin-section" id="section-careers">
			<summary>Careers</summary>
			<div class="admin-section__body"><div class="coming-card">Careers section controls will be added in an upcoming update.</div></div>
		</details>
		<?php endif; ?>

		<?php if (hasPermission($permissions, 'officers')): ?>
		<details class="admin-section" id="section-officers">
			<summary>Officers</summary>
			<div class="admin-section__body"><div class="coming-card">Officers section controls will be added in an upcoming update.</div></div>
		</details>
		<?php endif; ?>

		<?php if (hasPermission($permissions, 'in_the_news')): ?>
		<details class="admin-section" id="section-in-the-news">
			<summary>In The News</summary>
			<div class="admin-section__body"><div class="coming-card">InTheNews section controls will be added in an upcoming update.</div></div>
		</details>
		<?php endif; ?>

		<?php if (hasPermission($permissions, 'collaborators_sponsors')): ?>
		<details class="admin-section" id="section-collaborators-sponsors">
			<summary>Collaborators And Sponsors</summary>
			<div class="admin-section__body"><div class="coming-card">CollaboratorsAndSponsors controls will be added in an upcoming update.</div></div>
		</details>
		<?php endif; ?>

		<?php if (hasPermission($permissions, 'impact')): ?>
		<details class="admin-section" id="section-impact">
			<summary>Impact</summary>
			<div class="admin-section__body"><div class="coming-card">Impact section controls will be added in an upcoming update.</div></div>
		</details>
		<?php endif; ?>

		<?php if (hasPermission($permissions, 'affiliates')): ?>
		<details class="admin-section" id="section-affiliates">
			<summary>Affiliates</summary>
			<div class="admin-section__body"><div class="coming-card">Affiliates section controls will be added in an upcoming update.</div></div>
		</details>
		<?php endif; ?>

		<?php if (hasPermission($permissions, 'reach')): ?>
		<details class="admin-section" id="section-reach">
			<summary>Reach</summary>
			<div class="admin-section__body"><div class="coming-card">Reach section controls will be added in an upcoming update.</div></div>
		</details>
		<?php endif; ?>
	<?php endif; ?>
</main>

<?php include '../includes/footer.php'; ?>

<script src="../js/shared-layout.js"></script>
<script src="../js/admin.js"></script>
</body>
</html>
