<?php

class Tx_ExtbaseTwig_Twig_Node_Import extends Twig_Node_Import {

    /**
     * Compiles the node to PHP.
     *
     * @param Twig_Compiler A Twig_Compiler instance
     */
    public function compile(Twig_Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write('')
            ->subcompile($this->getNode('var'))
            ->raw(' = ')
        ;

        if ($this->getNode('expr') instanceof Twig_Node_Expression_Name && '_self' === $this->getNode('expr')->getAttribute('name')) {
            $compiler->raw("\$this");
        } else {
            $compiler
                ->raw('$this->env->loadTemplate(')
                ->subcompile($this->getNode('expr'))
// changes start here
// <<
//                ->raw(")")
// >>
                ->raw(", Tx_ExtbaseTwig_Twig_Environment::LOADER_PARTIAL)");
// changes end here
            ;
        }

        $compiler->raw(";\n");
    }
}