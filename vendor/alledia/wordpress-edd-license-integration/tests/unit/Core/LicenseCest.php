<?php namespace Core;

use Codeception\Util\Stub;
use UnitTester;

class LicenseCest
{
    public function testValidate_license_keyReturnsErrorMessageStringWhenTheRequestReturnsAnError(UnitTester $I)
    {
        $expectedMessage = 'Sorry, an error occurred';

        $licenseHandlerMock = Stub::makeEmptyExcept(
            '\\PublishPress\\EDD_License\\Core\\License',
            'validate_license_key',
            [
                'eddApiUrl'   => 'http://invalid.url',
                'getHomeUrl'  => 'http://localhost',
                'makeRequest' => new \WP_Error(1, 'Invalid Request'),
                'isWpError'   => true,
                'messages'    => [
                    'error-exception' => $expectedMessage,
                ]
            ]
        );

        $result = $licenseHandlerMock->validate_license_key('000000000000', 0);

        $I->assertIsErrorMessageString($result);
    }

    public function testValidate_license_keyReturnsErrorMessageStringWhenTheRequestReturnsResponseCodeDifferentThan200(
        UnitTester $I
    ) {
        $expectedMessage = 'Sorry, an error occurred';

        $licenseHandlerMock = Stub::makeEmptyExcept(
            '\\PublishPress\\EDD_License\\Core\\License',
            'validate_license_key',
            [
                'eddApiUrl'       => 'http://invalid.url',
                'getHomeUrl'      => 'http://localhost',
                'getResponseCode' => 500,
                'messages'        => [
                    'error-exception' => $expectedMessage,
                ]
            ]
        );

        $result = $licenseHandlerMock->validate_license_key('000000000000', 0);

        $I->assertIsErrorMessageString($result);
    }

    public function testValidate_license_keyReturnsStringInvalidIfResposnseBodyIsEmpty(UnitTester $I)
    {
        $licenseHandlerMock = Stub::makeEmptyExcept(
            '\\PublishPress\\EDD_License\\Core\\License',
            'validate_license_key',
            [
                'makeRequest'                => true,
                'getHomeUrl'                 => 'http://localhost',
                'getResponseCode'            => 200,
                'isWpError'                  => false,
                'getResponseDecodedJsonBody' => '',
            ]
        );

        $result = $licenseHandlerMock->validate_license_key('000000000000', 0);

        $I->assertEquals('invalid', $result, 'For empty response body we should return the string "invalid"');
    }

    public function testValidate_license_keyReturnsStringValidIfResponseReturnsSuccess(UnitTester $I)
    {
        $licenseHandlerMock = Stub::makeEmptyExcept(
            '\\PublishPress\\EDD_License\\Core\\License',
            'validate_license_key',
            [
                'makeRequest'                => true,
                'getHomeUrl'                 => 'http://localhost',
                'getResponseCode'            => 200,
                'isWpError'                  => false,
                'getResponseDecodedJsonBody' => (object)['success' => true],
            ]
        );

        $result = $licenseHandlerMock->validate_license_key('000000000000', 0);

        $I->assertEquals('valid', $result, 'For a successful response we should return the string "valid"');
    }

    public function testValidate_license_keyReturnsStringInvalidIfResponseReturnsAsInvalidLicense(UnitTester $I)
    {
        $licenseHandlerMock = Stub::makeEmptyExcept(
            '\\PublishPress\\EDD_License\\Core\\License',
            'validate_license_key',
            [
                'makeRequest'                => true,
                'getHomeUrl'                 => 'http://localhost',
                'getResponseCode'            => 200,
                'isWpError'                  => false,
                'getResponseDecodedJsonBody' => (object)['license' => 'invalid'],
            ]
        );

        $result = $licenseHandlerMock->validate_license_key('000000000000', 0);

        $I->assertEquals('invalid', $result, 'For a invalid license key answer in the response we should return the string "invalid"');
    }

    public function testValidate_license_keyReturnsReceivedErrorMessageIfResponseReturnsAnErrorMessage(UnitTester $I)
    {
        $expectedErrorMessage = 'Any error message';

        $licenseHandlerMock = Stub::makeEmptyExcept(
            '\\PublishPress\\EDD_License\\Core\\License',
            'validate_license_key',
            [
                'makeRequest'                => true,
                'getHomeUrl'                 => 'http://localhost',
                'getResponseCode'            => 200,
                'isWpError'                  => false,
                'getResponseDecodedJsonBody' => (object)['error' => $expectedErrorMessage],
            ]
        );

        $result = $licenseHandlerMock->validate_license_key('000000000000', 0);

        $I->assertEquals($expectedErrorMessage, $result, 'For an error returned on the license key answer in the response we should return the received error message');
    }

    public function testValidate_license_keyReturnsStringInvalidIfResponseReturnsAnErrorFlagButWithoutMessage(UnitTester $I)
    {
        $licenseHandlerMock = Stub::makeEmptyExcept(
            '\\PublishPress\\EDD_License\\Core\\License',
            'validate_license_key',
            [
                'makeRequest'                => true,
                'getHomeUrl'                 => 'http://localhost',
                'getResponseCode'            => 200,
                'isWpError'                  => false,
                'getResponseDecodedJsonBody' => (object)['error' => ''],
            ]
        );

        $result = $licenseHandlerMock->validate_license_key('000000000000', 0);

        $I->assertEquals('invalid', $result, 'For an empty error message returned on the license key answer in the response we should return the string "invalid"');
    }
}
