<!-- PROJECT SHIELDS -->
<!--
*** I'm using markdown "reference style" links for readability.
*** Reference links are enclosed in brackets [ ] instead of parentheses ( ).
*** See the bottom of this document for the declaration of the reference variables
*** for contributors-url, forks-url, etc. This is an optional, concise syntax you may use.
*** https://www.markdownguide.org/basic-syntax/#reference-style-links
-->
<!-- [![Contributors][contributors-shield]][contributors-url]
[![Forks][forks-shield]][forks-url]
[![Stargazers][stars-shield]][stars-url]
[![Issues][issues-shield]][issues-url] -->
<!-- [![MIT License][license-shield]][license-url] -->
[![LinkedIn][linkedin-shield]][linkedin-url]



<!-- PROJECT LOGO -->
<br />
<p align="center">
  <a href="https://github.com/flxcn/volunteer-nexus">
    <img src="assets/images/logo.png" alt="Logo" width="80" height="80">
  </a>

  <h3 align="center">volunteer-nexus</h3>

  <p align="center">
    A unified platform to connect students with service opportunities in an organized and efficient manner.
    <br />
    <!-- <a href="https://github.com/othneildrew/Best-README-Template"><strong>Explore the docs »</strong></a> -->
    <br />
    <br />
    <a href="https://app.volunteernexus.com">View Live Version</a>
    ·
    <a href="https://github.com/flxcn/volunteer-nexus/issues">Report Bug</a>
    ·
    <a href="https://github.com/flxcn/volunteer-nexus/issues">Request Feature</a>
  </p>
</p>



<!-- TABLE OF CONTENTS -->
## Table of Contents

* [About the Project](#about-the-project)
  * [Built With](#built-with)
* [Getting Started](#getting-started)
  * [Prerequisites](#prerequisites)
  * [Installation](#installation)
* [Usage](#usage)
  * [Features](#features)
* [Roadmap](#roadmap)
<!-- * [Contributing](#contributing) -->
<!-- * [License](#license) -->
* [Contact](#contact)
* [Acknowledgements](#acknowledgements)



<!-- ABOUT THE PROJECT -->
## About The Project

[VolunteerNexus](https://volunteernexus.com)

VolunteerNexus is an online platform designed for volunteers to connect with meaningful service opportunities.
A list of commonly used resources that I find helpful are listed in the acknowledgements.

### Built With
* [PHP](https://php.net)
* [MySQL](https://www.mysql.com/)
* [Bootstrap](https://getbootstrap.com)
* [JQuery](https://jquery.com)
* [JavaScript](https://javascript.com/)
* [CSS](https://www.w3.org/Style/CSS/Overview.en.html)

<!-- GETTING STARTED -->
## Getting Started

This is an example of how you may give instructions on setting up your project locally.
To get a local copy up and running follow these simple example steps.

### Prerequisites

* [composer](https://getcomposer.org/download/)

### Installation

1. Clone the repo
```sh
git clone https://github.com/flxcn/volunteer-nexus.git
```
2. Install composer packages
```sh
php composer.phar install
```
4. Enter your database credentials in `config.php` 

```PHP
define('HOST', 'localhost');
define('USERNAME', 'ENTER YOUR USERNAME');
define('PASSWORD', 'ENTER YOUR PASSWORD');
define('DATABASE_NAME', 'volunteer_nexus');
```

   and in `DatabaseConnection.php`

```PHP
define('DB_HOST','localhost');
define('DB_NAME','volunteer_nexus');
define('DB_CHARSET','utf8mb4');
define('DB_USERNAME','ENTER YOUR USERNAME');
define('DB_PASSWORD','ENTER YOUR PASSWORD');
```
5. Enter your Google OAuth2.0 credentials in `google-oauth.php`
```PHP
$clientID = '<YOUR_CLIENT_ID>';
$clientSecret = '<YOUR_CLIENT_SECRET>';
$redirectUri = '<YOUR_REDIRECT_URI>';
```

6. Deploy to web server



<!-- USAGE EXAMPLES -->
## Usage

Use this space to show useful examples of how a project can be used. Additional screenshots, code examples and demos work well in this space. You may also link to more resources.

_For more examples, please refer to the [Documentation](https://example.com)_

<!-- FEATURES -->
## Features

- Google Account Sign-in Option and Account Creation
- AttendanceAnywhere
  - Automatic Volunteer ID (QR Code) Generation
  - Scanning Capabilities within Browser
- Sorting functionality when viewing tables, A-Z and Z-A
- Password reset option
- Day-before Email Reminders



<!-- ROADMAP -->
## Roadmap

See the [open issues](https://github.com/flxcn/volunteer-nexus/issues) for a list of proposed features (and known issues).



<!-- CONTRIBUTING -->
<!-- ## Contributing

<!-- Contributions are what make the open source community such an amazing place to be learn, inspire, and create. Any contributions you make are **greatly appreciated**. -->

<!-- 1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request -->



<!-- LICENSE -->
<!-- ## License -->

<!-- Distributed under the MIT License. See `LICENSE` for more information. -->



<!-- CONTACT -->
## Contact

Felix Chen - felix@volunteernexus.com

Project Link: [https://github.com/flxcn/volunteer-nexus](https://github.com/flxcn/volunteer-nexus)



<!-- ACKNOWLEDGEMENTS -->
## Acknowledgements
* [PHPMailer](https://github.com/PHPMailer/PHPMailer)
* [GitHub Emoji Cheat Sheet](https://www.webpagefx.com/tools/emoji-cheat-sheet)
* [Img Shields](https://shields.io)
* [Choose an Open Source License](https://choosealicense.com)
* [GitHub Pages](https://pages.github.com)
* [Animate.css](https://daneden.github.io/animate.css)
* [Loaders.css](https://connoratherton.com/loaders)
* [Slick Carousel](https://kenwheeler.github.io/slick)
* [Smooth Scroll](https://github.com/cferdinandi/smooth-scroll)
* [Sticky Kit](http://leafo.net/sticky-kit)
* [JVectorMap](http://jvectormap.com)
* [Font Awesome](https://fontawesome.com)





<!-- MARKDOWN LINKS & IMAGES -->
<!-- https://www.markdownguide.org/basic-syntax/#reference-style-links -->
[contributors-shield]: https://img.shields.io/github/contributors/flxcn/volunteer-nexus.svg?style=flat-square
[contributors-url]: https://github.com/flxcn/volunteer-nexus/graphs/contributors
[forks-shield]: https://img.shields.io/github/forks/flxcn/volunteer-nexus.svg?style=flat-square
[forks-url]: https://github.com/flxcn/volunteer-nexus/network/members
[stars-shield]: https://img.shields.io/github/stars/flxcn/volunteer-nexus.svg?style=flat-square
[stars-url]: https://github.com/flxcn/volunteer-nexus/stargazers
[issues-shield]: https://img.shields.io/github/issues/flxcn/volunteer-nexus.svg?style=flat-square
[issues-url]: https://github.com/flxcn/volunteer-nexus/issues
[license-shield]: https://img.shields.io/github/license/flxcn/volunteer-nexus.svg?style=flat-square
[license-url]: https://github.com/flxcn/volunteer-nexus/blob/master/LICENSE.txt
[linkedin-shield]: https://img.shields.io/badge/-LinkedIn-black.svg?style=flat-square&logo=linkedin&colorB=555
[linkedin-url]: https://www.linkedin.com/in/felixchen1a/
[product-screenshot]: assets/images/screenshot.png

