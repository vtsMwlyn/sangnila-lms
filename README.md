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
### v1.1.x
- Added Google Calendar and Google Task synchronization feature for teachers
- Added forum discussion feature for teachers and students
- Error and bug fixes on forum discussion layout on smaller screens
- Implementation of the new footer
- Added sidebar toggler for larger screens

### v1.2.x
- Now students can upload portfolios for themself
- Responsive layout implementation for student pages
- Error and bug fixes during teacher attendance search feature and fix the issue where forms cannot be submitted on the enter key press
- Admins can now delete teacher's attendance
- Student lists separation based on their learning status for teachers

### v1.3.x
- Added system-generated certificate for completed students
- UI/UX improvements and several pages
- Implemented responsive sidebars to replace the dropdown sidebar menu
- Implemented responsive layout for teacher pages

### v1.4.x
- Added lecturer signatures to be included in system-generated certificates
- Added student attendance summary for teacher to help them tracking the students attendances
- Added monthly lecturer invoice generation feature
- Changed the lecturer invoice generation to include all student attendances from all teached courses
- Student selection separation based on their learning status for teacher and admin
- Portfolio uploads now support PDF files
- Fix on portfolio page to show and load .mov video files

### v1.5.x
- Added action status notifications for larger screens to replace action status badges
- Added lecturer reimburse upload feature and integrate it to lecturer invoice generation
- Added support to auto-refresh token so it will make form duration longer and API for other webapp to access Sangnila LMS and perform some action
- Implementation of lazy loading to all images and auto-convert uploaded images into .webp format
- Added new role `head of lecturer` and its functionality to see all portfolios and lecturer self attendances
- Adjustment into role mechanism and announcement showing mechanism
- Now input attendance page for admin will only show learning students

### v1.6.x
- Reworked guest pages for better display and data display
- Added learning outcomes, lecturer list, and mini statistic in guest course information
- Added material preview and portfolio showcase for guest
- Added portfolio highlighting feature for head of lecturer
- Added trial class functionality into Sangnila LMS including trial class resources where they can be uploaded by lecturers and will appear in guest pages and trial class attendances
- Integrate trial class attendance to lecturer invoice
- Added manage attendance feature to admin and removal of old lecturer attendance and show student attendance CRUD
- Page content separation into few tabs in few pages
- Added lecturer biography and it can be seen in guest page
- Converted all images into .webp and all accounts with old default password into the new one
- Bug fixes where mostly in filtering issue and some UI/UX improvements