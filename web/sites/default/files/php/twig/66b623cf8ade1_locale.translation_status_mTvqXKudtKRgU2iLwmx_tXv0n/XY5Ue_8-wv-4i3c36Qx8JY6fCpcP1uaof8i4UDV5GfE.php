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

/* @help_topics/locale.translation_status.html.twig */
class __TwigTemplate_5398403bdd640c352eb361864e41b94b extends Template
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
        // line 8
        $context["language_text"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            yield t("Languages", array());
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        // line 9
        $context["translation_updates_text"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            yield t("Available translation updates", array());
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        // line 10
        $context["language_link"] = $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\help\HelpTwigExtension']->getRouteLink($this->sandbox->ensureToStringAllowed(($context["language_text"] ?? null), 10, $this->source), "entity.configurable_language.collection"));
        // line 11
        $context["translation_updates_link"] = $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\help\HelpTwigExtension']->getRouteLink($this->sandbox->ensureToStringAllowed(($context["translation_updates_text"] ?? null), 11, $this->source), "locale.translate_status"));
        // line 12
        yield "<h2>";
        yield t("Goal", array());
        yield "</h2>
<p>";
        // line 13
        yield t("Check the current status of interface translations, and see if there are any updates available.", array());
        yield "</p>
<h2>";
        // line 14
        yield t("Steps", array());
        yield "</h2>
<ol>
  <li>";
        // line 16
        yield t("In the <em>Manage</em> administrative menu, navigate to <em>Configuration</em> &gt; <em>Region and language</em> &gt; @language_link.", array("@language_link" => ($context["language_link"] ?? null), ));
        yield "</li>
  <li>";
        // line 17
        yield t("Look at the <em>Interface translation</em> column in the language table, to find the percentage of user interface text that has been translated for each language.", array());
        yield "</li>
  <li>";
        // line 18
        yield t("In the <em>Manage</em> administrative menu, navigate to <em>Reports</em> &gt; <em>@translation_updates_link</em>. This report is only available if the core Update Status module is installed.", array("@translation_updates_link" => ($context["translation_updates_link"] ?? null), ));
        yield "</li>
  <li>";
        // line 19
        yield t("Optionally, click <em>Check manually</em> to update the report.", array());
        yield "</li>
  <li>";
        // line 20
        yield t("View the report to find out if any languages have translation updates that you can download.", array());
        yield "</li>
</ol>";
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "@help_topics/locale.translation_status.html.twig";
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
        return array (  84 => 20,  80 => 19,  76 => 18,  72 => 17,  68 => 16,  63 => 14,  59 => 13,  54 => 12,  52 => 11,  50 => 10,  45 => 9,  40 => 8,);
    }

    public function getSourceContext()
    {
        return new Source("", "@help_topics/locale.translation_status.html.twig", "/var/www/html/web/core/modules/locale/help_topics/locale.translation_status.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("set" => 8, "trans" => 8);
        static $filters = array("escape" => 16);
        static $functions = array("render_var" => 10, "help_route_link" => 10);

        try {
            $this->sandbox->checkSecurity(
                ['set', 'trans'],
                ['escape'],
                ['render_var', 'help_route_link'],
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
