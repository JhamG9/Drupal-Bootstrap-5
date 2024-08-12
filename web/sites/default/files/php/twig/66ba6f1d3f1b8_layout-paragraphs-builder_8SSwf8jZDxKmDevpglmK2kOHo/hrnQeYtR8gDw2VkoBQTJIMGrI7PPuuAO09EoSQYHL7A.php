<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* modules/contrib/layout_paragraphs/templates/layout-paragraphs-builder.html.twig */
class __TwigTemplate_5be776ccc11195249afb57a9d12d5c7e extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension(SandboxExtension::class);
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        yield "<div";
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["attributes"] ?? null), 1, $this->source), "html", null, true);
        yield ">
  ";
        // line 2
        if (($context["is_empty"] ?? null)) {
            // line 3
            yield "    <div class=\"lpb-empty-container__wrapper\">
      <div class=\"lpb-empty-container\">
        <div class=\"lpb-empty-message\">";
            // line 5
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["empty_message"] ?? null), 5, $this->source), "html", null, true);
            yield "</div>
        ";
            // line 6
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["insert_button"] ?? null), 6, $this->source), "html", null, true);
            yield "
      </div>
    </div>
  ";
        } else {
            // line 10
            yield "    ";
            if (($context["translation_warning"] ?? null)) {
                // line 11
                yield "    <div class=\"messages messages--status\">
      ";
                // line 12
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["translation_warning"] ?? null), 12, $this->source), "html", null, true);
                yield "
    </div>
    ";
            }
            // line 15
            yield "    <div class=\"js-lpb-component-list\">
      ";
            // line 16
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["root_components"] ?? null), 16, $this->source), "html", null, true);
            yield "
    </div>
  ";
        }
        // line 19
        yield "</div>";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["attributes", "is_empty", "empty_message", "insert_button", "translation_warning", "root_components"]);        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "modules/contrib/layout_paragraphs/templates/layout-paragraphs-builder.html.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable()
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo()
    {
        return array (  83 => 19,  77 => 16,  74 => 15,  68 => 12,  65 => 11,  62 => 10,  55 => 6,  51 => 5,  47 => 3,  45 => 2,  40 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "modules/contrib/layout_paragraphs/templates/layout-paragraphs-builder.html.twig", "/var/www/html/web/modules/contrib/layout_paragraphs/templates/layout-paragraphs-builder.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("if" => 2);
        static $filters = array("escape" => 1);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['if'],
                ['escape'],
                [],
                $this->source
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->source);

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }
}
