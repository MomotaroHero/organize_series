<?php

namespace BusinessRule;

use Codeception\Util\Shared\Asserts;

trait ErrorHandling
{
    use Asserts;

    public function assertIsErrorMessageString($message)
    {
        $expectedErrorMessage = 'Sorry, an error occurred';

        $this->assertThat(
            [
                'message' => $message,
            ],
            new Rule('isString(message)')
        );

        $this->assertThat(
            [
                'message'              => $message,
                'expectedErrorMessage' => $expectedErrorMessage,
            ],
            new Rule('stringContainsString(message, expectedErrorMessage)')
        );

        $this->assertStringContainsString(
            $expectedErrorMessage,
            $message,
            sprintf(
                'The error message should contains "%s"',
                $message
            )
        );
    }
}