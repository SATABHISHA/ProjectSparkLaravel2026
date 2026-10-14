<?php

namespace Database\Seeders;

use App\Models\ContentSection;
use App\Models\FocusArea;
use App\Models\Partner;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'site_name' => 'Project SPARK',
            'site_kicker' => 'School and Public Awareness for Responsible Knowledge of Health',
            'site_tagline' => 'Igniting Awareness, Inspiring Healthy Choices.',
            'site_intro' => 'A Healthier Tomorrow Begins with Awareness.',
            'hero_supporting' => 'Knowledge is the first step towards a healthier life. Awareness is the catalyst for change.',
            'collaboration_intro' => 'Two institutions, complementary strengths and one shared commitment to social well-being.',
            'focus_intro' => 'Programmes will be designed according to community needs, appropriate age groups, relevant health priorities and professional guidance.',
            'programmes_intro' => 'Health awareness travels further when learning is engaging, practical and shared.',
            'site_logo' => '/images/logo.jpeg',
            'address' => 'Narendrapur Nilachal complex plot no 9 Ram Krishna Misson Mandir Gate opposite Suravi sweet kolkata 700103',
            'contact_email' => '', 'contact_phone' => '',
            'footer_commitment' => 'Promoting responsible health awareness through education, collaboration and community engagement.',
            'disclaimer' => 'Project SPARK is an educational and public awareness initiative. Its content is intended for general informational purposes and does not replace professional medical advice, diagnosis or treatment. Individuals should consult qualified health professionals regarding personal medical concerns.',
            'smtp_host' => '', 'smtp_port' => '587', 'smtp_username' => '', 'smtp_password' => '',
            'smtp_encryption' => 'tls', 'smtp_from_address' => '', 'smtp_from_name' => 'Project SPARK',
        ];
        foreach ($settings as $key => $value) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $value, 'type' => 'text']);
        }

        $sections = [
            ['slug' => 'about', 'eyebrow' => 'About Project SPARK', 'title' => 'Two Institutions. One Vision. A Healthier Society.', 'subtitle' => 'Knowledge becomes powerful when it is understood, shared and put into practice.', 'body' => "Project SPARK represents a shared commitment by CE Foundation and CMRI to advance health awareness through education, engagement and collaboration.\n\nRecognising the importance of accessible health knowledge in shaping individual and community well-being, the initiative seeks to create meaningful opportunities for people to understand health-related issues, adopt preventive practices and make informed decisions.\n\nBy bringing together educational outreach and healthcare expertise, Project SPARK aims to make essential health knowledge more accessible, particularly to schoolchildren, adolescents and communities that can benefit from structured health awareness initiatives.\n\nWe believe that health education should begin early, extend beyond the classroom and remain relevant throughout life.\n\nOur purpose is simple: to transform health knowledge into awareness, awareness into action, and action into a healthier tomorrow.", 'sort_order' => 10],
            ['slug' => 'mission', 'eyebrow' => 'Our Mission', 'title' => 'Making Health Awareness Accessible to All.', 'subtitle' => 'Responsible, inclusive and evidence-informed health education for real life.', 'body' => 'Our mission is to promote responsible, inclusive and evidence-informed health education through structured school programmes and public awareness initiatives.', 'items' => [
                ['label' => 'Educate', 'text' => 'Promote age-appropriate health knowledge among schoolchildren and adolescents.'], ['label' => 'Inform', 'text' => 'Improve public understanding of essential health and wellness practices.'], ['label' => 'Prevent', 'text' => 'Encourage awareness of preventive healthcare and the importance of timely medical consultation.'], ['label' => 'Empower', 'text' => 'Enable individuals to make informed decisions about their health and well-being.'], ['label' => 'Engage', 'text' => 'Encourage meaningful participation by schools, families, healthcare professionals and communities.'], ['label' => 'Inspire', 'text' => 'Foster healthier habits and a lasting culture of health awareness.'],
            ], 'sort_order' => 20],
            ['slug' => 'schools', 'eyebrow' => 'Project SPARK in Schools', 'title' => 'Educating Young Minds. Inspiring Healthy Futures.', 'subtitle' => 'Because a child who understands health today can help build a healthier society tomorrow.', 'body' => 'Schools play a vital role in shaping lifelong habits and attitudes. Project SPARK aims to create opportunities for students to learn about health through engaging, age-appropriate and participatory educational activities.', 'items' => [
                ['label' => 'School-based health awareness sessions'], ['label' => 'Personal hygiene and sanitation education'], ['label' => 'Nutrition and healthy lifestyle workshops'], ['label' => 'Adolescent health awareness programmes'], ['label' => 'Interactive demonstrations and educational activities'], ['label' => 'Health awareness quizzes, competitions and campaigns'], ['label' => 'Sessions conducted with qualified healthcare professionals'], ['label' => 'Educational engagement with teachers and parents'],
            ], 'sort_order' => 40],
            ['slug' => 'community', 'eyebrow' => 'Community Outreach', 'title' => 'Taking Awareness Beyond the Classroom.', 'subtitle' => 'Health awareness becomes more effective when it reaches families and communities.', 'body' => 'Project SPARK aims to extend its educational outreach through community-focused programmes that encourage people to understand health concerns, recognise the value of preventive practices and seek appropriate professional guidance.', 'items' => [
                ['label' => 'Public health awareness campaigns'], ['label' => 'Community education programmes'], ['label' => 'Preventive healthcare awareness drives'], ['label' => 'Expert-led educational interactions'], ['label' => 'Dissemination of accessible health information'], ['label' => 'Family-oriented health awareness initiatives'], ['label' => 'Community engagement with schools and local organisations'],
            ], 'sort_order' => 50],
            ['slug' => 'approach', 'eyebrow' => 'Our Approach', 'title' => 'Learn. Understand. Act. Transform.', 'subtitle' => 'Meaningful health awareness turns information into everyday confidence.', 'body' => 'We believe that meaningful health awareness requires more than the communication of information. It requires understanding, participation and the ability to translate knowledge into everyday practice.', 'items' => [
                ['label' => 'Learn', 'text' => 'Access essential health knowledge through structured educational resources and awareness programmes.'], ['label' => 'Understand', 'text' => 'Develop a clearer understanding of health, hygiene, prevention and personal well-being.'], ['label' => 'Act', 'text' => 'Apply that knowledge through responsible decisions and healthier everyday practices.'], ['label' => 'Transform', 'text' => 'Contribute to a culture of awareness that benefits individuals, families, schools and communities.'],
            ], 'sort_order' => 60],
            ['slug' => 'why', 'eyebrow' => 'Why Project SPARK?', 'title' => 'Because Every Informed Choice Matters.', 'subtitle' => 'Five principles keep the work practical, respectful and trustworthy.', 'body' => 'We believe that access to responsible health knowledge can make a meaningful difference in people’s lives.', 'items' => [
                ['label' => 'Education with Purpose', 'text' => 'Making health knowledge understandable, relevant and accessible.'], ['label' => 'Prevention Through Awareness', 'text' => 'Encouraging informed approaches to health and well-being.'], ['label' => 'Inclusivity and Accessibility', 'text' => 'Reaching children, families and communities through appropriate educational initiatives.'], ['label' => 'Collaboration for Greater Impact', 'text' => 'Bringing educational and healthcare perspectives together to strengthen public awareness.'], ['label' => 'Responsibility and Trust', 'text' => 'Promoting credible information and encouraging consultation with qualified healthcare professionals.'],
            ], 'sort_order' => 70],
            ['slug' => 'collaborate', 'eyebrow' => 'Be a Part of the SPARK', 'title' => 'Together, Let Us Build a Healthier Tomorrow.', 'subtitle' => 'Bring knowledge to classrooms, awareness to communities and healthier choices into everyday life.', 'body' => 'Creating a healthier society is a shared responsibility. Whether you represent a school, work in healthcare, lead a community organisation or believe in the power of education, your participation can help extend the reach of responsible health awareness.\n\nProject SPARK welcomes opportunities for meaningful collaboration with educational institutions, healthcare professionals, community organisations and other stakeholders who share its vision.', 'sort_order' => 80],
            ['slug' => 'vision', 'eyebrow' => 'Our Vision', 'title' => 'A Spark Today. A Healthier Generation Tomorrow.', 'subtitle' => 'Awareness is a shared responsibility—and change can begin with a single spark.', 'body' => 'We envision a future where every child has the opportunity to learn about health, every family can access understandable and reliable health information, and every community is empowered to make informed choices about well-being.\n\nThrough the collaborative efforts of CE Foundation and CMRI, Project SPARK seeks to nurture a culture in which awareness becomes a shared responsibility and health education becomes a foundation for a healthier society.\n\nBecause meaningful change does not always begin with something monumental. Sometimes, it begins with a single spark.', 'sort_order' => 90],
        ];
        foreach ($sections as $section) ContentSection::updateOrCreate(['slug' => $section['slug']], $section + ['is_visible' => true]);

        $areas = [
            ['number' => '01', 'title' => 'School Health Awareness', 'description' => 'Introducing schoolchildren to essential health concepts and encouraging healthy habits from an early age.', 'icon' => 'school'], ['number' => '02', 'title' => 'Personal Hygiene and Sanitation', 'description' => 'Promoting everyday hygiene, handwashing, sanitation and practices that support individual and community health.', 'icon' => 'drop'], ['number' => '03', 'title' => 'Nutrition and Healthy Living', 'description' => 'Encouraging balanced nutrition, physical activity and informed lifestyle choices.', 'icon' => 'leaf'], ['number' => '04', 'title' => 'Adolescent Health Education', 'description' => 'Supporting age-appropriate awareness of physical changes, personal well-being and responsible health practices.', 'icon' => 'heart'], ['number' => '05', 'title' => 'Preventive Healthcare', 'description' => 'Increasing awareness of preventive health measures, appropriate screening, recommended vaccinations and timely medical consultation.', 'icon' => 'shield'], ['number' => '06', 'title' => 'Mental Health and Emotional Well-being', 'description' => 'Encouraging compassionate conversations about emotional health, stress and the importance of seeking appropriate professional support.', 'icon' => 'sun'], ['number' => '07', 'title' => 'Community Health Awareness', 'description' => 'Extending health education beyond schools through community outreach and public engagement.', 'icon' => 'people'], ['number' => '08', 'title' => 'Responsible Health Knowledge', 'description' => 'Promoting reliable health information, informed decision-making and awareness of the risks associated with health misinformation.', 'icon' => 'spark'],
        ];
        foreach ($areas as $index => $area) FocusArea::updateOrCreate(['number' => $area['number']], $area + ['sort_order' => $index, 'is_visible' => true]);

        Partner::updateOrCreate(['name' => 'CE Foundation'], ['tagline' => 'Education That Empowers. Awareness That Endures.', 'description' => 'CE Foundation is committed to advancing educational opportunities and creating meaningful learning experiences for children and communities. Under Project SPARK, this educational approach supports school-based learning, community engagement and structured educational activities.', 'sort_order' => 1, 'is_visible' => true]);
        Partner::updateOrCreate(['name' => 'CMRI'], ['tagline' => 'Healthcare Knowledge for a Healthier Community.', 'description' => 'CMRI brings a healthcare perspective to the collaborative initiative, supporting the broader objective of improving public understanding of health and well-being through preventive healthcare, healthy living and responsible decision-making.', 'sort_order' => 2, 'is_visible' => true]);

        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'aryanlife6@gmail.com')],
            ['name' => env('ADMIN_NAME', 'Project SPARK Admin'), 'password' => Hash::make(env('ADMIN_PASSWORD', 'Ccf@2026')), 'is_admin' => true, 'is_active' => true]
        );
    }
}
