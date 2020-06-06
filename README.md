## OpenSnap
OpenSnap is a personal project to build an open-source implementation of the Snap Store used within the Snap Packaging system. It's very early, but the goal is to eventually be fully-compatible with the Snap Store API. This will allow the hosting of 3rd-party Snap Stores outside of the main Canonical app store, as Canonical seems to have no interest in open-sourcing their server components anytime soon.

## Building
OpenSnap is written in Laravel/PHP. Simply set up your NGINX and MySQL server for Laravel, install the `composer` dependencies, edit the `.env` file as necessary, and run `php artisan migrate`.