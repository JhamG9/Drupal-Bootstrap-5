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

/* modules/contrib/layout_paragraphs/templates/layout-paragraphs-builder-component-menu.html.twig */
class __TwigTemplate_b703dc9218759edc3726e4f0e6ba26e7 extends Template
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
        $context["all_types"] = Twig\Extension\CoreExtension::merge($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["types"] ?? null), "layout", [], "any", false, false, true, 1), 1, $this->source), $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["types"] ?? null), "content", [], "any", false, false, true, 1), 1, $this->source));
        // line 2
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["status_messages"] ?? null), 2, $this->source), "html", null, true);
        yield "
<div";
        // line 3
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["attributes"] ?? null), 3, $this->source), "html", null, true);
        yield ">
  <h4 class=\"visually-hidden\">";
        // line 4
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Add Item"));
        yield "</h4>
  ";
        // line 5
        if ((Twig\Extension\CoreExtension::length($this->env->getCharset(), ($context["all_types"] ?? null)) > 1)) {
            // line 6
            yield "  <div class=\"lpb-component-list__search\">
    <input class=\"lpb-component-list-search-input\" type=\"text\" placeholder=\"";
            // line 7
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Filter items..."));
            yield "\" />
  </div>
  <div class=\"lpb-component-list__group\">
    ";
            // line 10
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["types"] ?? null), "layout", [], "any", false, false, true, 10)) {
                // line 11
                yield "    <div class=\"lpb-component-list__group--layout\">
    ";
            }
            // line 13
            yield "    ";
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, ($context["types"] ?? null), "layout", [], "any", false, false, true, 13));
            foreach ($context['_seq'] as $context["_key"] => $context["type"]) {
                // line 14
                yield "      <div class=\"lpb-component-list__item type-";
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["type"], "id", [], "any", false, false, true, 14), 14, $this->source), "html", null, true);
                yield " is-layout\">
        <a";
                // line 15
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["type"], "link_attributes", [], "any", false, false, true, 15), "setAttribute", ["href", CoreExtension::getAttribute($this->env, $this->source, $context["type"], "url", [], "any", false, false, true, 15)], "method", false, false, true, 15), 15, $this->source), "html", null, true);
                yield ">";
                if (CoreExtension::getAttribute($this->env, $this->source, $context["type"], "image", [], "any", false, false, true, 15)) {
                    yield "<img src=\"";
                    yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["type"], "image", [], "any", false, false, true, 15), 15, $this->source), "html", null, true);
                    yield "\" alt =\"\" />";
                }
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["type"], "label", [], "any", false, false, true, 15), 15, $this->source), "html", null, true);
                yield "</a>
      </div>
    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['type'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 18
            yield "    ";
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["types"] ?? null), "layout", [], "any", false, false, true, 18)) {
                // line 19
                yield "    </div>
    ";
            }
            // line 21
            yield "    ";
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["types"] ?? null), "content", [], "any", false, false, true, 21)) {
                // line 22
                yield "    <div class=\"lpb-component-list__group--content\">
    ";
            }
            // line 24
            yield "    ";
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, ($context["types"] ?? null), "content", [], "any", false, false, true, 24));
            foreach ($context['_seq'] as $context["_key"] => $context["type"]) {
                // line 25
                yield "      <div class=\"lpb-component-list__item type-";
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["type"], "id", [], "any", false, false, true, 25), 25, $this->source), "html", null, true);
                yield "\">
        <a";
                // line 26
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["type"], "link_attributes", [], "any", false, false, true, 26), "setAttribute", ["href", CoreExtension::getAttribute($this->env, $this->source, $context["type"], "url", [], "any", false, false, true, 26)], "method", false, false, true, 26), 26, $this->source), "html", null, true);
                yield ">";
                if (CoreExtension::getAttribute($this->env, $this->source, $context["type"], "image", [], "any", false, false, true, 26)) {
                    yield "<img src=\"";
                    yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["type"], "image", [], "any", false, false, true, 26), 26, $this->source), "html", null, true);
                    yield "\" alt =\"\" />";
                }
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["type"], "label", [], "any", false, false, true, 26), 26, $this->source), "html", null, true);
                yield "</a>
      </div>
    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['type'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 29
            yield "    ";
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["types"] ?? null), "content", [], "any", false, false, true, 29)) {
                // line 30
                yield "    </div>
    ";
            }
            // line 32
            yield "  </div>
  ";
        } else {
            // line 34
            yield "  <div class=\"lpb-component-list__empty-message\">
  ";
            // line 35
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["empty_message"] ?? null), 35, $this->source), "html", null, true);
            yield "
  </div>
  ";
        }
        // line 38
        yield "</div>
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["types", "status_messages", "attributes", "empty_message"]);        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "modules/contrib/layout_paragraphs/templates/layout-paragraphs-builder-component-menu.html.twig";
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
        return array (  157 => 38,  151 => 35,  148 => 34,  144 => 32,  140 => 30,  137 => 29,  121 => 26,  116 => 25,  111 => 24,  107 => 22,  104 => 21,  100 => 19,  97 => 18,  81 => 15,  76 => 14,  71 => 13,  67 => 11,  65 => 10,  59 => 7,  56 => 6,  54 => 5,  50 => 4,  46 => 3,  42 => 2,  40 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "modules/contrib/layout_paragraphs/templates/layout-paragraphs-builder-component-menu.html.twig", "/var/www/html/web/modules/contrib/layout_paragraphs/templates/layout-paragraphs-builder-component-menu.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("set" => 1, "if" => 5, "for" => 13);
        static $filters = array("merge" => 1, "escape" => 2, "t" => 4, "length" => 5);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['set', 'if', 'for'],
                ['merge', 'escape', 't', 'length'],
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
