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

/* @help_topics/layout_builder.overview.html.twig */
class __TwigTemplate_88cb3d23cd04541a6bb489139b98284b extends Template
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
        // line 9
        $context["content_types_text"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            yield t("Content types", array());
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        // line 10
        $context["content_types_link"] = $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\help\HelpTwigExtension']->getRouteLink($this->sandbox->ensureToStringAllowed(($context["content_types_text"] ?? null), 10, $this->source), "entity.node_type.collection"));
        // line 11
        $context["content_structure_topic"] = $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\help\HelpTwigExtension']->getTopicLink("core.content_structure"));
        // line 12
        $context["block_overview_topic"] = $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\help\HelpTwigExtension']->getTopicLink("block.overview"));
        // line 13
        yield "<h2>";
        yield t("Goal", array());
        yield "</h2>
<p>";
        // line 14
        yield t("Configure an entity sub-type to have its fields displayed using a layout (see @content_structure_topic for more on entities and fields).", array("@content_structure_topic" => ($context["content_structure_topic"] ?? null), ));
        yield "</p>
<h2>";
        // line 15
        yield t("What are the parts of a layout?", array());
        yield "</h2>
<p>";
        // line 16
        yield t("A layout consists of one or more <em>sections</em>. Each section can have from one to four <em>columns</em>. You can place blocks, including special blocks for the fields on the entity sub-type, in each column of each section (see @block_overview_topic for more on blocks).", array("@block_overview_topic" => ($context["block_overview_topic"] ?? null), ));
        yield "</p>
<h2>";
        // line 17
        yield t("Steps", array());
        yield "</h2>
<ol>
  <li>";
        // line 19
        yield t("Navigate to the page for managing the entity type you want to add the field to. For example, to add a field to a content type, in the <em>Manage</em> administrative menu, navigate to <em>Structure</em> &gt; @content_types_link.", array("@content_types_link" => ($context["content_types_link"] ?? null), ));
        yield "</li>
  <li>";
        // line 20
        yield t("Find the particular sub-type that you want to create a layout for, and click <em>Manage display</em> in the <em>Operations</em> list.", array());
        yield "</li>
  <li>";
        // line 21
        yield t("Under <em>Layout options</em>, check <em>Use Layout Builder</em>. You can also check the box below to allow each entity item to have its layout individually customized (if it is left unchecked, the site will use the same layout for all items of this entity sub-type).", array());
        yield "</li>
  <li>";
        // line 22
        yield t("Click <em>Save</em>. You will be returned to the <em>Manage display</em> page, but you will no longer see the table of fields of the classic display manager.", array());
        yield "</li>
  <li>";
        // line 23
        yield t("Click <em>Manage layout</em> to enter layout management view. A default layout will be set up for you, with a single one-column section containing the fields on your entity sub-type.", array());
        yield "</li>
  <li>";
        // line 24
        yield t("To remove the default section and start from an empty layout, find and click the <em>Remove</em> button for the default section, which looks like an X. Confirm by clicking <em>Remove</em> in the pop-up dialog.", array());
        yield "</li>
  <li>";
        // line 25
        yield t("Add new sections, each with one to four columns, to your layout. For instance, you might want a one-column section at the top, a two-column section in the middle, and then a one-column section at the bottom. To add a section, click <em>Add section</em> and click the desired number of columns. For multi-column sections, set the column width percentages and click <em>Add section</em> in the pop-up dialog.", array());
        yield "</li>
  <li>";
        // line 26
        yield t("In each section, click <em>Add block</em> to add a block. You will see a list of the blocks available on your site, plus a section called <em>Content fields</em> with a block for each field on your content item. Each block can be configured, if desired, with a <em>Title</em>, and for content field blocks, you can also configure the field formatter. Continue to add blocks to your sections until all the desired blocks and fields are displayed.", array());
        yield "</li>
  <li>";
        // line 27
        yield t("Verify your layout. You can check <em>Show content preview</em> to show a preview of what your layout will look like, or uncheck it to see the names of the fields and blocks in each section.", array());
        yield "</li>
  <li>";
        // line 28
        yield t("If needed, reorder the blocks by dragging them to new locations. If you hover over a block, a contextual menu will appear that will let you change the configuration of the block, remove the block, or <em>Move</em> blocks within the section using a more compact interface.", array());
        yield "</li>
  <li>";
        // line 29
        yield t("When you are satisfied with your layout, click <em>Save layout</em>.", array());
        yield "</li>
</ol>

<h2>";
        // line 32
        yield t("Additional resources", array());
        yield "</h2>
<ul>
  <li><a href=\"https://www.drupal.org/docs/8/core/modules/layout-builder/creating-layout-defaults\">";
        // line 34
        yield t("Creating layout defaults", array());
        yield "</a></li>
  <li><a href=\"https://www.drupal.org/docs/8/core/modules/layout-builder/building-layouts-using-the-layout-builder-ui\">";
        // line 35
        yield t("Building Layouts Using the Layout Builder UI", array());
        yield "</a></li>
</ul>";
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "@help_topics/layout_builder.overview.html.twig";
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
        return array (  128 => 35,  124 => 34,  119 => 32,  113 => 29,  109 => 28,  105 => 27,  101 => 26,  97 => 25,  93 => 24,  89 => 23,  85 => 22,  81 => 21,  77 => 20,  73 => 19,  68 => 17,  64 => 16,  60 => 15,  56 => 14,  51 => 13,  49 => 12,  47 => 11,  45 => 10,  40 => 9,);
    }

    public function getSourceContext()
    {
        return new Source("", "@help_topics/layout_builder.overview.html.twig", "/var/www/html/web/core/modules/layout_builder/help_topics/layout_builder.overview.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("set" => 9, "trans" => 9);
        static $filters = array("escape" => 14);
        static $functions = array("render_var" => 10, "help_route_link" => 10, "help_topic_link" => 11);

        try {
            $this->sandbox->checkSecurity(
                ['set', 'trans'],
                ['escape'],
                ['render_var', 'help_route_link', 'help_topic_link'],
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
