<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="DevTZ plans, designs, and builds clear websites and online systems for growing businesses.">
    <meta name="theme-color" content="#0d0d0d">
    <title>DevTZ — Websites and apps made simple.</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="stylesheet" href="{{ asset('css/portfolio.css') }}?v={{ filemtime(public_path('css/portfolio.css')) }}">
    <script src="{{ asset('js/portfolio.js') }}?v={{ filemtime(public_path('js/portfolio.js')) }}" defer></script>
</head>
<body>
    <a class="skip-link" href="#main">Skip to content</a>

    <header class="site-header" data-header>
        <a class="brand" href="#top" aria-label="DevTZ home">
            <span class="brand-mark" aria-hidden="true">D<span>/</span>TZ</span>
            <span class="brand-copy">Web & app<br>studio</span>
        </a>

        <button class="menu-button" type="button" aria-expanded="false" aria-controls="primary-nav" data-menu-button>
            <span class="sr-only">Toggle navigation</span>
            <span></span><span></span>
        </button>

        <nav class="primary-nav" id="primary-nav" aria-label="Primary navigation" data-nav>
            <a href="#about">About us</a>
            @if ($projects !== [])
                <a href="#work">Our work</a>
            @endif
            <a href="#services">Services</a>
            <a href="#team">Team</a>
            <a class="nav-cta" href="#contact">Contact <span aria-hidden="true">↗</span></a>
        </nav>
    </header>

    <main id="main">
        <section class="hero" id="top" aria-labelledby="hero-title">
            <div class="hero-grid">
                <div class="hero-copy">
                    <p class="eyebrow reveal">Websites and apps for growing businesses <span>Simple from day one</span></p>
                    <h1 id="hero-title" class="display reveal">
                        We make<br>
                        <span class="outline-word">digital</span> ideas<br>
                        feel <span class="scribble">simple.</span>
                    </h1>
                    <div class="hero-bottom reveal">
                        <p>Tell us what your business needs. We plan it, design it, build it, and help you after it goes live.</p>
                        <a class="round-link" href="{{ $projects !== [] ? '#work' : '#services' }}" aria-label="{{ $projects !== [] ? 'See selected work' : 'Explore our services' }}">
                            <span>{{ $projects !== [] ? 'See our work' : 'Our services' }}</span>
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 4v15m0 0 6-6m-6 6-6-6"/></svg>
                        </a>
                    </div>
                </div>

                <aside class="build-card simple-card reveal" aria-label="Our simple project approach">
                    <div class="window-bar">
                        <span></span><span></span><span></span>
                        <small>your idea → launch</small>
                    </div>
                    <div class="simple-steps">
                        <div><span>1</span><p><strong>You share the idea</strong><small>No technical words needed.</small></p></div>
                        <div><span>2</span><p><strong>We make a clear plan</strong><small>You know what happens next.</small></p></div>
                        <div><span>3</span><p><strong>Together, we launch</strong><small>Ready for real customers.</small></p></div>
                    </div>
                    <div class="build-status">
                        <span class="pulse"></span>
                        <p><strong>Clear at every step</strong><br>One team from idea to launch</p>
                    </div>
                </aside>
            </div>
        </section>

        <div class="ticker" aria-hidden="true">
            <div class="ticker-track">
                @for ($i = 0; $i < 2; $i++)
                    <span>Clear websites</span><b>✦</b>
                    <span>Easy to use</span><b>✦</b>
                    <span>Built for your goals</span><b>✦</b>
                    <span>Support after launch</span><b>✦</b>
                @endfor
            </div>
        </div>

        <section class="about section" id="about" aria-labelledby="about-title">
            <div class="about-grid">
                <div class="reveal">
                    <p class="kicker">About us</p>
                    <h2 id="about-title">We build software that works well and lasts.</h2>
                </div>
                <div class="about-story reveal">
                    <p class="about-lead">Founded on the conviction that high-performing software stems from rigorous architectural design, disciplined clean code practices, and uncompromising focus on speed.</p>
                    <p>At DevTZ, we do not ship bloated templates or fragile shortcuts. Every database query, every asynchronous job queue, and every frontend interaction is calibrated for sub-millisecond responsiveness and long-term maintainability.</p>
                </div>
            </div>
            <div class="about-values">
                <article class="reveal"><span>01</span><h3>Deterministic Reliability</h3><p>Strict automated test coverage, static analysis, and zero-compromise CI/CD.</p></article>
                <article class="reveal"><span>02</span><h3>High Concurrency by Design</h3><p>Laravel Octane, Swoole, and Redis caching topologies designed for tens of thousands of concurrent users.</p></article>
                <article class="reveal"><span>03</span><h3>Applied Intelligence</h3><p>Deep integration of vector databases, LLM agents, and semantic search directly in operational workflows.</p></article>
            </div>
        </section>

        @if ($projects !== [])
        <section class="work section" id="work" aria-labelledby="work-title">
            <div class="section-heading reveal">
                <div>
                    <p class="kicker">Selected work</p>
                    <h2 id="work-title">Built for real<br><i>business needs.</i></h2>
                </div>
                <p>A selection of websites, applications, and systems designed to solve practical problems clearly.</p>
            </div>

            <div class="project-list">
                @foreach ($projects as $project)
                    <article class="project reveal">
                        <div class="project-visual {{ $project['theme'] }} {{ count($project['images']) ? 'has-images' : '' }}">
                            @if (count($project['images']))
                                <div class="project-gallery" data-project-gallery data-autoplay-interval="2000" aria-label="{{ $project['name'] }} project screenshots">
                                    <div class="project-gallery-chrome" aria-hidden="true">
                                        <span class="project-gallery-lights"><i></i><i></i><i></i></span>
                                        <span class="project-gallery-address">devtz.com / selected work</span>
                                        <span class="project-gallery-view-count">{{ count($project['images']) }} {{ str('view')->plural(count($project['images'])) }}</span>
                                    </div>
                                    <div class="project-gallery-track" data-gallery-track>
                                        @foreach ($project['images'] as $image)
                                            <figure class="project-gallery-slide" data-gallery-slide aria-hidden="{{ $loop->first ? 'false' : 'true' }}">
                                                <img
                                                    @if ($loop->first) src="{{ $image['url'] }}" @else data-src="{{ $image['url'] }}" @endif
                                                    alt="{{ $image['alt'] }}"
                                                    loading="lazy"
                                                    fetchpriority="low"
                                                    decoding="async"
                                                >
                                            </figure>
                                        @endforeach
                                    </div>

                                    @if (count($project['images']) > 1)
                                        <div class="project-gallery-controls">
                                            <button type="button" data-gallery-previous aria-label="Show previous {{ $project['name'] }} screenshot">←</button>
                                            <span data-gallery-count aria-live="polite">1 / {{ count($project['images']) }}</span>
                                            <button type="button" data-gallery-next aria-label="Show next {{ $project['name'] }} screenshot">→</button>
                                        </div>
                                        <div class="project-gallery-dots" aria-label="Choose a {{ $project['name'] }} screenshot">
                                            @foreach ($project['images'] as $image)
                                                <button type="button" data-gallery-dot="{{ $loop->index }}" aria-label="Show screenshot {{ $loop->iteration }}" aria-current="{{ $loop->first ? 'true' : 'false' }}"></button>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @elseif ($project['theme'] === 'violet')
                                <div class="phone-mockup">
                                    <div class="phone-top"></div>
                                    <div class="product-orb"></div>
                                    <div class="mock-line wide"></div>
                                    <div class="mock-line"></div>
                                    <div class="mock-button">Explore</div>
                                </div>
                                <span class="visual-note note-one">simple checkout</span>
                                <span class="visual-note note-two">made for mobile</span>
                            @elseif ($project['theme'] === 'lime')
                                <div class="dashboard-mockup">
                                    <div class="dash-side"><b>m.</b><i></i><i></i><i></i></div>
                                    <div class="dash-main">
                                        <div class="dash-title"><span></span><small></small></div>
                                        <div class="chart"><i style="--h: 38%"></i><i style="--h: 62%"></i><i style="--h: 49%"></i><i style="--h: 84%"></i><i style="--h: 70%"></i></div>
                                        <div class="dash-row"><span></span><span></span><span></span></div>
                                    </div>
                                </div>
                                <span class="metric-bubble">Live<small>overview</small></span>
                            @else
                                <div class="community-mockup">
                                    <div class="community-nav">KIJIJI <span>•••</span></div>
                                    <div class="community-title">Good things<br>happen <i>together.</i></div>
                                    <div class="avatar-row"><span>K</span><span>M</span><span>A</span><span>+</span></div>
                                </div>
                                <svg class="visual-loop" viewBox="0 0 200 120" aria-hidden="true"><path d="M5 90C45 15 143 11 186 67c24 31-33 55-65 31C84 70 68 36 24 52"/></svg>
                            @endif
                            <span class="concept-label">{{ count($project['images']) ? 'Project gallery' : 'Selected project' }}</span>
                        </div>
                        <div class="project-copy">
                            <span class="project-index">{{ $project['index'] }}</span>
                            <div>
                                <p>{{ $project['type'] }}</p>
                                <h3>{{ $project['name'] }}</h3>
                                <p class="project-summary">{{ $project['summary'] }}</p>
                                @if (! empty($project['url']))
                                    <a class="project-visit" href="{{ $project['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="Visit {{ $project['name'] }} (opens in a new tab)">Visit project <span aria-hidden="true">↗</span></a>
                                @endif
                                <ul aria-label="Project benefits">
                                    @foreach ($project['tags'] as $tag)
                                        <li>{{ $tag }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
        @endif

        <section class="services section" id="services" aria-labelledby="services-title">
            <div class="services-intro reveal">
                <p class="kicker light">What we do</p>
                <h2 id="services-title">From idea<br><span>to launch.</span></h2>
                <p>You do not need to arrive with all the answers. We guide the project, explain every decision clearly, and take care of the difficult parts.</p>
            </div>
            <div class="service-list">
                @foreach ($services as $service)
                    <article class="service-item reveal">
                        <span>{{ $service['number'] }}</span>
                        <h3>{{ $service['title'] }}</h3>
                        <p>{{ $service['description'] }}</p>
                        <i aria-hidden="true">↗</i>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="team section" id="team" aria-labelledby="team-title">
            <div class="team-header reveal">
                <p class="kicker">Our team</p>
                <h2 id="team-title">Small team.<br>Fully involved.</h2>
                <p>You work directly with the people doing the work. That means quicker answers, fewer surprises, and a result that stays true to your goal.</p>
            </div>

            @if ($teamMembers->isNotEmpty())
                <div class="people-grid">
                    @foreach ($teamMembers as $teamMember)
                        <article class="person-card reveal">
                            <div class="person-avatar">
                                @if ($teamMember->photoUrl())
                                    <img src="{{ $teamMember->photoUrl() }}" alt="" loading="lazy" decoding="async">
                                @else
                                    <span>{{ str($teamMember->name)->substr(0, 2)->upper() }}</span>
                                @endif
                            </div>
                            <p>{{ $teamMember->role }}</p>
                            <h3>{{ $teamMember->name }}</h3>
                            @if ($teamMember->bio)<div class="person-bio">{{ $teamMember->bio }}</div>@endif
                            @if ($teamMember->skills)
                                <ul aria-label="Skills">
                                    @foreach ($teamMember->skills as $skill)<li>{{ $skill }}</li>@endforeach
                                </ul>
                            @endif
                        </article>
                    @endforeach
                </div>
            @else
                <div class="discipline-grid">
                    @foreach ($disciplines as $index => $discipline)
                        <article class="discipline reveal">
                            <div class="discipline-icon icon-{{ $index + 1 }}" aria-hidden="true"><span></span><span></span><span></span></div>
                            <p>0{{ $index + 1 }}</p>
                            <h3>{{ $discipline['title'] }}</h3>
                            <ul>
                                @foreach ($discipline['items'] as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </article>
                    @endforeach
                </div>
            @endif

            <div class="team-note reveal">
                <span>DevTZ</span>
                <p><strong>Our promise:</strong> clear communication, thoughtful work, and no unnecessary complexity.</p>
            </div>
        </section>

        <section class="process section" id="process" aria-labelledby="process-title">
            <div class="process-heading reveal">
                <p class="kicker light">How it works</p>
                <h2 id="process-title">Four clear<br>steps.</h2>
            </div>
            <ol class="process-list">
                <li class="reveal"><span>01</span><h3>Tell us</h3><p>Share your goal, your customers, and the problem you want to solve.</p></li>
                <li class="reveal"><span>02</span><h3>See the plan</h3><p>We agree on the pages, features, timing, and next steps before work begins.</p></li>
                <li class="reveal"><span>03</span><h3>Watch it grow</h3><p>You see progress early and can share feedback throughout the project.</p></li>
                <li class="reveal"><span>04</span><h3>Go live</h3><p>We launch carefully, show you how everything works, and remain available to help.</p></li>
            </ol>
        </section>

        <section class="contact section" id="contact" aria-labelledby="contact-title">
            <div class="contact-copy reveal">
                <p class="kicker">Ready when you are</p>
                <h2 id="contact-title">Tell us what<br><span>you need.</span></h2>
                <p>No technical words required. A simple description is enough to start.</p>
                <div class="contact-detail">
                    <span>We can help</span>
                    <p>Whether you need a new website, an online business system, or a better version of what you already have.</p>
                </div>
            </div>

            <form class="contact-form reveal" method="POST" action="{{ route('inquiries.store') }}" novalidate>
                @csrf

                @if (session('success'))
                    <div class="form-alert success" role="status">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="form-alert error" role="alert">Please check the highlighted fields and try again.</div>
                @endif

                <div class="field-grid">
                    <label>
                        <span>Your name</span>
                        <input type="text" name="name" value="{{ old('name') }}" autocomplete="name" placeholder="Jane Doe" required aria-describedby="name-error">
                        @error('name')<small id="name-error">{{ $message }}</small>@enderror
                    </label>
                    <label>
                        <span>Email address</span>
                        <input type="email" name="email" value="{{ old('email') }}" autocomplete="email" placeholder="jane@company.com" required aria-describedby="email-error">
                        @error('email')<small id="email-error">{{ $message }}</small>@enderror
                    </label>
                </div>
                <div class="field-grid">
                    <label>
                        <span>Company <i>(optional)</i></span>
                        <input type="text" name="company" value="{{ old('company') }}" autocomplete="organization" placeholder="Your team">
                    </label>
                    <label>
                        <span>What do you need?</span>
                        <select name="project_type" required aria-describedby="project-type-error">
                            <option value="">Choose one</option>
                            <option value="web-app" @selected(old('project_type') === 'web-app')>An online system or app</option>
                            <option value="website" @selected(old('project_type') === 'website')>A new website</option>
                            <option value="product-design" @selected(old('project_type') === 'product-design')>Improve what I have</option>
                            <option value="other" @selected(old('project_type') === 'other')>I am not sure yet</option>
                        </select>
                        @error('project_type')<small id="project-type-error">{{ $message }}</small>@enderror
                    </label>
                </div>
                <label>
                    <span>Tell us about the idea</span>
                    <textarea name="message" rows="5" placeholder="Tell us what you want to achieve..." required aria-describedby="message-error">{{ old('message') }}</textarea>
                    @error('message')<small id="message-error">{{ $message }}</small>@enderror
                </label>
                <button class="submit-button" type="submit">
                    <span>Send my idea</span>
                    <i aria-hidden="true">↗</i>
                </button>
                <p class="form-note">Your message is private and only used to discuss your project.</p>
            </form>
        </section>
    </main>

    <footer class="site-footer">
        <div class="footer-top">
            <a class="brand footer-brand" href="#top"><span class="brand-mark">D<span>/</span>TZ</span></a>
            <p>Websites and apps,<br>made simple.</p>
            <a href="#top">Back to top ↑</a>
        </div>
        <div class="footer-bottom">
            <p>© {{ now()->year }} DevTZ. All rights reserved.</p>
            <p>Independent · Collaborative · Intentional</p>
        </div>
    </footer>
</body>
</html>
