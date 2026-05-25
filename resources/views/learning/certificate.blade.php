<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Certificate of Completion</title>
    <style>
        body {
            font-family: 'Georgia', Times, 'Times New Roman', serif;
            color: #333;
            background-color: #fcfbf7;
            padding: 20px;
        }

        .certificate-container {
            border: 15px double #6f42c1;
            padding: 40px;
            text-align: center;
            background-color: #fff;
            position: relative;
        }

        .logo {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 20px;
            font-weight: 800;
            color: #6f42c1;
            margin-bottom: 20px;
            letter-spacing: 1px;
        }

        .logo-sub {
            color: #ffc107;
        }

        .title {
            font-size: 38px;
            font-weight: bold;
            color: #1a1a1a;
            margin-top: 10px;
            margin-bottom: 5px;
            letter-spacing: 2px;
        }

        .subtitle {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 4px;
            color: #777;
            margin-bottom: 40px;
        }

        .presented-to {
            font-size: 16px;
            font-style: italic;
            color: #555;
            margin-bottom: 10px;
        }

        .student-name {
            font-size: 32px;
            font-weight: bold;
            color: #6f42c1;
            border-bottom: 2px solid #eee;
            width: 70%;
            margin: 0 auto 20px auto;
            padding-bottom: 10px;
        }

        .description {
            font-size: 16px;
            line-height: 1.6;
            color: #555;
            width: 80%;
            margin: 0 auto 50px auto;
        }

        .highlight {
            font-weight: bold;
            color: #333;
        }

        .signatures-table {
            width: 100%;
            margin-top: 40px;
            border-collapse: collapse;
        }

        .signatures-table td {
            width: 50%;
            vertical-align: bottom;
            text-align: center;
        }

        .signature-line {
            width: 60%;
            margin: 0 auto 5px auto;
            border-bottom: 1px dashed #777;
        }

        .signature-title {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #777;
        }

        .seal-container {
            margin-top: 20px;
            font-size: 11px;
            color: #999;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }
    </style>
</head>
<body>

<div class="certificate-container">
    <div class="logo">
        TUTOR<span class="logo-sub">CONNECT</span> ACADEMY
    </div>
    
    <div class="title">Certificate of Completion</div>
    <div class="subtitle">Awarded for Academic Excellence</div>

    <div class="presented-to">This certificate is proudly presented to</div>
    <div class="student-name">{{ $booking->student->user->name }}</div>

    <div class="description">
        for successfully completing the 1-on-1 tutoring syllabus in 
        <span class="highlight">{{ $booking->subject->name }}</span> under the instruction of 
        <span class="highlight">{{ $booking->tutor->user->name }}</span>.<br>
        This session was conducted on <span class="highlight">{{ $booking->date->format('F d, Y') }}</span> 
        for a duration of 1 hour.
    </div>

    <!-- Signatures -->
    <table class="signatures-table">
        <tr>
            <td>
                <!-- Dynamic Script Handwriting simulation -->
                <div style="font-family: 'Brush Script MT', cursive, Georgia; font-size: 24px; color: #555; margin-bottom:-5px;">
                    {{ $booking->tutor->user->name }}
                </div>
                <div class="signature-line"></div>
                <div class="signature-title">Course Instructor</div>
            </td>
            <td>
                <div style="font-family: 'Brush Script MT', cursive, Georgia; font-size: 24px; color: #555; margin-bottom:-5px;">
                    Kunal Maini
                </div>
                <div class="signature-line"></div>
                <div class="signature-title">Platform Administrator</div>
            </td>
        </tr>
    </table>

    <div class="seal-container">
        Verification ID: TC-CERT-{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }} &bull; Issued via TutorConnect Platform
    </div>
</div>

</body>
</html>
