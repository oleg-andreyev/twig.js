/**
 * @fileoverview Compiled template for file
 *
 * Tests/Fixture/templates/sameas.twig
 *
 * @suppress {checkTypes|fileoverviewTags}
 */

goog.require('twig');
goog.provide('twig.templates');
goog.require('twig.filter');

/**
 * @constructor
 * @param {twig.Environment} env
 * @extends {twig.Template}
 */
twig.templates['sameas.twig'] = function(env) {
    twig.Template.call(this, env);
};
twig.inherits(twig.templates['sameas.twig'], twig.Template);

/**
 * @inheritDoc
 */
twig.templates['sameas.twig'].prototype.getParent_ = function(context) {
    return false;
};

/**
 * @inheritDoc
 */
twig.templates['sameas.twig'].prototype.render_ = function(sb, context, blocks) {
    blocks = typeof(blocks) == "undefined" ? {} : blocks;
    /* line 1 */
    sb.append("This is a simple template.<br \/><br \/>\n\n");
    /* line 3 */
    if ((!(("name" in context ? context["name"] : null) === false))) {
        /* line 4 */
        sb.append("Hello not false!\n");
    }
};

/**
 * @inheritDoc
 */
twig.templates['sameas.twig'].prototype.getTemplateName = function() {
    return "sameas";
};

/**
 * Returns whether this template can be used as trait.
 *
 * @return {boolean}
 */
twig.templates['sameas.twig'].prototype.isTraitable = function() {
    return false;
};
