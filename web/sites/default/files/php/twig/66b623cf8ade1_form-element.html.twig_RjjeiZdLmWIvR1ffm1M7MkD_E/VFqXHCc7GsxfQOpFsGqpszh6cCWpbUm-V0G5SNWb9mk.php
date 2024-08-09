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

/* themes/contrib/bootstrap5/templates/form/form-element.html.twig */
class __TwigTemplate_e512380d6ff9a1d60544d3bd00b7430c extends Template
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
        // line 48
        $context["classes"] = ["js-form-item", "form-item", ("js-form-type-" . \Drupal\Component\Utility\Html::getClass($this->sandbox->ensureToStringAllowed(        // line 51
($context["type"] ?? null), 51, $this->source))), ("form-type-" . \Drupal\Component\Utility\Html::getClass($this->sandbox->ensureToStringAllowed(        // line 52
($context["type"] ?? null), 52, $this->source))), ("js-form-item-" . \Drupal\Component\Utility\Html::getClass($this->sandbox->ensureToStringAllowed(        // line 53
($context["name"] ?? null), 53, $this->source))), ("form-item-" . \Drupal\Component\Utility\Html::getClass($this->sandbox->ensureToStringAllowed(        // line 54
($context["name"] ?? null), 54, $this->source))), ((!CoreExtension::inFilter(        // line 55
($context["title_display"] ?? null), ["after", "before"])) ? ("form-no-label") : ("")), (((        // line 56
($context["disabled"] ?? null) == "disabled")) ? ("form-disabled") : ("")), ((        // line 57
($context["errors"] ?? null)) ? ("form-item--error") : (""))];
        // line 61
        $context["description_classes"] = ["description", (((        // line 63
($context["description_display"] ?? null) == "invisible")) ? ("visually-hidden") : (""))];
        // line 66
        yield "<div";
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [($context["classes"] ?? null)], "method", false, false, true, 66), 66, $this->source), "html", null, true);
        yield ">
  ";
        // line 67
        if (CoreExtension::inFilter(($context["label_display"] ?? null), ["before", "invisible"])) {
            // line 68
            yield "    ";
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["label"] ?? null), 68, $this->source), "html", null, true);
            yield "
  ";
        }
        // line 70
        yield "  ";
        if ( !Twig\Extension\CoreExtension::testEmpty(($context["prefix"] ?? null))) {
            // line 71
            yield "    <span class=\"field-prefix\">";
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["prefix"] ?? null), 71, $this->source), "html", null, true);
            yield "</span>
  ";
        }
        // line 73
        yield "  ";
        if (((($context["description_display"] ?? null) == "before") && CoreExtension::getAttribute($this->env, $this->source, ($context["description"] ?? null), "content", [], "any", false, false, true, 73))) {
            // line 74
            yield "    <div";
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["description"] ?? null), "attributes", [], "any", false, false, true, 74), 74, $this->source), "html", null, true);
            yield ">
      ";
            // line 75
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["description"] ?? null), "content", [], "any", false, false, true, 75), 75, $this->source), "html", null, true);
            yield "
    </div>
  ";
        }
        // line 78
        yield "  ";
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["children"] ?? null), 78, $this->source), "html", null, true);
        yield "
  ";
        // line 79
        if ( !Twig\Extension\CoreExtension::testEmpty(($context["suffix"] ?? null))) {
            // line 80
            yield "    <span class=\"field-suffix\">";
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["suffix"] ?? null), 80, $this->source), "html", null, true);
            yield "</span>
  ";
        }
        // line 82
        yield "  ";
        if ((($context["label_display"] ?? null) == "after")) {
            // line 83
            yield "    ";
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["label"] ?? null), 83, $this->source), "html", null, true);
            yield "
  ";
        }
        // line 85
        yield "  ";
        if (($context["errors"] ?? null)) {
            // line 86
            yield "    <div class=\"form-item--error-message\">
      <strong>";
            // line 87
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["errors"] ?? null), 87, $this->source), "html", null, true);
            yield "</strong>
    </div>
  ";
        }
        // line 90
        yield "  ";
        if ((CoreExtension::inFilter(($context["description_display"] ?? null), ["after", "invisible"]) && CoreExtension::getAttribute($this->env, $this->source, ($context["description"] ?? null), "content", [], "any", false, false, true, 90))) {
            // line 91
            yield "    <div";
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["description"] ?? null), "attributes", [], "any", false, false, true, 91), "addClass", [($context["description_classes"] ?? null)], "method", false, false, true, 91), 91, $this->source), "html", null, true);
            yield ">
      ";
            // line 92
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["description"] ?? null), "content", [], "any", false, false, true, 92), 92, $this->source), "html", null, true);
            yield "
    </div>
  ";
        }
        // line 95
        yield "</div>
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["type", "name", "title_display", "disabled", "errors", "description_display", "attributes", "label_display", "label", "prefix", "description", "children", "suffix"]);        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "themes/contrib/bootstrap5/templates/form/form-element.html.twig";
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
        return array (  136 => 95,  130 => 92,  125 => 91,  122 => 90,  116 => 87,  113 => 86,  110 => 85,  104 => 83,  101 => 82,  95 => 80,  93 => 79,  88 => 78,  82 => 75,  77 => 74,  74 => 73,  68 => 71,  65 => 70,  59 => 68,  57 => 67,  52 => 66,  50 => 63,  49 => 61,  47 => 57,  46 => 56,  45 => 55,  44 => 54,  43 => 53,  42 => 52,  41 => 51,  40 => 48,);
    }

    public function getSourceContext()
    {
        return new Source("", "themes/contrib/bootstrap5/templates/form/form-element.html.twig", "/var/www/html/web/themes/contrib/bootstrap5/templates/form/form-element.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("set" => 48, "if" => 67);
        static $filters = array("clean_class" => 51, "escape" => 66);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['set', 'if'],
                ['clean_class', 'escape'],
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
