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
### v0.7.0-beta
- Logic fixes and UI/UX improvement in import student and batch assign student for admin pages
- Applied glass morphism for content containers
- Improvements in text displays (resizing and transforming)
- Improvements in table data highlighting and column sizing/positioning
- Optimizing default password for accounts
- Improvements in notification badges for post-actions
- UI/UX improvement in login pages

### v0.7.1-beta
- Fixes in some notification badge dan validation rules
- Implementation of new account email verification feature
- Implementation of forgot password and remember me feature
- Implementation of breadcrumbs feature

### v0.7.2-beta
- Added password visibility toggle in all password input fields
- Now course topics and materials setup will be separated for teachers that hold the same course
- Added import from excel for importing students data

### v0.7.3-beta
- Added curriculum topics and materials for admins
- Teacher now can synchronize topic and materials in his/her class to the curriculum

### v0.7.4-beta
- Added import from excel for importing curriculums for admins
- Added import from excel for importing topics and materials for teachers
- Provided template excel import file, available for download
- UI/UX improvements in cards display and navbars

### v0.7.5-beta
- Now attendances are made flexible
- Some improvements in edit attendance
- Now admin can see student list in each teacher's teacher courses

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
