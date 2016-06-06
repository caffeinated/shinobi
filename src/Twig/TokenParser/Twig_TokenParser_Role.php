<?php
namespace Caffeinated\Shinobi\Twig\TokenParser;

use Twig_Token;
use Twig_TokenParser;
use Twig_Error_Syntax;
use Twig_Node;
use Caffeinated\Shinobi\Twig\Node\Twig_Node_Role;

class Twig_TokenParser_Role extends Twig_TokenParser
{
    public function parse(Twig_Token $token)
    {
        $lineno = $token->getLine();
        $expr   = $this->parser->getExpressionParser()->parseExpression();
        $stream = $this->parser->getStream();
        $stream->expect(Twig_Token::BLOCK_END_TYPE);
        $body  = $this->parser->subparse(array($this, 'decideIfFork'));
        $tests = array($expr, $body);
        $else  = null;

        $end = false;
        while (!$end) {
            switch ($stream->next()->getValue()) {
                case 'else':
                    $stream->expect(Twig_Token::BLOCK_END_TYPE);
                    $else = $this->parser->subparse(array($this, 'decideIfEnd'));
                    break;

                case 'elserole':
                    $expr = $this->parser->getExpressionParser()->parseExpression();
                    $stream->expect(Twig_Token::BLOCK_END_TYPE);
                    $body    = $this->parser->subparse(array($this, 'decideIfFork'));
                    $tests[] = $expr;
                    $tests[] = $body;
                    break;

                case 'endrole':
                    $end = true;
                    break;

                default:
                    throw new Twig_Error_Syntax(sprintf('Unexpected end of template. Twig was looking for the following tags "else", "elserole", or "endrole" to close the "role" block started at line %d).', $lineno), $stream->getCurrent()->getLine(), $stream->getFilename());
            }
        }

        $stream->expect(Twig_Token::BLOCK_END_TYPE);

        return new Twig_Node_Role(new Twig_Node($tests), $else, $lineno, $this->getTag());
    }

    public function decideIfFork(Twig_Token $token)
    {
        return $token->test(array('elserole', 'else', 'endrole'));
    }

    public function decideIfEnd(Twig_Token $token)
    {
        return $token->test(array('endrole'));
    }

    public function getTag()
    {
        return 'role';
    }
}
