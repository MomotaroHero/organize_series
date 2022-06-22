<?php namespace Core;

use Codeception\Util\Stub;
use PublishPress\EDD_License\Core\License;
use WpunitTester;

class LicenseCest
{
    const DUMMY_PLUGIN_VERSION = '0.1.0';

    public function testValidate_license_keyReturnsInvalidForInvalidLicenseKey(WpunitTester $I)
    {
        $itemId = '234';

        $container = $I->getEddContainer(self::DUMMY_PLUGIN_VERSION, $itemId, '', '');

        $licenseHandler = new License($container);

        $result = $licenseHandler->validate_license_key('000000000000000', $itemId);

        $I->assertIsString($result);
        $I->assertEquals('invalid', $result);
    }

    public function testValidate_license_keyReturnsValidForValidLicenseKey(WpunitTester $I)
    {
        $validItemId     = $_ENV['PUBLISHPRESS_VALID_ITEM_ID'];
        $validLicenseKey = $_ENV['PUBLISHPRESS_VALID_LICENSE_KEY'];

        $container = $I->getEddContainer(self::DUMMY_PLUGIN_VERSION, $validItemId, '', '');

        $licenseHandler = new License($container);

        $result = $licenseHandler->validate_license_key($validLicenseKey, $validItemId);

        $I->assertIsString($result);
        $I->assertEquals('valid', $result);
    }

    public function testValidate_license_keyDoesntThrowsExceptionForNotResolvableApiUrl(WpunitTester $I)
    {
        $validItemId         = $_ENV['PUBLISHPRESS_VALID_ITEM_ID'];
        $validLicenseKey     = $_ENV['PUBLISHPRESS_VALID_LICENSE_KEY'];
        $notResolvableApiUrl = 'http://invalid.site.com';

        $container = $I->getEddContainer(self::DUMMY_PLUGIN_VERSION, $validItemId, '', '', $notResolvableApiUrl);

        $licenseHandler = new License($container);

        $result = $licenseHandler->validate_license_key($validLicenseKey, $validItemId);

        $I->assertIsString($result);
        $I->assertStringContainsString('Sorry, an error occurred', $result);
    }

    public function testValidate_license_keyHandlingCodeDifferentThan200ForAValidKey(WpunitTester $I)
    {
        $validItemId         = $_ENV['PUBLISHPRESS_VALID_ITEM_ID'];
        $validLicenseKey     = $_ENV['PUBLISHPRESS_VALID_LICENSE_KEY'];
        $notResolvableApiUrl = 'http://invalid.site.com';

        $container = $I->getEddContainer(self::DUMMY_PLUGIN_VERSION, $validItemId, '', '', $notResolvableApiUrl);

        $licenseHandlerMock = Stub::construct(
            '\\PublishPress\\EDD_License\\Core\\License',
            [
                $container,
            ],
            [
                'makeRequest' => [
                    'body'     => '',
                    'response' => ['code' => 201],
                ]
            ]
        );

        $result = $licenseHandlerMock->validate_license_key($validLicenseKey, $validItemId);

        $I->assertIsString($result);
        $I->assertStringContainsString('Sorry, an error occurred', $result);
    }
}
