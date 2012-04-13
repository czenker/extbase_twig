<?php

class Tx_ExtbaseTwig_Twig_TokenParser_Include extends Twig_TokenParser_Include
{
    /**
     * Parses a token and returns a node.
     *
     * @param Twig_Token $token A Twig_Token instance
     *
     * @return Twig_NodeInterface A Twig_NodeInterface instance
     */
    public function parse(Twig_Token $token)
    {
        $expr = $this->parser->getExpressionParser()->parseExpression();

        $ignoreMissing = false;
        if ($this->parser->getStream()->test(Twig_Token::NAME_TYPE, 'ignore')) {
            $this->parser->getStream()->next();
            $this->parser->getStream()->expect(Twig_Token::NAME_TYPE, 'missing');

            $ignoreMissing = true;
        }

        $variables = null;
        if ($this->parser->getStream()->test(Twig_Token::NAME_TYPE, 'with')) {
            $this->parser->getStream()->next();

            $variables = $this->parser->getExpressionParser()->parseExpression();
        }

        $only = false;
        if ($this->parser->getStream()->test(Twig_Token::NAME_TYPE, 'only')) {
            $this->parser->getStream()->next();

            $only = true;
        }

        $this->parser->getStream()->expect(Twig_Token::BLOCK_END_TYPE);

        return new Tx_ExtbaseTwig_Twig_Node_Include($expr, $variables, $only, $ignoreMissing, $token->getLine(), $this->getTag());
    }
}
