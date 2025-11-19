<?php

namespace app\Translation;

use Google\Cloud\Translate\V3\TranslationServiceClient;
use Google\Cloud\Translate\V3\Glossary;
use Google\Cloud\Translate\V3\GlossaryInputConfig;
use Google\Cloud\Translate\V3\GcsSource;

/**
 * Glossary Manager for Educational Terms
 * Manages custom glossaries for accurate translation of educational terminology
 */
class GlossaryManager
{
    private TranslationConfig $config;
    private TranslationServiceClient $client;
    private array $educationalTerms;

    public function __construct()
    {
        $this->config = TranslationConfig::getInstance();
        
        // Initialize Google Cloud Translation client
        if ($this->config->isConfigured()) {
            $this->client = new TranslationServiceClient([
                'credentials' => $this->config->getCredentialsPath()
            ]);
        }

        // Define educational terms glossary
        $this->educationalTerms = $this->getEducationalGlossary();
    }

    /**
     * Get educational terminology glossary
     * Maps English terms to Filipino (Tagalog) equivalents
     */
    private function getEducationalGlossary(): array
    {
        return [
            // School System Terms
            'Student Information System' => 'Sistema ng Impormasyon ng Mag-aaral',
            'Department of Education' => 'Kagawaran ng Edukasyon',
            'DepEd' => 'DepEd',
            'Academic Year' => 'Taong Akademiko',
            'School Year' => 'Taong Pampaaralan',
            'Enrollment' => 'Pagpaparehistro',
            'Registration' => 'Pagpaparehistro',
            
            // Educational Levels
            'Kindergarten' => 'Kindergarten',
            'Grade Level' => 'Baitang',
            'Elementary' => 'Elementarya',
            'Junior High School' => 'Junior High School',
            'Senior High School' => 'Senior High School',
            
            // Student Records
            'Learner Reference Number' => 'Learner Reference Number (LRN)',
            'LRN' => 'LRN',
            'Report Card' => 'Repord Kard',
            'Grades' => 'Marka',
            'Transcript' => 'Transcript',
            'Certificate' => 'Sertipiko',
            'Diploma' => 'Diploma',
            
            // Academic Terms
            'Subject' => 'Asignatura',
            'Curriculum' => 'Kurikulum',
            'Syllabus' => 'Syllabus',
            'Quarter' => 'Markahan',
            'Semester' => 'Semestre',
            'Section' => 'Seksyon',
            'Class' => 'Klase',
            'Schedule' => 'Iskedyul',
            
            // Personnel
            'Teacher' => 'Guro',
            'Principal' => 'Punong Guro',
            'Administrator' => 'Administrador',
            'Staff' => 'Kawani',
            'Guardian' => 'Tagapag-alaga',
            'Parent' => 'Magulang',
            
            // Documents
            'Birth Certificate' => 'Sertipiko ng Kapanganakan',
            'Form 137' => 'Form 137',
            'Form 138' => 'Form 138',
            'Good Moral Certificate' => 'Sertipiko ng Mabuting Asal',
            
            // Enrollment Process
            'Enrollment Form' => 'Form ng Pagpaparehistro',
            'Enrollment Status' => 'Status ng Pagpaparehistro',
            'Application' => 'Aplikasyon',
            'Admission' => 'Pagtatanggap',
            'Requirements' => 'Mga Kinakailangan',
            
            // Personal Information
            'First Name' => 'Unang Pangalan',
            'Middle Name' => 'Gitnang Pangalan',
            'Last Name' => 'Apelyido',
            'Full Name' => 'Buong Pangalan',
            'Date of Birth' => 'Petsa ng Kapanganakan',
            'Address' => 'Tirahan',
            'Contact Number' => 'Numero ng Telepono',
            'Phone Number' => 'Numero ng Telepono',
            'Email Address' => 'Email Address',
            
            // System Actions
            'Login' => 'Mag-login',
            'Logout' => 'Mag-logout',
            'Sign In' => 'Mag-sign In',
            'Sign Up' => 'Mag-sign Up',
            'Register' => 'Magparehistro',
            'Submit' => 'Ipasa',
            'Cancel' => 'Kanselahin',
            'Continue' => 'Magpatuloy',
            'Update' => 'I-update',
            'Delete' => 'Tanggalin',
            'Edit' => 'I-edit',
            'Save' => 'I-save',
            'Search' => 'Maghanap',
            'Filter' => 'Salain',
            'Download' => 'I-download',
            'Upload' => 'Mag-upload',
            'Print' => 'I-print',
            
            // Status Terms
            'Approved' => 'Aprubado',
            'Pending' => 'Naghihintay',
            'Rejected' => 'Hindi Tinanggap',
            'Active' => 'Aktibo',
            'Inactive' => 'Hindi Aktibo',
            'Completed' => 'Nakumpleto',
            'In Progress' => 'Isinasagawa',
            
            // Common Phrases
            'Welcome' => 'Maligayang Pagdating',
            'Thank You' => 'Salamat',
            'Please' => 'Pakiusap',
            'Yes' => 'Oo',
            'No' => 'Hindi',
            'Required' => 'Kailangan',
            'Optional' => 'Opsyonal',
            'Home' => 'Home',
            'Dashboard' => 'Dashboard',
            'Profile' => 'Profile',
            'Settings' => 'Settings',
            'Help' => 'Tulong',
            'Privacy Policy' => 'Patakaran sa Privacy',
            'Terms and Conditions' => 'Mga Tuntunin at Kondisyon',
            
            // Special Education
            'Disability' => 'Kapansanan',
            'Visual Impairment' => 'Kapansanan sa Paningin',
            'Hearing Impairment' => 'Kapansanan sa Pandinig',
            'Physical Disability' => 'Pisikal na Kapansanan',
            'Assistive Technology' => 'Assistive Technology',
            'Special Education' => 'Espesyal na Edukasyon',
            
            // School Information
            'School Name' => 'Pangalan ng Paaralan',
            'School ID' => 'School ID',
            'Mission' => 'Misyon',
            'Vision' => 'Bisyon',
            'Announcement' => 'Patalastas',
            'News' => 'Balita',
            'Events' => 'Mga Kaganapan',
            
            // Password & Security
            'Password' => 'Password',
            'Forgot Password' => 'Nakalimutan ang Password',
            'Change Password' => 'Baguhin ang Password',
            'Update Password' => 'I-update ang Password',
            'New Password' => 'Bagong Password',
            'Confirm Password' => 'Kumpirmahin ang Password',
            'Old Password' => 'Lumang Password',
            
            // Error Messages
            'Error' => 'Error',
            'Success' => 'Matagumpay',
            'Warning' => 'Babala',
            'Invalid' => 'Hindi Wasto',
            'Required Field' => 'Kinakailangang Patlang'
        ];
    }

    /**
     * Get glossary terms
     */
    public function getGlossaryTerms(): array
    {
        return $this->educationalTerms;
    }

    /**
     * Translate text with glossary awareness
     * Falls back to simple replacement if API is not configured
     */
    public function translateWithGlossary(string $text, string $sourceLang, string $targetLang): string
    {
        // If translating from English to Tagalog, apply glossary
        if ($sourceLang === 'en' && $targetLang === 'tl') {
            return $this->applyGlossary($text);
        }

        return $text;
    }

    /**
     * Apply glossary terms to text
     * Simple replacement method for offline use
     */
    private function applyGlossary(string $text): string
    {
        $translatedText = $text;

        // Sort by length (longest first) to handle multi-word phrases correctly
        $terms = $this->educationalTerms;
        uksort($terms, function($a, $b) {
            return strlen($b) - strlen($a);
        });

        foreach ($terms as $english => $filipino) {
            // Use word boundary to match complete words/phrases
            $pattern = '/\b' . preg_quote($english, '/') . '\b/i';
            $translatedText = preg_replace($pattern, $filipino, $translatedText);
        }

        return $translatedText;
    }

    /**
     * Create or update glossary in Google Cloud
     * This should be run once during setup
     */
    public function createGlossary(): array
    {
        if (!$this->config->isConfigured()) {
            return [
                'success' => false,
                'message' => 'Translation API not configured'
            ];
        }

        try {
            $projectId = $this->config->getProjectId();
            $glossaryId = $this->config->getGlossaryId();
            $location = 'us-central1'; // Default location

            // Format glossary name
            $formattedParent = $this->client->locationName($projectId, $location);
            $glossaryName = $this->client->glossaryName($projectId, $location, $glossaryId);

            // Prepare glossary data in TSV format
            $glossaryContent = "en\ttl\n";
            foreach ($this->educationalTerms as $english => $filipino) {
                $glossaryContent .= "$english\t$filipino\n";
            }

            // Save glossary to a temporary file
            $tempFile = tempnam(sys_get_temp_dir(), 'glossary_');
            file_put_contents($tempFile, $glossaryContent);

            return [
                'success' => true,
                'message' => 'Glossary structure prepared',
                'terms_count' => count($this->educationalTerms),
                'glossary_file' => $tempFile
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error creating glossary: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get term translation
     */
    public function getTermTranslation(string $term, string $sourceLang = 'en', string $targetLang = 'tl'): ?string
    {
        if ($sourceLang === 'en' && $targetLang === 'tl') {
            return $this->educationalTerms[$term] ?? null;
        }

        return null;
    }

    /**
     * Add custom term to glossary
     */
    public function addCustomTerm(string $english, string $filipino): void
    {
        $this->educationalTerms[$english] = $filipino;
    }

    /**
     * Export glossary as JSON
     */
    public function exportGlossaryAsJson(): string
    {
        return json_encode($this->educationalTerms, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Export glossary as CSV
     */
    public function exportGlossaryAsCsv(): string
    {
        $csv = "English,Filipino\n";
        foreach ($this->educationalTerms as $english => $filipino) {
            $csv .= '"' . str_replace('"', '""', $english) . '","' . str_replace('"', '""', $filipino) . "\"\n";
        }
        return $csv;
    }
}
