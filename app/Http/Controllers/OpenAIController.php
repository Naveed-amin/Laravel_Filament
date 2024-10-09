<?php

namespace App\Http\Controllers;

use EchoLabs\Prism\Prism;
use Illuminate\Http\Request;

class OpenAIController extends Controller
{
    public function generateText()
    {
        $prism = Prism::text()
            ->using('anthropic', 'claude-3-sonnet')
            ->withPrompt('Tell me a short story about a brave knight.');

        echo $prism()->text;
    }
}
