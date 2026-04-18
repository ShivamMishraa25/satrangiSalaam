(function () {
    function getCurrentLang() {
        var paramLang = new URLSearchParams(window.location.search).get('lang');
        if (paramLang === 'hi' || paramLang === 'hindi') {
            return 'hi';
        }
        if (paramLang === 'en') {
            return 'en';
        }

        var cookieMatch = document.cookie.match(/(?:^|; )lang=([^;]+)/);
        var cookieLang = cookieMatch ? decodeURIComponent(cookieMatch[1]) : '';
        return (cookieLang === 'hi' || cookieLang === 'hindi') ? 'hi' : 'en';
    }

    function applySharedLang(lang) {
        var normalized = (lang === 'hi') ? 'hi' : 'en';

        document.documentElement.lang = normalized === 'hi' ? 'hi' : 'en';
        document.body.classList.toggle('hindi', normalized === 'hi');

        if (window.SharedLayout && typeof window.SharedLayout.persistLangParam === 'function') {
            window.SharedLayout.persistLangParam(normalized);
        }
        if (window.SharedLayout && typeof window.SharedLayout.updateNavLinkLang === 'function') {
            window.SharedLayout.updateNavLinkLang(normalized);
        }
        if (window.SharedLayout && typeof window.SharedLayout.applySharedTranslations === 'function') {
            window.SharedLayout.applySharedTranslations(normalized);
        }

        var translateBtn = document.querySelector('.translate-btn');
        if (translateBtn) {
            translateBtn.textContent = normalized === 'hi' ? 'English' : 'हिंदी';
        }
    }

    var sections = document.querySelectorAll('.admin-section[id]');
    var storageKey = 'adminSectionOpenState';
    var urlParams = new URLSearchParams(window.location.search);
    var sectionFromUrl = urlParams.get('section');
    var scrollFromUrl = parseInt(urlParams.get('scroll') || '0', 10);

    var savedState = {};
    try {
        savedState = JSON.parse(localStorage.getItem(storageKey) || '{}');
    } catch (e) {
        savedState = {};
    }

    sections.forEach(function (section) {
        if (Object.prototype.hasOwnProperty.call(savedState, section.id)) {
            section.open = !!savedState[section.id];
        }

        section.addEventListener('toggle', function () {
            savedState[section.id] = section.open;
            localStorage.setItem(storageKey, JSON.stringify(savedState));
        });
    });

    if (sectionFromUrl) {
        var targetSection = document.getElementById(sectionFromUrl);
        if (targetSection) {
            targetSection.open = true;
        }
    }

    if (scrollFromUrl > 0) {
        window.requestAnimationFrame(function () {
            window.scrollTo({ top: scrollFromUrl, behavior: 'auto' });
        });
    }

    var initialLang = getCurrentLang();
    applySharedLang(initialLang);
    window.toggleLanguage = function () {
        var nextLang = document.documentElement.lang === 'hi' ? 'en' : 'hi';
        applySharedLang(nextLang);
    };

    var forms = document.querySelectorAll('main.admin-shell form');
    forms.forEach(function (form) {
        form.addEventListener('submit', function () {
            var parentSection = form.closest('.admin-section[id]');
            if (!parentSection) {
                return;
            }

            var sectionInput = form.querySelector('input[name="return_section"]');
            if (!sectionInput) {
                sectionInput = document.createElement('input');
                sectionInput.type = 'hidden';
                sectionInput.name = 'return_section';
                form.appendChild(sectionInput);
            }
            sectionInput.value = parentSection.id;

            var scrollInput = form.querySelector('input[name="return_scroll"]');
            if (!scrollInput) {
                scrollInput = document.createElement('input');
                scrollInput.type = 'hidden';
                scrollInput.name = 'return_scroll';
                form.appendChild(scrollInput);
            }
            scrollInput.value = String(Math.max(window.scrollY || 0, 0));
        });
    });

    document.querySelectorAll('[data-password-toggle]').forEach(function (toggle) {
        toggle.addEventListener('click', function () {
            var field = toggle.closest('.pw-field');
            if (!field) {
                return;
            }

            var input = field.querySelector('[data-password-input]');
            if (!input) {
                return;
            }

            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            toggle.textContent = show ? 'Hide' : 'Show';
            toggle.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
        });
    });

    var privilegeSelect = document.querySelector('[data-privileges-select]');
    var privilegeChecklist = document.querySelector('[data-privileges-checklist]');

    function syncPrivilegesFromOption(option) {
        if (!privilegeChecklist) {
            return;
        }

        var privilegeMap = {
            can_manage_admins: 'data-can-manage-admins',
            can_user_approval: 'data-can-user-approval',
            can_announcements: 'data-can-announcements',
            can_articles: 'data-can-articles',
            can_events: 'data-can-events',
            can_gallery: 'data-can-gallery',
            can_certificates: 'data-can-certificates',
            can_homepage: 'data-can-homepage',
            can_careers: 'data-can-careers',
            can_officers: 'data-can-officers',
            can_in_the_news: 'data-can-in-the-news',
            can_collaborators_sponsors: 'data-can-collaborators-sponsors',
            can_impact: 'data-can-impact',
            can_affiliates: 'data-can-affiliates',
            can_reach: 'data-can-reach'
        };

        Object.keys(privilegeMap).forEach(function (name) {
            var checkbox = privilegeChecklist.querySelector('input[type="checkbox"][name="' + name + '"]');
            if (!checkbox) {
                return;
            }

            var rawValue = option ? option.getAttribute(privilegeMap[name]) : null;
            checkbox.checked = rawValue === '1';
        });
    }

    if (privilegeSelect && privilegeChecklist) {
        privilegeSelect.addEventListener('change', function () {
            var option = privilegeSelect.options[privilegeSelect.selectedIndex] || null;
            syncPrivilegesFromOption(option);
        });

        syncPrivilegesFromOption(privilegeSelect.options[privilegeSelect.selectedIndex] || null);
    }

    function renderUploadPreview(input, container) {
        if (!container) {
            return;
        }

        container.innerHTML = '';
        var files = Array.prototype.slice.call(input.files || []);
        if (!files.length) {
            return;
        }

        files.forEach(function (file) {
            var item = document.createElement('div');
            item.className = 'upload-preview-item';

            var isImage = /^image\//i.test(file.type) || /\.(jpg|jpeg|png|gif|webp)$/i.test(file.name);
            if (isImage) {
                var img = document.createElement('img');
                var blobUrl = URL.createObjectURL(file);
                img.src = blobUrl;
                img.alt = file.name;
                img.addEventListener('load', function () {
                    URL.revokeObjectURL(blobUrl);
                });
                item.appendChild(img);
            } else {
                var fileLabel = document.createElement('a');
                fileLabel.className = 'upload-preview-file';
                fileLabel.href = '#';
                fileLabel.textContent = file.name;
                fileLabel.addEventListener('click', function (event) {
                    event.preventDefault();
                });
                item.appendChild(fileLabel);
            }

            var meta = document.createElement('small');
            meta.textContent = file.name;
            item.appendChild(meta);
            container.appendChild(item);
        });
    }

    document.querySelectorAll('input[type="file"][data-preview-target]').forEach(function (input) {
        var targetId = input.getAttribute('data-preview-target');
        var container = targetId ? document.getElementById(targetId) : null;
        if (!container) {
            return;
        }

        input.addEventListener('change', function () {
            renderUploadPreview(input, container);
        });
    });

    document.addEventListener('click', function (event) {
        var target = event.target instanceof HTMLElement ? event.target.closest('button') : null;
        if (!target) {
            return;
        }

        if (target.matches('.btn--danger')) {
            var form = target.closest('form');
            if (!form) {
                return;
            }

            var actionInput = form.querySelector('input[name="action"]');
            if (!actionInput) {
                return;
            }

            var riskyActions = [
                'dismiss_submission',
                'dismiss_article',
                'delete_gallery',
                'move_user_exmember',
                'reject_donation_certificate',
                'reject_experience_certificate',
                'reject_participation_certificate'
            ];
            if (riskyActions.indexOf(actionInput.value) !== -1) {
                var ok = window.confirm('Are you sure you want to continue?');
                if (!ok) {
                    event.preventDefault();
                }
            }
        }
    });
})();
