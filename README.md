# Sangnila LMS
## About
A web based learning management system application designed for admins, teachers, and students in Sangnila Arts Academy. The web application is still in development and is not ready to use yet. This web application is using Tailwind CSS and JQuery to manage its front end side and Laravel and MySQL to handle its backend side.

![Example Page](readme_imgs/example-page.png)

## Roles
There are 5 roles (types of account) in Sangnila LMS:
- **Admins**: Those who are in charge in observing and managing the whole LMS system including `courses`, `teachers`, `students`, and `accounts`
- **Teachers**: Those who are assigned to manage `topic and activities` also teaching and managing students' `progress`, `attendance`, and `assignment` in Sangnila courses
- **Students**: Those who are enrolled and is currently in learning progress in Sangnila courses, they are able to access learning `activities`, `attendance` data, and upload `assignments`
- **Parents**: Students' parents, those who are observing students' progress and learning results
- **Guests**: Public user, those who are don't have any account to use, can only view few parts of Sangnila LMS

## Change Logs
### v0.9.0-beta
- Changed main layout to be navbar-sidebar-content from previously only navbar and content
- Redesign all pages for students

### v0.9.1-beta
- Implemented popup submission forms
- Improvement and bug fixes in displaying popups and announcements
- Font and element sizing adjustments

### v0.9.2-beta
- Redesign all pages for teachers
- Now teacher can select students to include in a new attendance report
- Fix major bugs and mechanism of adding and editting attendance report when data is invalid

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
