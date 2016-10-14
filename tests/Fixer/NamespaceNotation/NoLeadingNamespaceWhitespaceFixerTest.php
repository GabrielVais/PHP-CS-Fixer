<?php

/*
 * This file is part of PHP CS Fixer.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpCsFixer\Tests\Fixer\NamespaceNotation;

use PhpCsFixer\Test\AbstractFixerTestCase;
use PhpCsFixer\WhitespacesFixerConfig;

/**
 * @author Bram Gotink <bram@gotink.me>
 * @author Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * @internal
 */
final class NoLeadingNamespaceWhitespaceFixerTest extends AbstractFixerTestCase
{
    /**
     * @dataProvider provideExamples
     */
    public function testFix($expected, $input = null)
    {
        $this->doTest($expected, $input);
    }

    public function provideExamples()
    {
        $manySpaces = array();
        for ($i = 1; $i <= 100; ++$i) {
            $manySpaces[] = 'namespace Test'.$i.';';
        }

        return array(
            // with newline
            array("<?php\nnamespace Test1;"),
            array("<?php\n\nnamespace Test2;"),
            array("<?php\nnamespace Test3;", "<?php\n namespace Test3;"),
            // without newline
            array('<?php namespace Test4;'),
            array('<?php namespace Test5;', '<?php  namespace Test5;'),
            // multiple namespaces with newline
            array(
                '<?php
namespace Test6a;
namespace Test6b;',
            ),
            array(
                '<?php
namespace Test7a;
/* abc */
namespace Test7b;',
                '<?php
namespace Test7a;
/* abc */namespace Test7b;',
            ),
            array(
                '<?php
namespace Test8a;
namespace Test8b;',
                '<?php
 namespace Test8a;
    namespace Test8b;',
            ),
            array(
                '<?php
namespace Test9a;
class Test {}
namespace Test9b;',
                '<?php
 namespace Test9a;
class Test {}
   namespace Test9b;',
            ),
            array(
                '<?php
namespace Test10a;
use Exception;
namespace Test10b;',
                '<?php
 namespace Test10a;
use Exception;
   namespace Test10b;',
            ),
            // multiple namespaces without newline
            array('<?php namespace Test11a; namespace Test11b;'),
            array('<?php namespace Test12a; namespace Test12b;', '<?php    namespace Test12a;  namespace Test12b;'),
            array('<?php namespace Test13a; namespace Test13b;', '<?php namespace Test13a;  namespace Test13b;'),
            // namespaces without spaces in between
            array(
                '<?php
namespace Test14a{}
namespace Test14b{}',
                '<?php
     namespace Test14a{}namespace Test14b{}',
            ),
            array(
                '<?php
namespace Test15a;
namespace Test15b;',
                '<?php
namespace Test15a;namespace Test15b;',
            ),
            array(
                '<?php
'.implode("\n", $manySpaces),
                '<?php
'.implode('', $manySpaces),
            ),
        );
    }

    /**
     * @dataProvider provideMessyWhitespacesCases
     */
    public function testMessyWhitespaces($expected, $input = null)
    {
        $fixer = clone $this->getFixer();
        $fixer->setWhitespacesConfig(new WhitespacesFixerConfig("\t", "\r\n"));

        $this->doTest($expected, $input, null, $fixer);
    }

    public function provideMessyWhitespacesCases()
    {
        return array(
            array(
                "<?php\r\nnamespace TestW1a{}\r\nnamespace TestW1b{}",
                "<?php\r\n     namespace TestW1a{}\r\nnamespace TestW1b{}",
            ),
            array(
                "<?php\r\nnamespace Test14a{}\r\nnamespace Test14b{}",
                "<?php\r\n     namespace Test14a{}namespace Test14b{}",
            ),
        );
    }
}
