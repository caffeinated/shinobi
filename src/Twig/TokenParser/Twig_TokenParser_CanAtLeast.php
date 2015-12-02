<?php
namespace Caffeinated\Shinobi\Twig\TokenParser;

use Twig_Token;
use Twig_TokenParser;
use Twig_Error_Syntax;
use Twig_Node;
use Caffeinated\Shinobi\Twig\Node\Twig_Node_CanAtLeast;

class Twig_TokenParser_CanAtLeast extends Twig_TokenParser
{
    public function parse(Twig_Token $token)
    {
        $lineno = $token->getLine();
        $expr = $this->parser->getExpressionParser()->parseExpression();
        $stream = $this->parser->getStream();
        $stream->expect(Twig_Token::BLOCK_END_TYPE);
        $body = $this->parser->subparse(array($this, 'decideIfFork'));
        $tests = array($expr, $body);
        $else = null;

        $end = false;
        while (!$end) {
            switch ($stream->next()->getValue()) {
                case 'else':
                    $stream->expect(Twig_Token::BLOCK_END_TYPE);
                    $else = $this->parser->subparse(array($this, 'decideIfEnd'));
                    break;

                case 'elsecanatleast':
                    $expr = $this->parser->getExpressionParser()->parseExpression();
                    $stream->expect(Twig_Token::BLOCK_END_TYPE);
                    $body = $this->parser->subparse(array($this, 'decideIfFork'));
                    $tests[] = $expr;
                    $tests[] = $body;
                    break;

                case 'endcanatleast':
                    $end = true;
                    break;

                default:
                    throw new Twig_Error_Syntax(sprintf('Unexpected end of template. Twig was looking for the following tags "else", "elsecanatleast", or "endcanatleast" to close the "canatleast" block started at line %d).', $lineno), $stream->getCurrent()->getLine(), $stream->getFilename());
            }
        }

        $stream->expect(Twig_Token::BLOCK_END_TYPE);

        return new Twig_Node_CanAtLeast(new Twig_Node($tests), $else, $lineno, $this->getTag());
    }

    public function decideIfFork(Twig_Token $token)
    {
        return $token->test(array('elsecanatleast', 'else', 'endcanatleast'));
    }

    public function decideIfEnd(Twig_Token $token)
    {
        return $token->test(array('endcanatleast'));
    }

    public function getTag()
    {
        return 'canatleast';
    }
}
