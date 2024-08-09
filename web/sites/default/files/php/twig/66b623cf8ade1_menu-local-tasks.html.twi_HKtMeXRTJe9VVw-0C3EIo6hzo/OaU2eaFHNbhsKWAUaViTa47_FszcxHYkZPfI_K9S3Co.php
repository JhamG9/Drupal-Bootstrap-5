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

/* themes/contrib/bootstrap5/templates/navigation/menu-local-tasks.html.twig */
class __TwigTemplate_6366f164cb219c163f48fa6b35934be2 extends Template
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
        // line 14
        if (($context["primary"] ?? null)) {
            // line 15
            yield "  <h2 class=\"visually-hidden\">";
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Primary tabs"));
            yield "</h2>
  <nav class=\"tabs-wrapper tabs-primary is-collapsible\" aria-labelledby=\"primary-tabs-title\" data-drupal-nav-tabs>
    <ul class=\"nav nav-tabs flex-column flex-md-row primary clearfix\" data-drupal-nav-tabs-target>";
            // line 17
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["primary"] ?? null), 17, $this->source), "html", null, true);
            yield "</ul>
  </nav>
";
        }
        // line 20
        if (($context["secondary"] ?? null)) {
            // line 21
            yield "  <h2 id=\"secondary-tabs-title\" class=\"visually-hidden\">";
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Secondary tabs"));
            yield "</h2>
  <nav class=\"tabs-wrapper tabs-secondary\" aria-labelledby=\"secondary-tabs-title\" data-drupal-nav-tabs>
    <ul class=\"nav nav-tabs flex-column flex-md-row primary clearfix\" data-drupal-nav-tabs-target>";
            // line 23
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["secondary"] ?? null), 23, $this->source), "html", null, true);
            yield "</ul>
  </nav>
";
        }
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["primary", "secondary"]);        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "themes/contrib/bootstrap5/templates/navigation/menu-local-tasks.html.twig";
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
        return array (  62 => 23,  56 => 21,  54 => 20,  48 => 17,  42 => 15,  40 => 14,);
    }

    public function getSourceContext()
    {
        return new Source("", "themes/contrib/bootstrap5/templates/navigation/menu-local-tasks.html.twig", "/var/www/html/web/themes/contrib/bootstrap5/templates/navigation/menu-local-tasks.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("if" => 14);
        static $filters = array("t" => 15, "escape" => 17);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['if'],
                ['t', 'escape'],
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
