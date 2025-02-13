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
### v0.9.0-beta until v0.9.2-beta
- Changed main layout to be navbar-sidebar-content from previously only navbar and content
- Redesign all pages for students
- Implemented popup submission forms
- Improvement and bug fixes in displaying popups and announcements
- Font and element sizing adjustments
- Redesign all pages for teachers
- Now teacher can select students to include in a new attendance report
- Fix major bugs and mechanism of adding and editting attendance report when data is invalid

### v0.9.3-beta until v0.9.5-beta
- Changed the term `materials` into `activities`
- Added other option for uploading attendance reports
- Added learning outcomes into LMS where it can be uploaded by admins
- Added session number system into for both normal activities and syllabus activities
- Added learning outcomes - activities - sessions settings for both normal activities and syllabus activities
- Changed the student progress based on attendance data count instead of unlocked activities
- Adjustments in student's course details page where 1 topic can contains multiple activities and dynamically updated the learning outcome
- Teacher now can pick items from syllabus as an alternative of copying the whole activity - sessions - learning outcomes settings

### v0.9.6-beta until v0.9.8-beta
- Fixed bugs encountered in displaying data or performing some actions
- Now profile pictures will also be shown mostly next to student/teacher's name
- Student attendance progress is now counting for both attended and absent attendance data
- Admins now can input, edit, and delete attendance data and it will be merged with the attendance data uploaded by the teacher
- Added details to course data such as delivery mode, price, and code; then now displayed courses level beside courses name
- Improvements in some data displays (sorting, paginations)
- Changed the attendance upload mechanism to suit multiple sessions in 1 day
- Redesigned the attendance upload page

