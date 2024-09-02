# Sangnila LMS
## About
A web based learning management system application designed for admins, teachers, and students in Sangnila Arts Academy. The web application is still in development and is not ready to use yet. This web application is using Tailwind CSS and JQuery to manage its front end side and Laravel and MySQL to handle its backend side.

![Example Page](readme_imgs/example-page.png)

## Roles
There are 5 roles (types of account) in Sangnila LMS:
- **Admins**: Those who are in charge in observing and managing the whole LMS system including `courses`, `teachers`, `students`, and `accounts`
- **Teachers**: Those who are assigned to manage `topic and materials` also teaching and managing students' `progress`, `attendance`, and `assignment` in Sangnila courses
- **Students**: Those who are enrolled and is currently in learning progress in Sangnila courses, they are able to access learning `materials`, `attendance` data, and upload `assignments`
- **Parents**: Students' parents, those who are observing students' progress and learning results
- **Guests**: Public user, those who are don't have any account to use, can only view few parts of Sangnila LMS

## Change Logs
### v0.8.0-beta
- Fixes in misplaced error messages
- Error marking color and thickness improvement
- Improvement in error handling mechanism
- Removed dismiss option on notifications
- Auto remove read notifications since 30 days ago

### v0.8.1-beta
- Fix in announcements that can't display image from storage
- Now announcement will have its display period and will not shown if outside of the period
- Now material selection dropdown has search feature enabled to help admin and teacher

## Clone Project
Clone the repository in any desired directory. Web resources such as images, videos, scripts, and styles are included in `public` folder. To generate the database system and fill it will sample data, run this command in the terminal:
```bash
php artisan migrate
```
```bash
php artisan db:seed
```
Then to run the web application, first make sure that MySQL is activated and the database is already set. Next you can either type this in the browser URL if you have installed XAMPP and already set symbol link from the `project folder` to `htdocs` folder:
```bash
localhost/project-folder/public/
```
or by run this command in the terminal:
```bash
php artisan serve
```
