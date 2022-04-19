<?php

namespace App\Controller;

use function Symfony\Component\String\b;
use function Symfony\Component\String\s;
use function Symfony\Component\String\u;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\ByteString;
use Symfony\Component\String\CodePointString;
use Symfony\Component\String\UnicodeString;

class StringController extends AbstractController
{
    /**
     * @Route("/strings", name="string_operartions")
     */
    public function index(): Response
    {
        $text = (new UnicodeString('This is a déjà-vu situation.'))
            ->trimEnd('.')
            ->replace('déjà-vu', 'jamais-vu')
            ->append('!');
        // $text = 'This is a jamais-vu situation!'

        $answer = $text;
        $nl = "<br/>";

        $content = new UnicodeString('नमस्ते दुनिया');
        if ($content->ignoreCase()->startsWith('नमस्ते')) {
            $answer .= $nl . $content;
        }

        $foo = new ByteString('hello');
        $bar = new CodePointString('hello');
        // UnicodeString is the most commonly used class
        $baz = new UnicodeString('hello');

        $answer .= $nl . $foo . $nl . $bar . $nl . $baz;

        $contents = ByteString::wrap(['hello', 'world']); // $contents = ByteString[]
        $answer .= $nl;
        print_r($contents);
        echo "<br/><br/>";

        $contents = UnicodeString::wrap(['I', '❤️', 'Symfony']); // $contents = UnicodeString[]
        print_r($contents);
        echo "<br/><br/>";

        // use the unwrap method to make the inverse conversion
        $contents = UnicodeString::unwrap([
            new UnicodeString('hello'), new UnicodeString('world'),
        ]); // $contents = ['hello', 'world']
        print_r($contents);
        echo "<br/><br/>";

        // the b() function creates byte strings
        // both lines are equivalent
        $foo = new ByteString('hello');
        $answer .= $nl . $foo;

        $foo = b('hello');
        $answer .= $nl . $foo;

        // the u() function creates Unicode strings
        // both lines are equivalent
        $foo = new UnicodeString('hello');
        $answer .= $nl . $foo;

        $foo = u('hello');
        $answer .= $nl . $foo;

        // the s() function creates a byte string or Unicode string
        // depending on the given contents

        // creates a ByteString object
        $foo = s("\xfe\xff");
        $answer .= $nl . $foo;
        // creates a UnicodeString object
        $foo = s('अनुच्छेद');
        $answer .= $nl . $foo;

        // ByteString can create a random string of the given length
        $foo = ByteString::fromRandom(12);
        $answer .= $nl . $foo;
        
        // by default, random strings use A-Za-z0-9 characters; you can restrict
        // the characters to use with the second optional argument
        $foo = ByteString::fromRandom(6, 'AEIOU0123456789');
        $answer .= $nl . $foo;

        $foo = ByteString::fromRandom(10, 'qwertyuiop');
        $answer .= $nl . $foo;

        // CodePointString and UnicodeString can create a string from code points
        $foo = UnicodeString::fromCodePoints(0x928, 0x92E, 0x938, 0x94D, 0x924, 0x947);
        // equivalent to: $foo = new UnicodeString('नमस्ते');
        $answer .= $nl . $foo;
        
        return new Response($answer);
    }
}
