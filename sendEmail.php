<?php 
require 'vendor/autoload.php';
 
use SparkPost\SparkPost;
use GuzzleHttp\Client;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
 
$httpClient = new GuzzleAdapter(new Client());
$sparky = new SparkPost($httpClient, ['key'=>'2e7260f1d44f33c40bdad118ee3f748f09d0a507', 'async' => 
false]);

$resetLink = "1234546";

$transmissionData = [
    'content' => [
        'from' => [
            'name' => 'Slimage Support Team',
            'email' => 'support@slimage.io',
        ],
        'subject' => 'Verify your account',
        'html' => '<html>
        <body yahoo="fix" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
            
            <!-- ======= main section ======= -->
            <table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="f0f2f5">
                
                <tr>
                    <td align="center">
                        
                        <table border="0" align="center" width="510" cellpadding="0" cellspacing="0" class="container590">
                            
                            <tr>
                                <td>
                                    <table align="center" width="180" border="0" cellpadding="0" cellspacing="0" bgcolor="dee0e5">
                                        <tr><td height="1" style="font-size: 1px; line-height: 1px;">&nbsp;</td></tr>
                                    </table>
                                </td>
                            </tr>
                            
                            <tr><td height="40" style="font-size: 40px; line-height: 40px;">&nbsp;</td></tr>
                            
                            <tr>
                                <td align="center" style="color: #646b81; font-size: 32px; mso-line-height-rule: exactly; line-height: 30px;" class="title_color main-header">
                                    
                                    <!-- ======= section header ======= -->
                                    
                                    <div style="line-height: 30px;">
                                        
                                            Thank you for registering
                                        
                                    </div>
                                </td>
                            </tr>
                            
                            <tr><td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td></tr>
                            
                            <tr>
                                <td align="center">
                                    <table border="0" width="400" align="center" cellpadding="0" cellspacing="0" class="container580">				
                                        <tr>
                                            <td align="center" style="color: #94969d; font-size: 14px;  mso-line-height-rule: exactly; line-height: 22px;" class="resize-text text_color">
                                                <div style="line-height: 22px">
                                                    
                                                    <!-- ======= section text ======= -->
                                                    
                                                    Click the link below to activate your account and complete your registration.
                                                        
                                                </div>
                                            </td>	
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            
                            <tr><td height="45" style="font-size: 45px; line-height: 45px;">&nbsp;</td></tr>
                            
                            <tr>
                                <td align="center">
                                    
                                    <table border="0" align="center" width="250" cellpadding="0" cellspacing="0" bgcolor="D73964" style="border-radius: 30px;">
                                        
                                        <tr><td height="13" style="font-size: 13px; line-height: 13px;">&nbsp;</td></tr>
                                        
                                        <tr>
                                            
                                            <td align="center" style="color: #ffffff; font-size: 16px; font-family: Questrial, sans-serif;">
                                                <!-- ======= main section button ======= -->
                                                
                                                <div style="line-height: 24px;">
                                                    <a href="https://slimage.io/verifyAccount.php?verificationId={{verificationId}}" style="color: #ffffff; text-decoration: none;">Activate Account</a> 
                                                </div>
                                            </td>
                                            
                                        </tr>
                                        
                                        <tr><td height="13" style="font-size: 13px; line-height: 13px;">&nbsp;</td></tr>
                                    
                                    </table>
                                </td>
                            </tr>
                            
                            <tr><td height="80" style="font-size: 80px; line-height: 80px;">&nbsp;</td></tr>
                            

                                                        
                        </table>
                    </td>
                </tr>
                
                <tr><td height="50" style="font-size: 50px; line-height: 50px;">&nbsp;</td></tr>
                
            </table>
            <!-- ======= end header ======= -->
            
            
        </body></html>'
        ],
    'substitution_data' => ['verificationId' => '1234567'],
    'recipients' => [
        [
            'address' => [
                'name' => 'Justin Harr',
                'email' => 'dasveloper@gmail.com',
            ],
        ],
    ]
];

try {
    $response = $sparky->transmissions->post($transmissionData);
    echo $response->getStatusCode()."\n";
    print_r($response->getBody())."\n";
    return true;
}
catch (\Exception $e) {
    echo $e->getCode()."\n";
    echo $e->getMessage()."\n";
    return false;
}
?>