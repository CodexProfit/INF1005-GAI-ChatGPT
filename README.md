# Pet Lovers Community Onboarding Wizard

A full-stack PHP web application that guides new users through a multi-step onboarding wizard, stores records in a CSV flat file, and provides login/profile management features.

## Features

- Multi-step onboarding with separate pages:
  1. Username and password
  2. Personal info
  3. Profile photo upload
  4. Pet info + pet photo upload
  5. Confirmation and save
- Login for existing users
- Community profile listing
- Edit profile (all fields except username)
- Delete profile
- CSV-backed persistence (`data/users.csv`)

## Tech stack

- PHP
- HTML/CSS/Bootstrap
- JavaScript
- CSV flat-file storage

## Run locally

```bash
php -S 0.0.0.0:8000
```

Then open `http://localhost:8000`.

> Ensure the `uploads/` and `data/` directories are writable.
