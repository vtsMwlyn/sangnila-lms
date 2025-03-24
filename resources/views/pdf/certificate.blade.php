<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>SangnilaArtsAcademy_Certificate_{{ $student->id }}_{{ $course->code }}</title>

        <style>
            @font-face {
                font-family: 'Poppins';
                src: url("{{ storage_path('fonts/Poppins-Light.ttf') }}") format('truetype');
                font-weight: 300;
                font-style: normal;
            }

            @font-face {
                font-family: 'Poppins';
                src: url("{{ storage_path('fonts/Poppins-Regular.ttf') }}") format('truetype');
                font-weight: 400;
                font-style: normal;
            }

            @font-face {
                font-family: 'Poppins';
                src: url("{{ storage_path('fonts/Poppins-SemiBold.ttf') }}") format('truetype');
                font-weight: 500;
                font-style: normal;
            }

            @font-face {
                font-family: 'Poppins';
                src: url("{{ storage_path('fonts/Poppins-Bold.ttf') }}") format('truetype');
                font-weight: 700;
                font-style: normal;
            }

            @font-face {
                font-family: 'Poppins';
                src: url("{{ storage_path('fonts/Poppins-Black.ttf') }}") format('truetype');
                font-weight: 900;
                font-style: normal;
            }

            @page {
                margin: 0;
                padding: 0;
            }

            body {
                font-family: 'Poppins';
            }
        </style>
    </head>

    <body>
        <img src="{{ public_path('img/certificate-bg-page1.png') }}" style="width: 100%; height: 100%; position: absolute; top: 0; left: 0; z-index: 0;">
        <div style="position: relative; width: 100%; height: 100%; z-index: 5;">
            <div style="position: absolute; left: 50px; top: 310px;">
                <div style="font-size: 52pt; font-weight: 700;">
                    {{ $assessment->custom_name ?? substr($student->full_name, 0, 25) }}
                </div>
                <div style="font-size: 14pt; font-weight: 400;">Who has completed Class of “{{ $course->course_name }}”</div>
            </div>
            <div style="position: absolute; right: 75px; top: 550px; color: white; font-size: 60pt; font-weight: 300; font-size: 14pt;">Bandung, {{ Carbon\Carbon::parse($assessment->updated_at)->format('d M Y') }}</div>
        </div>
    </body>

    <body>
        <img src="{{ public_path('img/certificate-bg-page2.png') }}" style="width: 100%; height: 100%; position: absolute; top: 0; left: 0; z-index: 0;">
        <div style="position: relative; width: 100%; height: 100%; z-index: 5;">
            <div style="position: absolute; top: 40px; width: 100%; padding-left: 30px; padding-right: 30px;">
                <div style="font-weight: 500; color: #344b9b;"><span style="font-size: 22pt; letter-spacing: -0.5px; transform: scaleX(0.9);">STUDENT EVALUATION</span></div>
                <div style="height: 3.5px; background-color: #344b9b; width: 95%;"></div>

                <div style="margin-top: 10px; padding-left: 20px; padding-right: 20px; width: 90%;">
                    <div style="display: inline-block; width: 185px; font-weight: 700; font-size: 16pt;">Performance</div>
                    <div style="display: inline-block; width: 25px; font-weight: 700; font-size: 16pt;">:</div>
                    <div style="display: inline-block; width: 200px; font-weight: 700; font-size: 16pt;">{{ ucwords($assessment->performance_score) }}</div>
                    <div style="font-size: 13pt; margin-top: -5px; line-height: 0.8;">{{ $assessment->performance_description }}</div>
                </div>

                <div style="margin-top: 10px; padding-left: 20px; padding-right: 20px; width: 90%;">
                    <div style="display: inline-block; width: 185px; font-weight: 700; font-size: 16pt;">Technical Skill</div>
                    <div style="display: inline-block; width: 25px; font-weight: 700; font-size: 16pt;">:</div>
                    <div style="display: inline-block; width: 200px; font-weight: 700; font-size: 16pt;">{{ ucwords($assessment->technical_skill_score) }}</div>
                    <div style="font-size: 13pt; margin-top: -5px; line-height: 0.8;">{{ $assessment->technical_skill_description }}</div>
                </div>

                <div style="margin-top: 10px; padding-left: 20px; padding-right: 20px; width: 90%;">
                    <div style="display: inline-block; width: 185px; font-weight: 700; font-size: 16pt;">Aesthetical Skill</div>
                    <div style="display: inline-block; width: 25px; font-weight: 700; font-size: 16pt;">:</div>
                    <div style="display: inline-block; width: 200px; font-weight: 700; font-size: 16pt;">{{ ucwords($assessment->aesthetical_skill_score) }}</div>
                    <div style="font-size: 13pt; margin-top: -5px; line-height: 0.8;">{{ $assessment->aesthetical_skill_description }}</div>
                </div>

                <div style="margin-top: 10px; padding-left: 20px; padding-right: 20px; width: 70%;">
                    <div style="display: inline-block; width: 185px; font-weight: 700; font-size: 16pt;">Overall</div>
                    <div style="display: inline-block; width: 25px; font-weight: 700; font-size: 16pt;">:</div>
                    <div style="display: inline-block; width: 200px; font-weight: 700; font-size: 16pt;">{{ ucwords($assessment->overall_score) }}</div>
                    <div style="font-size: 13pt; margin-top: -5px; line-height: 0.8;">{{ $assessment->overall_description }}</div>
                </div>
            </div>

            <div style="position: absolute; bottom: 40px; right: 50px;">
                @if($signature_img_path)
                    <center>
                        <img src="{{ storage_path($signature_img_path) }}" style="width: 150px; height: 150px;" alt="signature">
                    </center>
                @endif
                <div style="text-align: center; font-size: 15pt; color: #344b9b; margin-bottom: 5px;">{{ $teacher->full_name }}</div>
                <div style="height: 1.5px; background-color: #344b9b; width: 95%; margin: auto;"></div>
                <div style="text-align: center; font-size: 13pt; color: #344b9b; margin-top: -5px;">Lecturer</div>
            </div>
        </div>
    </body>
</html>