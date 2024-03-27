@extends('front.pages.template.index')

@section('page')
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-light" id="mainNav">
        <div class="px-lg-5 container px-4">
            <a class="navbar-brand" href="index.html">Start Bootstrap</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive"
                aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                Menu
                <svg class="svg-inline--fa fa-bars" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="bars"
                    role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                    <path fill="currentColor"
                        d="M0 96C0 78.3 14.3 64 32 64H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H416c17.7 0 32 14.3 32 32z">
                    </path>
                </svg><!-- <i class="fas fa-bars"></i> Font Awesome fontawesome.com -->
            </button>
            <div class="navbar-collapse collapse" id="navbarResponsive">
                <ul class="navbar-nav py-lg-0 ms-auto py-4">
                    <li class="nav-item"><a class="nav-link px-lg-3 py-lg-4 py-3" href="index.html">Home</a></li>
                    <li class="nav-item"><a class="nav-link px-lg-3 py-lg-4 py-3" href="about.html">About</a></li>
                    <li class="nav-item"><a class="nav-link px-lg-3 py-lg-4 py-3" href="post.html">Sample Post</a></li>
                    <li class="nav-item"><a class="nav-link px-lg-3 py-lg-4 py-3" href="contact.html">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Page Header-->
    <header class="masthead" style="background-image: url('assets/img/home-bg.jpg')">
        <div class="position-relative px-lg-5 container px-4">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <div class="site-heading">
                        <h1>{{ $pageTitle }}</h1>
                        <span class="subheading">A Blog Theme by Start Bootstrap</span>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Main Content-->
    <div class="px-lg-5 container px-4">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-md-10 col-lg-8 col-xl-7">
                <!-- Post preview-->
                @foreach (getCategoryPosts('Blog Single') as $post)
                    <div class="post-preview">
                        <a href="post.html">
                            <h2 class="post-title">{{ $post->field('title') }}</h2>
                            <h3 class="post-subtitle">{{ $post->field('small-title') }}</h3>
                        </a>
                        <p class="post-meta">
                            Posted by
                            <a href="#!">{{ $post->field('author') }}</a>
                            on September 24, 2023
                        </p>
                    </div>
                @endforeach
                <!-- Divider-->
                <hr class="my-4">
                <!-- Pager-->
                <div class="d-flex justify-content-end mb-4"><a class="btn btn-primary text-uppercase" href="#!">Older
                        Posts →</a></div>
            </div>
        </div>
    </div>
@endsection
