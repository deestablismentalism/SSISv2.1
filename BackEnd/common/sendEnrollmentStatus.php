<?php
require_once __DIR__ . '/../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

class SMSFailureException extends Exception {}
class SendEnrollmentStatus {
    private function cleanPhoneNumber($phoneNumber) {
        $cleaned = preg_replace('/[^0-9]/', '', $phoneNumber);
        if (substr($cleaned, 0, 2) === '09') {
            $cleaned = '63' . substr($cleaned, 1);
        }
        if (substr($cleaned, 0, 2) !== '63') {
            $cleaned = '63' . $cleaned;
        }
        return $cleaned;
    }

    public function sendEnrollmentStatus(array $data) {
        $gatewayUrl = $_ENV['SMS_GATEWAY_URL'];
        $username = $_ENV['SMS_GATEWAY_USERNAME'];
        $password = $_ENV['SMS_GATEWAY_PASSWORD'];

        $Cleaned_Contact_Number = $this->cleanPhoneNumber($data['Recipient_Contact_Number']);
        $Last_Name = $data['Last_Name'];
        $First_Name = $data['First_Name'];
        $Middle_Name = $data['Middle_Name'];
        $Enrollee_Name = $data['Enrollee_Name'];
        $Enrollment_Status = $data['Enrollment_Status'];
        
        switch ($Enrollment_Status) {
            case 'Enrolled':
                $data = [
                    "message" => "Hello $Last_Name, $First_Name $Middle_Name! Your child, $Enrollee_Name, is successfully enrolled! For further announcements, .",
                    "phoneNumbers" => ["+$Cleaned_Contact_Number"],
                    "simNumber" => 1
                ];
                break;
            case 'Follow-Up':
                $data = [
                    "message" => "Hello $Last_Name, $First_Name $Middle_Name! Your child, $Enrollee_Name's, enrollment status requires follow-up. 
                    Please log on to your account or contact the school for further details.",
                    "phoneNumbers" => ["+$Cleaned_Contact_Number"],
                    "simNumber" => 1
                ];
                break;
            case 'Denied':
                $data = [
                    "message" => "Hello $Last_Name, $First_Name $Middle_Name! We regret to inform you that your child, $Enrollee_Name's, enrollment has been denied. 
                    Please log on to your account or contact the school for further details.",
                    "phoneNumbers" => ["+$Cleaned_Contact_Number"],
                    "simNumber" => 1
                ];
                break;
            default:
                $data =[
                    "message" => "Hello $Last_Name, $First_Name $Middle_Name! There has been an update regarding your child, $Enrollee_Name's, enrollment status. Please log on to your account or contact the school for further details.",
                    "phoneNumbers" => ["+$Cleaned_Contact_Number"],
                    "simNumber" => 1
                ];
                break;
        } 

        $ch = curl_init($gatewayUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            throw new SMSFailureException("cURL Error: " . curl_error($ch));
        }

        curl_close($ch);

        $responseData = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new SMSFailureException("Invalid response from SMS gateway");
        }

        // Check if the message was accepted
        if (!isset($responseData['state']) || $responseData['state'] !== 'Pending') {
            throw new SMSFailureException("SMS gateway rejected the message");
        }
        return true;
    }
}
?>
