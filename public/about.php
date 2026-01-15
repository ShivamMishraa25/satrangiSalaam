<?php
    include "../config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta property="og:site_name" content="Satrangi Salaam">
    <meta property="og:title" content="About Us | All India Satrangi Salaam Association">
    <meta property="og:description" content="About All India Satrangi Salaam Association (AISSA)">
    <meta property="og:type" content="website" />

    <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL; ?>favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo BASE_URL; ?>apple-touch-icon.png">

    <title>About Us | AISSA</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/about.css">
</head>
<body>
    <?php include "../includes/nav.php"; ?>

    <main class="about-page">
        <section class="about-hero">
            <div class="about-hero__bg" aria-hidden="true"></div>
            <div class="about-hero__inner">
                <div class="about-hero__content">
                    <p class="about-hero__badge" data-i18n="badge">About AISSA</p>
                    <h1 class="about-hero__title" data-i18n="title">About Us</h1>
                    <p class="about-hero__lead" data-i18n="lead">
                        All India Satrangi Salaam Association (AISSA) is a registered, lawful, and socially responsible organization that works in India with the core values of equality, dignity, inclusion, and social responsibility.
                    </p>

                    <div class="about-hero__highlights">
                        <div class="about-chip" data-i18n="chip1">Equality & Dignity</div>
                        <div class="about-chip" data-i18n="chip2">Inclusion</div>
                        <div class="about-chip" data-i18n="chip3">Social Responsibility</div>
                    </div>
                </div>

                <aside class="about-card" aria-label="Overview">
                    <h2 class="about-card__title" data-i18n="overviewTitle">Quick Overview</h2>
                    <ul class="about-card__list">
                        <li><strong data-i18n="overviewLabel1">Legal Status:</strong> <span data-i18n="overviewVal1">Registered Trust (Indian Trusts Act)</span></li>
                        <li><strong data-i18n="overviewLabel2">Headquarters:</strong> <span data-i18n="overviewVal2">Prayagraj, Uttar Pradesh</span></li>
                        <li><strong data-i18n="overviewLabel3">Core Values:</strong> <span data-i18n="overviewVal3">Equality, dignity, inclusion, responsibility</span></li>
                    </ul>
                </aside>
            </div>
        </section>

        <section class="about-section">
            <div class="about-container">
                <h2 class="about-section__title" data-i18n="introTitle">Who We Are</h2>
                <div class="about-prose">
                    <p data-i18n="introP1">AISSA is not meant for any single community, identity, caste, religion, or group. It exists for all those who believe in human dignity, mutual respect, and responsible social coexistence.</p>
                    <p data-i18n="introP2">AISSA is a Registered Trust, headquartered in Prayagraj, Uttar Pradesh, and all its activities are conducted strictly within a legal, ethical, and institutional framework.</p>
                </div>
            </div>
        </section>

        <section class="about-section about-section--alt">
            <div class="about-container">
                <h2 class="about-section__title" data-i18n="historyTitle">Establishment and Historical Background</h2>
                <div class="about-prose">
                    <p data-i18n="historyP1">Satrangi Salaam began in the year 2020 as an online initiative.</p>
                    <p data-i18n="historyP2">At that time, Bhartendu Vimal Dubey, along with a few associates, initiated dialogue through digital platforms to address discrimination, inequality, and prevailing social misconceptions.</p>
                    <p data-i18n="historyP3">This initiative was purely awareness-based and was not bound by any formal organizational structure.</p>
                    <p data-i18n="historyP4">Over time, considering the continuity of work, social necessity, and responsibility, it became evident that this initiative needed to be established as a lawful, accountable, and structured institution.</p>
                    <p data-i18n="historyP5">Accordingly, on 22 July 2024, formal resolutions were passed in Prayagraj, and the organization was officially constituted under the name:</p>

                    <div class="about-quote" role="note">
                        <p data-i18n="historyQuote">“All India Satrangi Salaam Association”</p>
                    </div>

                    <p data-i18n="historyP6">Subsequently, on 2 August 2024, the organization was registered under the Indian Trusts Act.</p>
                    <p data-i18n="historyP7">Since then, AISSA has been functioning as a registered public trust.</p>
                </div>
            </div>
        </section>

        <section class="about-section">
            <div class="about-container">
                <h2 class="about-section__title" data-i18n="meaningTitle">“Satrangi Salaam” — Meaning and Philosophy</h2>
                <div class="about-prose">
                    <p data-i18n="meaningP1">“Satrangi” means multiple colours, and “Salaam” means respect.</p>
                    <p data-i18n="meaningP2">This name is not derived from any religion, sect, or community, but is inspired by Indian social philosophy.</p>
                    <p data-i18n="meaningP3">We live in a society where discrimination based on colour, caste, language, identity, class, and ideology is common.</p>
                    <p data-i18n="meaningP4">Sometimes green–blue, sometimes black–white, sometimes caste and sub-caste — new reasons for division are continuously manufactured.</p>
                    <div class="about-quote about-quote--highlight" role="note">
                        <p data-i18n="meaningQuote">Diversity is not a cause of conflict, but the strength of society.</p>
                    </div>
                    <p data-i18n="meaningP5">Nature itself is not of a single colour. Family members are not identical. Even our fingers are not the same — yet they function together.</p>
                    <p data-i18n="meaningP6">AISSA believes in this principle of Unity in Diversity.</p>
                    <p data-i18n="meaningP7">The word “Salaam” is used here not as a religious expression, but as a symbol of respect, acceptance, and human dignity.</p>
                    <p data-i18n="meaningP8">It is also intended to promote linguistic tolerance. AISSA actively opposes harmful tendencies that detach us from our own languages and cultures.</p>
                </div>
            </div>
        </section>

        <section class="about-section about-section--alt">
            <div class="about-container">
                <h2 class="about-section__title" data-i18n="forTitle">Who Is AISSA For?</h2>
                <div class="about-prose">
                    <p data-i18n="forP1">AISSA is not limited to any single identity or group.</p>
                    <ul class="about-list">
                        <li data-i18n="forL1">Not restricted to any one community</li>
                        <li data-i18n="forL2">Not a caste-based, religion-based, or ideology-based institution</li>
                        <li data-i18n="forL3">Not driven by any political or religious agenda</li>
                    </ul>
                    <p data-i18n="forP2">AISSA is for all individuals who believe in equality, wish to stand against discrimination, understand social responsibility, and seek coexistence with dignity and respect.</p>
                    <p data-i18n="forP3">To become a member or collaborator of the organization, no identity claim is required — only agreement with the core principles.</p>
                </div>
            </div>
        </section>

        <section class="about-section">
            <div class="about-container">
                <h2 class="about-section__title" data-i18n="workTitle">What We Do (Our Work & Approach)</h2>
                <div class="about-prose">
                    <p data-i18n="workP1">We work across education, environment, health, human rights, and all domains essential for holistic human development.</p>
                    <p data-i18n="workP2">AISSA’s scope is not limited to awareness and dialogue alone. The objectives of the organization include:</p>
                    <ul class="about-list">
                        <li data-i18n="workL1">Challenging misconceptions prevalent in society</li>
                        <li data-i18n="workL2">Promoting respectful and meaningful dialogue</li>
                        <li data-i18n="workL3">Addressing health, mental balance, and dignity</li>
                        <li data-i18n="workL4">Translating inclusion into actual practice</li>
                    </ul>
                    <p data-i18n="workP3">We consciously work on neglected and uncomfortable issues that are often deliberately ignored.</p>
                    <ul class="about-list">
                        <li data-i18n="workL5">Does not represent any individual</li>
                        <li data-i18n="workL6">Does not interfere in anyone’s private life</li>
                        <li data-i18n="workL7">Is not a surveillance or control body</li>
                    </ul>
                    <p data-i18n="workP4">Our approach is neutral, ethical, and responsible.</p>
                </div>
            </div>
        </section>

        <section class="about-section about-section--alt">
            <div class="about-container">
                <h2 class="about-section__title" data-i18n="legalTitle">Legal Status and Transparency</h2>
                <div class="about-prose">
                    <p data-i18n="legalP1">AISSA is a registered trust and possesses all necessary legal credentials:</p>
                    <ul class="about-list">
                        <li data-i18n="legalL1">Registered under the Indian Trusts Act</li>
                        <li data-i18n="legalL2">Registration Number: 290/24 (IV)</li>
                        <li data-i18n="legalL3">NGO Darpan ID: UP/2025/0625395</li>
                        <li data-i18n="legalL4">Registered Main Office: Prayagraj, Uttar Pradesh</li>
                        <li data-i18n="legalL5">Email: SatrangiSalamSS@gmail.com</li>
                    </ul>
                    <p data-i18n="legalP2">Additional information is disclosed strictly on a Need-to-Know basis.</p>
                    <div class="about-quote" role="note">
                        <p data-i18n="legalQuote">Not all information is meant for public disclosure.</p>
                    </div>
                    <p data-i18n="legalP3">Therefore, the organization follows the Need-to-Know Principle to ensure institutional security, member privacy, and protection against misuse.</p>
                </div>
            </div>
        </section>

        <section class="about-section">
            <div class="about-container">
                <h2 class="about-section__title" data-i18n="govTitle">Governance, Ethics, and Accountability</h2>
                <div class="about-prose">
                    <p data-i18n="govP1">AISSA is not a person-centric organization. Its governance operates through Trust Deed, amended rules, internal codes, committees, and collective decision-making processes.</p>
                    <p data-i18n="govP2">Every position, responsibility, and decision falls within a defined institutional framework.</p>
                </div>
            </div>
        </section>

        <section class="about-section about-section--alt">
            <div class="about-container">
                <h2 class="about-section__title" data-i18n="discTitle">Disclaimer</h2>
                <div class="about-prose">
                    <p data-i18n="discP1">Content published on AISSA’s website and digital platforms may or may not reflect the official position of the organization, and may represent the personal views of authors or contributors.</p>
                    <p data-i18n="discP2">AISSA does not support hate, discrimination, or any unlawful or unethical conduct.</p>
                    <p data-i18n="discP3">The organization expects respectful and responsible behaviour from all users.</p>
                </div>
            </div>
        </section>
    </main>

    <?php include "../includes/footer.php"; ?>
    <script src="<?php echo BASE_URL; ?>js/shared-layout.js"></script>
    <script src="<?php echo BASE_URL; ?>js/about.js"></script>
</body>
</html>
