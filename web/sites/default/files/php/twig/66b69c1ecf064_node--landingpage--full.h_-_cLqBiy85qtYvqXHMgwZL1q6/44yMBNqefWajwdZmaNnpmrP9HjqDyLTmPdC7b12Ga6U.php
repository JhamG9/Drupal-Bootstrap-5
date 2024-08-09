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

/* themes/custom/qintess/templates/node--landingpage--full.html.twig */
class __TwigTemplate_7db8ad701f6cff5c7094c4209663ad3c extends Template
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
        yield "<div class=\"container p-5 bg-body mt-md-n6 position-relative rounded\">
\t<div class=\"row\">
\t\t<div class=\"col-md-4 text-center align-self-center\">
\t\t\t<div class=\"lc-block border-lg-end border-2 \">
\t\t\t\t<div editable=\"rich\">
\t\t\t\t\t<p class=\"display-4 text-secondary\">WHY?</p>
\t\t\t\t</div>
\t\t\t</div><!-- /lc-block -->
\t\t</div><!-- /col -->
\t\t<div class=\"col-md-8\">
\t\t\t<div class=\"lc-block \">
\t\t\t\t<div editable=\"rich\">
\t\t\t\t\t<p class=\"display-4\">";
        // line 13
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["label"] ?? null), 0, [], "any", false, false, true, 13), 13, $this->source), "html", null, true);
        yield "</p>
\t\t\t\t</div>
\t\t\t</div><!-- /lc-block -->
\t\t</div><!-- /col -->
\t</div>
\t<div class=\"row\">
\t\t<div class=\"col-md-12 p-md-5\">
            ";
        // line 20
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["content"] ?? null), "field_contenidos_landing", [], "any", false, false, true, 20), 20, $this->source), "html", null, true);
        yield "
\t\t</div><!-- /col -->
\t</div>
</div> ";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["label", "content"]);        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "themes/custom/qintess/templates/node--landingpage--full.html.twig";
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
        return array (  64 => 20,  54 => 13,  40 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "themes/custom/qintess/templates/node--landingpage--full.html.twig", "/var/www/html/web/themes/custom/qintess/templates/node--landingpage--full.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array();
        static $filters = array("escape" => 13);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                [],
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
