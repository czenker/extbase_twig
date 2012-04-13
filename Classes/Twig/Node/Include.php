<?php

class Tx_ExtbaseTwig_Twig_Node_Include extends Twig_Node_Include
{

    public function compile(Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this);

        if ($this->getAttribute('ignore_missing')) {
            $compiler
                ->write("try {\n")
                ->indent()
            ;
        }

        if ($this->getNode('expr') instanceof Twig_Node_Expression_Constant) {
            $compiler
                ->write("\$this->env->loadTemplate(")
                ->subcompile($this->getNode('expr'))
// changes start here
                ->raw(", Tx_ExtbaseTwig_Twig_Environment::LOADER_PARTIAL")
// changes end here
                ->raw(")->display(")
            ;
        } else {
            $compiler
                ->write("\$template = \$this->env->resolveTemplate(")
                ->subcompile($this->getNode('expr'))
// changes start here
                ->raw(", Tx_ExtbaseTwig_Twig_Environment::LOADER_PARTIAL")
// changes end here
                ->raw(");\n")
                ->write('$template->display(')
            ;
        }

        if (false === $this->getAttribute('only')) {
            if (null === $this->getNode('variables')) {
                $compiler->raw('$context');
            } else {
                $compiler
                    ->raw('array_merge($context, ')
                    ->subcompile($this->getNode('variables'))
                    ->raw(')')
                ;
            }
        } else {
            if (null === $this->getNode('variables')) {
                $compiler->raw('array()');
            } else {
                $compiler->subcompile($this->getNode('variables'));
            }
        }

        $compiler->raw(");\n");

        if ($this->getAttribute('ignore_missing')) {
            $compiler
                ->outdent()
                ->write("} catch (Twig_Error_Loader \$e) {\n")
                ->indent()
                ->write("// ignore missing template\n")
                ->outdent()
                ->write("}\n\n")
            ;
        }
    }
}
