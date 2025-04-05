<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate Preview - {{ $student->full_name }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Cormorant+Garamond:wght@400;500;700&display=swap');

        body {
            background-color: #f5f5f5;
            padding: 0;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .certificate-container {
            width: 100%;
            max-width: 1000px;
            height: 700px;
            margin: 0 auto 50px;
            background-color: white;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
            border: 1px solid #ddd;
            transform: rotate(0deg);
            transform-origin: center;
            position: relative;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .certificate {
            padding: 10px;
            position: relative;
            background-color: white;
            font-family: 'Cormorant Garamond', 'Times New Roman', Times, serif;
            height: 100%;
            overflow: hidden;
        }

        .certificate-border {
            position: relative;
            border: 1px solid #c9a55a;
            padding: 20px 50px 30px 50px;
            background-color: #fffef8;
            height: 100%;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-sizing: border-box;
        }

        .corner-decoration {
            position: absolute;
            width: 25px;
            height: 25px;
            border: 2px solid #c9a55a;
            z-index: 1;
        }

        .top-left {
            top: -2px;
            left: -2px;
            border-right: none;
            border-bottom: none;
        }

        .top-right {
            top: -2px;
            right: -2px;
            border-left: none;
            border-bottom: none;
        }

        .bottom-left {
            bottom: -2px;
            left: -2px;
            border-right: none;
            border-top: none;
        }

        .bottom-right {
            bottom: -2px;
            right: -2px;
            border-left: none;
            border-top: none;
        }

        .certificate-header {
            display: flex;
            flex-direction: row;
            align-items: center;
            margin-bottom: 15px;
            position: relative;
            justify-content: space-between;
        }

        .logo-container {
            width: 80px;
            flex: 0 0 80px;
            display: flex;
            justify-content: center;
        }
        
        .school-logo-container {
            margin-right: 15px;
        }
        
        .deped-logo-container {
            margin-left: 15px;
        }

        .school-logo, .school-logo-placeholder, .deped-logo-placeholder, .deped-logo {
            width: 80px;
            height: 80px;
            object-fit: contain;
            margin: 0 auto;
        }

        .school-logo-placeholder, .deped-logo-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f4e9;
            border-radius: 50%;
            font-size: 40px;
            color: #c9a55a;
            border: 2px solid #c9a55a;
        }

        .header-text {
            text-align: center;
            flex: 1;
            padding: 0;
        }

        .republic {
            font-size: 14px;
            margin-bottom: 2px;
            font-family: 'Playfair Display', serif;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .department {
            font-size: 14px;
            margin-bottom: 6px;
            font-family: 'Playfair Display', serif;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .school-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 3px;
            color: #333;
            font-family: 'Playfair Display', serif;
        }

        .school-address {
            font-size: 14px;
            color: #555;
        }

        .certificate-ribbon {
            position: relative;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            background-color: #c9a55a;
            color: white;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .certificate-title {
            text-align: center;
            margin-bottom: 10px;
            padding: 5px 0;
        }

        .certificate-title h1 {
            font-size: 30px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
            font-family: 'Playfair Display', serif;
        }

        .award-type {
            font-size: 24px;
            font-weight: bold;
            color: #7d5327;
            margin-bottom: 3px;
        }

        .award-tagalog {
            font-size: 18px;
            font-style: italic;
            color: #7d5327;
        }

        .certificate-body {
            text-align: center;
            line-height: 1.5;
            position: relative;
            display: flex;
            flex: 1;
        }

        .decorative-line {
            width: 1px;
            background: linear-gradient(to bottom, transparent, #c9a55a 15%, #c9a55a 85%, transparent);
            flex: 0 0 1px;
            display: none;
        }

        .body-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .certificate-text {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .student-name {
            font-size: 28px;
            font-weight: bold;
            color: #7d5327;
            margin: 10px 0;
            display: inline-block;
            padding-bottom: 3px;
            font-family: 'Playfair Display', serif;
        }

        .certificate-description {
            font-size: 14px;
            margin: 15px auto 5px;
            max-width: 700px;
            text-align: justify;
            color: #444;
        }

        .certificate-note {
            font-size: 14px;
            margin: 5px auto 10px;
            max-width: 700px;
            text-align: justify;
            color: #444;
            font-style: italic;
        }

        .certificate-seal {
            position: absolute;
            right: 60px;
            bottom: 80px;
            opacity: 0.2;
        }

        .seal-circle {
            width: 80px;
            height: 80px;
            border: 2px solid #c9a55a;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #c9a55a;
            font-size: 35px;
        }

        .certificate-footer {
            display: flex;
            justify-content: space-between;
            position: relative;
            z-index: 2;
            padding: 0 10%;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .signature-container {
            text-align: center;
            width: 40%;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            margin-bottom: 5px;
        }

        .signatory-name {
            font-weight: bold;
            font-size: 16px;
        }

        .signatory-title {
            font-size: 14px;
        }

        .certificate-date {
            text-align: center;
            margin-top: 10px;
            font-size: 14px;
            font-style: italic;
            color: #555;
            position: relative;
            z-index: 2;
        }

        /* Button styling for language selector */
        .btn-outline-primary.active {
            background-color: #007bff;
            color: white;
        }
        
        /* Print styles */
        @media print {
            @page {
                size: landscape;
                margin: 0;
                padding: 0;
            }
            
            html, body {
                background-color: white !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                height: 100% !important;
                overflow: hidden !important;
            }

            .d-print-none {
                display: none !important;
            }

            .container-fluid {
                padding: 0 !important;
                margin: 0 auto !important;
                width: 100% !important;
                max-width: 100% !important;
                display: flex !important;
                justify-content: center !important;
                align-items: center !important;
                height: 100vh !important;
            }

            .certificate-container {
                box-shadow: none !important;
                border: none !important;
                margin: 0 auto !important;
                width: 1000px !important;  /* Match the preview size exactly */
                max-width: 1000px !important;
                height: 700px !important;  /* Match the preview size exactly */
                transform: none !important;
                transform-origin: center !important;
            }

            .certificate {
                padding: 10px !important; /* Match the preview padding */
                height: 100% !important;
                width: 100% !important;
            }

            .certificate-border {
                padding: 20px 50px 30px 50px !important; /* Match the preview padding */
                height: 100% !important;
                border: 1px solid #c9a55a !important;
            }

            /* Don't forcibly hide the Filipino version, let the currently displayed one show */
            #certificate-en[style*="display: none"],
            #certificate-tl[style*="display: none"] {
                display: none !important;
            }
            
            /* Ensure colors print correctly */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4" style="max-width: 1200px; margin: 0 auto;">
    <div class="d-print-none mb-4">
        <div class="row">
            <div class="col-md-6">
                <a href="{{ route('teacher.reports.certificates.generate', ['section_id' => $section->id, 'quarter' => $quarter]) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
            </div>
            <div class="col-md-6 text-end">
                <div class="btn-group me-2">
                    <button type="button" class="btn btn-outline-primary" id="lang-en">English</button>
                    <button type="button" class="btn btn-outline-primary" id="lang-tl">Filipino</button>
                </div>
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="fas fa-print me-1"></i> Print Certificate
                </button>
            </div>
        </div>
    </div>

    <!-- English Version -->
    <div class="certificate-container" id="certificate-en">
        <div class="certificate">
            <div class="certificate-border">
                <div class="corner-decoration top-left"></div>
                <div class="corner-decoration top-right"></div>
                <div class="corner-decoration bottom-left"></div>
                <div class="corner-decoration bottom-right"></div>
                
                <div class="certificate-header">
                    <div class="logo-container school-logo-container">
                        @if($school->logo_path)
                            <img src="{{ asset($section->school->logo_path) }}" alt="School Logo" class="school-logo">
                        @else
                            <div class="school-logo-placeholder">
                                <i class="fas fa-school"></i>
                            </div>
                        @endif
                    </div>
                    <div class="header-text">
                        <div class="republic">Republic of the Philippines</div>
                        <div class="department">Department of Education</div>
                        <div class="school-name">{{ $school->name }}</div>
                        <div class="school-address">{{ $school->address }}</div>
                    </div>
                    <div class="logo-container deped-logo-container">
                        <img src="{{ asset('images/logo.jpg') }}" alt="DepEd Logo" class="deped-logo">
                    </div>
                </div>

                <div class="certificate-ribbon">
                    <div class="ribbon-left"></div>
                    <div class="ribbon-center">Excellence</div>
                    <div class="ribbon-right"></div>
                </div>

                <div class="certificate-title">
                    <h1>Academic Excellence Award</h1>
                    <div class="award-type">{{ $award['title'] }}</div>
                </div>

                <div class="certificate-body">
                    <div class="decorative-line left"></div>
                    <div class="body-content">
                        <p class="certificate-text">
                            This certifies that
                        </p>

                        <p class="student-name">{{ $student->full_name }}</p>

                        <p class="certificate-text">
                            a student from section <strong>{{ $section->name }}</strong>, {{ $section->grade_level }},
                            has attained an average of <strong>{{ $award['average'] }}</strong> and passed all learning areas
                            for the {{ $quarter == 'Q1' ? '1st' : ($quarter == 'Q2' ? '2nd' : ($quarter == 'Q3' ? '3rd' : '4th')) }} Quarter
                            of School Year {{ $section->school_year }}.
                        </p>

                        <p class="certificate-description">
                            The Award for Academic Excellence within the quarter is given to learners from
                            grades 1 to 12 who have attained an average of at least 90 and passed all learning
                            areas.
                        </p>

                        <p class="certificate-note">
                            The Average Grade per Quarter is reported as a whole number following DepEd
                            Order No. 8, s. 2015.
                        </p>
                    </div>
                    <div class="decorative-line right"></div>
                </div>

                <div class="certificate-seal">
                    <div class="seal-circle">
                        <i class="fas fa-award"></i>
                    </div>
                </div>

                <div class="certificate-footer">
                    <div class="signature-container">
                        <div class="signature-line"></div>
                        <div class="signatory-name">{{ $section->adviser->name ?? 'Class Adviser' }}</div>
                        <div class="signatory-title">Class Adviser</div>
                    </div>

                    <div class="signature-container">
                        <div class="signature-line"></div>
                        <div class="signatory-name">{{ $school->principal }}</div>
                        <div class="signatory-title">School Principal</div>
                    </div>
                </div>

                <div class="certificate-date">
                    Issued on {{ date('F d, Y') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Filipino Version -->
    <div class="certificate-container" id="certificate-tl" style="display: none;">
        <div class="certificate">
            <div class="certificate-border">
                <div class="corner-decoration top-left"></div>
                <div class="corner-decoration top-right"></div>
                <div class="corner-decoration bottom-left"></div>
                <div class="corner-decoration bottom-right"></div>
                
                <div class="certificate-header">
                    <div class="logo-container school-logo-container">
                        @if($school->logo_path)
                            <img src="{{ asset($section->school->logo_path) }}" alt="School Logo" class="school-logo">
                        @else
                            <div class="school-logo-placeholder">
                                <i class="fas fa-school"></i>
                            </div>
                        @endif
                    </div>
                    <div class="header-text">
                        <div class="republic">Republika ng Pilipinas</div>
                        <div class="department">Kagawaran ng Edukasyon</div>
                        <div class="school-name">{{ $school->name }}</div>
                        <div class="school-address">{{ $school->address }}</div>
                    </div>
                    <div class="logo-container deped-logo-container">
                        <img src="{{ asset('images/logo.jpg') }}" alt="DepEd Logo" class="deped-logo">
                    </div>
                </div>

                <div class="certificate-ribbon">
                    <div class="ribbon-left"></div>
                    <div class="ribbon-center">Kahusayan</div>
                    <div class="ribbon-right"></div>
                </div>

                <div class="certificate-title">
                    <h1>Academic Excellence Award</h1>
                    <div class="award-type">{{ $award['title'] }}</div>
                </div>

                <div class="certificate-body">
                    <div class="decorative-line left"></div>
                    <div class="body-content">
                        <p class="certificate-text">
                            Ito ay nagpapatunay na si
                        </p>

                        <p class="student-name">{{ $student->full_name }}</p>

                        <p class="certificate-text">
                            isang mag-aaral mula sa seksyon <strong>{{ $section->name }}</strong>, {{ $section->grade_level }},
                            ay nakakuha ng average na <strong>{{ $award['average'] }}</strong> at pumasa sa lahat ng mga asignatura
                            para sa {{ $quarter == 'Q1' ? 'Unang' : ($quarter == 'Q2' ? 'Ikalawang' : ($quarter == 'Q3' ? 'Ikatlong' : 'Ikaapat na')) }} Markahan
                            ng Taong {{ $section->school_year }}.
                        </p>

                        <p class="certificate-description">
                            Ang Gawad para sa Kahusayan sa Akademiko sa loob ng markahan ay ibinibigay sa mga mag-aaral mula 
                            sa baitang 1 hanggang 12 na nakakuha ng average na hindi bababa sa 90 at pumasa sa lahat ng mga asignatura.
                        </p>

                        <p class="certificate-note">
                            Ang Average na Grado kada Markahan ay inuulat bilang isang buong numero alinsunod sa DepEd
                            Order No. 8, s. 2015.
                        </p>
                    </div>
                    <div class="decorative-line right"></div>
                </div>

                <div class="certificate-seal">
                    <div class="seal-circle">
                        <i class="fas fa-award"></i>
                    </div>
                </div>

                <div class="certificate-footer">
                    <div class="signature-container">
                        <div class="signature-line"></div>
                        <div class="signatory-name">{{ $section->adviser->name ?? 'Class Adviser' }}</div>
                        <div class="signatory-title">Tagapayo ng Klase</div>
                    </div>

                    <div class="signature-container">
                        <div class="signature-line"></div>
                        <div class="signatory-name">{{ $school->principal }}</div>
                        <div class="signatory-title">Punong-guro</div>
                    </div>
                </div>

                <div class="certificate-date">
                    Inilabas noong {{ date('F d, Y') }}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const langEn = document.getElementById('lang-en');
        const langTl = document.getElementById('lang-tl');
        const certEn = document.getElementById('certificate-en');
        const certTl = document.getElementById('certificate-tl');
            const printBtn = document.querySelector('button[onclick="window.print()"]');

        // Set English as default active
        langEn.classList.add('active');

        langEn.addEventListener('click', function() {
            certEn.style.display = 'block';
            certTl.style.display = 'none';
            langEn.classList.add('active');
            langTl.classList.remove('active');
        });

        langTl.addEventListener('click', function() {
            certEn.style.display = 'none';
            certTl.style.display = 'block';
            langEn.classList.remove('active');
            langTl.classList.add('active');
        });

            // Custom print handling
            if (printBtn) {
                printBtn.setAttribute('onclick', '');
                printBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Get the active certificate
                    const activeCert = certTl.style.display === 'block' ? certTl : certEn;
                    const inactiveCert = activeCert === certEn ? certTl : certEn;
                    
                    // Store original styles before printing
                    const origActiveDisplay = activeCert.style.display;
                    const origInactiveDisplay = inactiveCert.style.display;
                    
                    // Ensure only the active certificate is visible
                    activeCert.style.display = 'block';
                    inactiveCert.style.display = 'none';
                    
                    // Add a class to body for print-specific styles
                    document.body.classList.add('print-preview');
                    
                    // Adjust for printing
                    const certContainer = activeCert.querySelector('.certificate');
                    let originalTransform, originalPadding;
                    
                    if (certContainer) {
                        // Back up original styles
                        originalTransform = certContainer.style.transform;
                        originalPadding = certContainer.style.padding;
                        
                        // No need to apply custom scaling - we want to match the preview exactly
                        certContainer.style.transformOrigin = 'center center';
                    }
                    
                    // Print the document
                    window.print();
                    
                    // Remove the class and restore styles after printing
                    setTimeout(function() {
                        document.body.classList.remove('print-preview');
                        
                        // Restore original styles
                        if (certContainer) {
                            certContainer.style.transform = originalTransform;
                            certContainer.style.padding = originalPadding;
                        }
                    }, 1000);
                });
            }
    });
</script>
</body>
</html>
