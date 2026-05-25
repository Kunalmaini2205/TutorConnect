<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tutor;
use App\Models\Student;
use App\Models\Subject;
use App\Models\AvailabilitySlot;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Chat;
use App\Models\Message;
use App\Models\LearningProgress;
use App\Models\UploadedMaterial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Subjects
        $subjectsData = [
            ['name' => 'Mathematics', 'description' => 'Algebra, Calculus, Geometry, and Trigonometry', 'slug' => 'mathematics'],
            ['name' => 'Physics', 'description' => 'Classical Mechanics, Electromagnetism, and Thermodynamics', 'slug' => 'physics'],
            ['name' => 'Organic Chemistry', 'description' => 'Carbon compounds, reactions, mechanisms, and synthesis', 'slug' => 'organic-chemistry'],
            ['name' => 'Computer Science', 'description' => 'Data Structures, Algorithms, PHP, and Laravel web development', 'slug' => 'computer-science'],
            ['name' => 'English Literature', 'description' => 'Poetry, plays, novels, and critical analysis of classical texts', 'slug' => 'english-literature'],
            ['name' => 'World History', 'description' => 'Modern history, global conflicts, and ancient civilizations', 'slug' => 'world-history'],
            ['name' => 'General Science', 'description' => 'Introductory biology, chemistry, and earth sciences', 'slug' => 'general-science'],
        ];

        $subjects = [];
        foreach ($subjectsData as $data) {
            $subjects[] = Subject::create($data);
        }

        // 2. Create Admin
        User::create([
            'name' => 'System Admin',
            'email' => 'admin@tutorconnect.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
            'phone' => '+15550100',
        ]);

        // 3. Create Tutors
        $tutorData = [
            [
                'name' => 'John Doe',
                'email' => 'john.tutor@example.com',
                'title' => 'Experienced Math & Physics Professor',
                'hourly_rate' => 45.00,
                'experience' => 8,
                'qualification' => 'M.Sc. in Physics, MIT',
                'rating' => 4.8,
                'is_verified' => true,
                'subjects' => [0, 1], // Math, Physics
                'bio' => 'Hello! I have been teaching Mathematics and Physics for over 8 years. I specialize in making complex algebra and calculus concepts easy to understand for high school and university students.',
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane.tutor@example.com',
                'title' => 'Chemistry & Biology Specialist',
                'hourly_rate' => 50.00,
                'experience' => 10,
                'qualification' => 'Ph.D. in Organic Chemistry, Stanford',
                'rating' => 5.0,
                'is_verified' => true,
                'subjects' => [2, 6], // Organic Chemistry, General Science
                'bio' => 'Passion for chemistry and biology! I focus on conceptual clarity, reaction mechanisms, and real-world applications. I love helping students prep for college exams and MCAT chemistry sections.',
            ],
            [
                'name' => 'Bob Johnson',
                'email' => 'bob.tutor@example.com',
                'title' => 'Software Engineer & CS Instructor',
                'hourly_rate' => 60.00,
                'experience' => 5,
                'qualification' => 'B.S. in Computer Science, UC Berkeley',
                'rating' => 4.5,
                'is_verified' => true,
                'subjects' => [3], // CS
                'bio' => 'Learn programming and web development the hands-on way! I can teach you Python, PHP, Javascript, and frameworks like Laravel and React. Let us build some amazing projects together.',
            ],
            [
                'name' => 'Alice Brown',
                'email' => 'alice.tutor@example.com',
                'title' => 'Literature Tutor & Essay Coach',
                'hourly_rate' => 35.00,
                'experience' => 3,
                'qualification' => 'B.A. in English, Oxford University',
                'rating' => 0.0,
                'is_verified' => false, // Pending admin verification
                'subjects' => [4, 5], // English Lit, World History
                'bio' => 'Hi, I am Alice! I love classical literature, analyzing poetry, and helping students craft high-scoring academic essays. Looking forward to helping you improve your reading and writing skills.',
            ],
        ];

        $tutors = [];
        foreach ($tutorData as $index => $tData) {
            $user = User::create([
                'name' => $tData['name'],
                'email' => $tData['email'],
                'password' => Hash::make('password'),
                'role' => 'tutor',
                'status' => 'active',
                'bio' => $tData['bio'],
                'phone' => '+1555020' . $index,
            ]);

            $tutor = Tutor::create([
                'user_id' => $user->id,
                'title' => $tData['title'],
                'hourly_rate' => $tData['hourly_rate'],
                'bio' => $tData['bio'],
                'experience' => $tData['experience'],
                'qualification' => $tData['qualification'],
                'rating' => $tData['rating'],
                'is_verified' => $tData['is_verified'],
                'zoom_link' => $tData['is_verified'] ? 'https://zoom.us/mock-classroom-' . Str::slug($tData['name']) : null,
            ]);

            // Attach subjects
            foreach ($tData['subjects'] as $subIndex) {
                $tutor->subjects()->attach($subjects[$subIndex]->id);
            }
            $tutors[] = $tutor;
        }

        // 4. Create Students
        $studentData = [
            [
                'name' => 'Charlie Brown',
                'email' => 'charlie.student@example.com',
                'grade_level' => 'High School',
                'learning_goals' => 'Wants to improve grades in AP Physics and prepare for SAT math section.',
            ],
            [
                'name' => 'David Miller',
                'email' => 'david.student@example.com',
                'grade_level' => 'Undergraduate',
                'learning_goals' => 'Looking to learn web development with Laravel for a college capstone project.',
            ],
            [
                'name' => 'Emma Watson',
                'email' => 'emma.student@example.com',
                'grade_level' => 'Middle School',
                'learning_goals' => 'Needs help with homework assignments in general science and algebra.',
            ],
        ];

        $students = [];
        foreach ($studentData as $index => $sData) {
            $user = User::create([
                'name' => $sData['name'],
                'email' => $sData['email'],
                'password' => Hash::make('password'),
                'role' => 'student',
                'status' => 'active',
                'phone' => '+1555030' . $index,
            ]);

            $student = Student::create([
                'user_id' => $user->id,
                'grade_level' => $sData['grade_level'],
                'learning_goals' => $sData['learning_goals'],
            ]);
            $students[] = $student;
        }

        // Favorite John Doe & Jane Smith for Charlie Brown (Student 0)
        $students[0]->favoriteTutors()->attach([$tutors[0]->id, $tutors[1]->id]);

        // 5. Create Availability Slots for Tutors
        // Let's create slots for John Doe (Math - Tutor 0) and Jane Smith (Chem - Tutor 1)
        // Relative to current time
        $days = [0, 1, 2, 3, 4, 5];
        $times = [
            ['start' => '09:00:00', 'end' => '10:00:00'],
            ['start' => '11:00:00', 'end' => '12:00:00'],
            ['start' => '14:00:00', 'end' => '15:00:00'],
            ['start' => '16:00:00', 'end' => '17:00:00']
        ];

        $slots = [];
        // Populate slots for Tutors 0, 1, 2
        for ($tIdx = 0; $tIdx < 3; $tIdx++) {
            $tutor = $tutors[$tIdx];
            foreach ($days as $dayOffset) {
                $date = Carbon::today()->addDays($dayOffset)->toDateString();
                foreach ($times as $time) {
                    $slots[] = AvailabilitySlot::create([
                        'tutor_id' => $tutor->id,
                        'date' => $date,
                        'start_time' => $time['start'],
                        'end_time' => $time['end'],
                        'is_booked' => false,
                    ]);
                }
            }
        }

        // 6. Create Bookings, Payments, and Reviews
        // Charlie Brown (Student 0) booked John Doe (Tutor 0) in the past (yesterday)
        // Let's find a slot representing yesterday
        $yesterdayDate = Carbon::yesterday()->toDateString();
        // Manually create an availability slot representing yesterday for tutor 0 to book
        $pastSlot = AvailabilitySlot::create([
            'tutor_id' => $tutors[0]->id,
            'date' => $yesterdayDate,
            'start_time' => '14:00:00',
            'end_time' => '15:00:00',
            'is_booked' => true,
        ]);

        $booking1 = Booking::create([
            'student_id' => $students[0]->id,
            'tutor_id' => $tutors[0]->id,
            'subject_id' => $subjects[0]->id, // Math
            'slot_id' => $pastSlot->id,
            'date' => $yesterdayDate,
            'start_time' => '14:00:00',
            'end_time' => '15:00:00',
            'status' => 'completed',
            'total_price' => 45.00,
            'payment_status' => 'paid',
            'meet_link' => 'https://zoom.us/mock-classroom-john-doe',
            'status_notes' => 'Session finished successfully.',
        ]);

        // Create Payment for booking1
        Payment::create([
            'booking_id' => $booking1->id,
            'user_id' => $students[0]->user->id,
            'transaction_id' => 'TXN-' . strtoupper(Str::random(10)),
            'amount' => 45.00,
            'payment_method' => 'card',
            'status' => 'success',
        ]);

        // Create Review for booking1
        Review::create([
            'booking_id' => $booking1->id,
            'student_id' => $students[0]->id,
            'tutor_id' => $tutors[0]->id,
            'rating' => 5,
            'comment' => 'John is an excellent teacher. He explained trigonometry so clearly that I was able to solve my homework easily. Highly recommend!',
            'is_visible' => true,
        ]);

        // Create Learning Progress for booking1
        LearningProgress::create([
            'student_id' => $students[0]->id,
            'tutor_id' => $tutors[0]->id,
            'subject_id' => $subjects[0]->id,
            'notes' => 'Completed introductory algebra review and trigonometric functions. Student showed solid progress in resolving equations.',
            'progress_percentage' => 40,
            'recorded_date' => $yesterdayDate,
        ]);


        // Charlie Brown (Student 0) books Jane Smith (Tutor 1) for tomorrow
        $tomorrowDate = Carbon::tomorrow()->toDateString();
        // Book the slot tomorrow at 11:00 for Tutor 1 (Jane Smith)
        $tomorrowSlot = AvailabilitySlot::where('tutor_id', $tutors[1]->id)
            ->where('date', $tomorrowDate)
            ->where('start_time', '11:00:00')
            ->first();

        if ($tomorrowSlot) {
            $tomorrowSlot->update(['is_booked' => true]);

            $booking2 = Booking::create([
                'student_id' => $students[0]->id,
                'tutor_id' => $tutors[1]->id,
                'subject_id' => $subjects[2]->id, // Organic Chemistry
                'slot_id' => $tomorrowSlot->id,
                'date' => $tomorrowDate,
                'start_time' => '11:00:00',
                'end_time' => '12:00:00',
                'status' => 'accepted',
                'total_price' => 50.00,
                'payment_status' => 'paid',
                'meet_link' => 'https://zoom.us/mock-classroom-jane-smith',
                'status_notes' => 'Tutor accepted booking. Looking forward to the chemistry review.',
            ]);

            Payment::create([
                'booking_id' => $booking2->id,
                'user_id' => $students[0]->user->id,
                'transaction_id' => 'TXN-' . strtoupper(Str::random(10)),
                'amount' => 50.00,
                'payment_method' => 'card',
                'status' => 'success',
            ]);
        }

        // David Miller (Student 1) requests Bob Johnson (Tutor 2) for day after tomorrow (pending booking)
        $dayAfterTomorrowDate = Carbon::today()->addDays(2)->toDateString();
        $pendingSlot = AvailabilitySlot::where('tutor_id', $tutors[2]->id)
            ->where('date', $dayAfterTomorrowDate)
            ->where('start_time', '14:00:00')
            ->first();

        if ($pendingSlot) {
            $pendingSlot->update(['is_booked' => true]);

            Booking::create([
                'student_id' => $students[1]->id,
                'tutor_id' => $tutors[2]->id,
                'subject_id' => $subjects[3]->id, // Computer Science
                'slot_id' => $pendingSlot->id,
                'date' => $dayAfterTomorrowDate,
                'start_time' => '14:00:00',
                'end_time' => '15:00:00',
                'status' => 'pending',
                'total_price' => 60.00,
                'payment_status' => 'unpaid',
                'meet_link' => null,
                'status_notes' => 'Requested introduction session for Laravel.',
            ]);
        }

        // 7. Create Chats & Messages
        // Chat 1: Student Charlie Brown (User ID 5) <-> Tutor John Doe (User ID 1)
        $chat1 = Chat::create([
            'student_id' => $students[0]->id,
            'tutor_id' => $tutors[0]->id,
        ]);

        Message::create([
            'chat_id' => $chat1->id,
            'sender_id' => $students[0]->user->id,
            'message' => 'Hello Mr. Doe! I am looking forward to our algebra session. Should I prepare any materials beforehand?',
            'is_read' => true,
            'created_at' => Carbon::yesterday()->subHours(4),
        ]);

        Message::create([
            'chat_id' => $chat1->id,
            'sender_id' => $tutors[0]->user->id,
            'message' => 'Hi Charlie! Just bring your textbook and the homework assignments you had troubles with. We will go through them step-by-step.',
            'is_read' => true,
            'created_at' => Carbon::yesterday()->subHours(3),
        ]);

        Message::create([
            'chat_id' => $chat1->id,
            'sender_id' => $students[0]->user->id,
            'message' => 'Sounds great. Thank you! See you tomorrow.',
            'is_read' => true,
            'created_at' => Carbon::yesterday()->subHours(2),
        ]);

        // Chat 2: Student Charlie Brown <-> Tutor Jane Smith
        $chat2 = Chat::create([
            'student_id' => $students[0]->id,
            'tutor_id' => $tutors[1]->id,
        ]);

        Message::create([
            'chat_id' => $chat2->id,
            'sender_id' => $students[0]->user->id,
            'message' => 'Hello Dr. Smith, I just completed the booking for tomorrow. I need help with organic synthesis pathways.',
            'is_read' => false,
            'created_at' => Carbon::now()->subMinutes(30),
        ]);

        // 8. Upload Materials
        // Tutor 0 (John Doe) uploads a Math resource
        UploadedMaterial::create([
            'tutor_id' => $tutors[0]->id,
            'title' => 'Calculus Cheat Sheet',
            'description' => 'A handy single page summary of common derivatives, integrals, and limits.',
            'file_path' => 'materials/calculus_cheat_sheet_mock.pdf',
            'file_type' => 'pdf',
            'size' => 154200, // 150 KB
            'downloads' => 12,
        ]);

        // Tutor 1 (Jane Smith) uploads a Chem resource
        UploadedMaterial::create([
            'tutor_id' => $tutors[1]->id,
            'title' => 'Organic Reaction Mechanism Pack',
            'description' => 'A detailed PDF guide explaining nucleophilic substitution and elimination reactions.',
            'file_path' => 'materials/organic_chem_guide_mock.pdf',
            'file_type' => 'pdf',
            'size' => 1250400, // ~1.2 MB
            'downloads' => 28,
        ]);
    }
}
