/**
 *
 * Creative Timeline Framework
 *
 */
;
(function($, window, document, undefined) {
    'use strict';

    /**
     * Define Constants
     */
    var CTWP = CTWP || {};
    CTWP.funcs = {};
    CTWP.vars = {
        onloaded: false,
        $body: $('body'),
        $window: $(window),
        $document: $(document),
        $form_warning: null,
        is_confirm: false,
        form_modified: false,
        code_themes: [],
        is_rtl: $('body').hasClass('rtl'),
    };

    /**
     * Helper Functions
     */
    CTWP.helper = {

        uid: function(prefix) {
            return (prefix || '') + Math.random().toString(36).substr(2, 9);
        },
        preg_quote: function(str) {
            return (str + '').replace(/(\[|\])/g, "\\$1");
        },
        name_nested_replace: function($selector, field_id) {
            var checks = [];
            var regex = new RegExp(CTWP.helper.preg_quote(field_id + '[\\d+]'), 'g');
            $selector.find(':radio').each(function() {
                if (this.checked || this.orginal_checked) {
                    this.orginal_checked = true;
                }
            });
            $selector.each(function(index) {
                $(this).find(':input').each(function() {
                    this.name = this.name.replace(regex, field_id + '[' + index + ']');
                    if (this.orginal_checked) {
                        this.checked = true;
                    }
                });
            });
        },
    
        debounce: function(callback, threshold, immediate) {
            var timeout;
            return function() {
                var context = this,
                    args = arguments;
                var later = function() {
                    timeout = null;
                    if (!immediate) {
                        callback.apply(context, args);
                    }
                };
                var callNow = (immediate && !timeout);
                clearTimeout(timeout);
                timeout = setTimeout(later, threshold);
                if (callNow) {
                    callback.apply(context, args);
                }
            };
        },

    };

    $.fn.ctwp_clone = function() {

        var base = $.fn.clone.apply(this, arguments),
            clone = this.find('select').add(this.filter('select')),
            cloned = base.find('select').add(base.filter('select'));

        for (var i = 0; i < clone.length; ++i) {
            for (var j = 0; j < clone[i].options.length; ++j) {

                if (clone[i].options[j].selected === true) {
                    cloned[i].options[j].selected = true;
                }

            }
        }

        this.find(':radio').each(function() {
            this.orginal_checked = this.checked;
        });

        return base;

    };

    /**
     * Expand All Options
     * @returns 
     */
    $.fn.ctwp_expand_all = function() {
        return this.each(function() {
            $(this).on('click', function(e) {

                e.preventDefault();
                $('.ctwp-wrapper').toggleClass('ctwp-show-all');
                $('.ctwp-section').ctwp_reload_script();
                $(this).find('.fa').toggleClass('fa-indent').toggleClass('fa-outdent');

            });
        });
    };

    /**
     * Options Navigation
     * @returns 
     */
    $.fn.ctwp_nav_options = function() {
        return this.each(function() {

            var $nav = $(this),
                $window = $(window),
                $wpwrap = $('#wpwrap'),
                $links = $nav.find('a'),
                $last;

            $window.on('hashchange ctwp.hashchange', function() {
                var hash = window.location.hash.replace('#tab=', '');
                var slug = hash ? hash : $links.first().attr('href').replace('#tab=', '');
                var $link = $('[data-tab-id="' + slug + '"]');

                if ($link.length) {
                    $link.closest('.ctwp-tab-item').addClass('ctwp-tab-expanded').siblings().removeClass('ctwp-tab-expanded');
                    if ($link.next().is('ul')) {
                        $link = $link.next().find('li').first().find('a');
                        slug = $link.data('tab-id');
                    }
                    $links.removeClass('ctwp-active');
                    $link.addClass('ctwp-active');
                    if ($last) {
                        $last.addClass('hidden');
                    }
                    var $section = $('[data-section-id="' + slug + '"]');
                    $section.removeClass('hidden');
                    $section.ctwp_reload_script();
                    $('.ctwp-section-id').val($section.index() + 1);
                    $last = $section;
                    if ($wpwrap.hasClass('wp-responsive-open')) {
                        $('html, body').animate({ scrollTop: ($section.offset().top - 50) }, 200);
                        $wpwrap.removeClass('wp-responsive-open');
                    }
                }
            }).trigger('ctwp.hashchange');
        });
    };

    /**
     * Metabox Tabs
     * @returns 
     */
    $.fn.ctwp_nav_metabox = function() {
        return this.each(function() {

            var $nav = $(this),
                $links = $nav.find('a'),
                $sections = $nav.parent().find('.ctwp-section'),
                $last;
            $links.each(function(index) {
                $(this).on('click', function(e) {
                    alert("hii");
                    e.preventDefault();
                    var $link = $(this);
                    $links.removeClass('ctwp-active');
                    $link.addClass('ctwp-active');
                    if ($last !== undefined) {
                        $last.addClass('hidden');
                    }
                    var $section = $sections.eq(index);
                    $section.removeClass('hidden');
                    $section.ctwp_reload_script();
                    $last = $section;
                });
            });
            $links.first().trigger('click');
        });
    };

    /**
     * Metabox Page Templates Listener
     * @returns 
     */
    $.fn.ctwp_page_templates = function() {
        if (this.length) {

            $(document).on('change', '.editor-page-attributes__template select, #page_template', function() {
                var maybe_value = $(this).val() || 'default';
                $('.ctwp-page-templates').removeClass('ctwp-metabox-show').addClass('ctwp-metabox-hide');
                $('.ctwp-page-' + maybe_value.toLowerCase().replace(/[^a-zA-Z0-9]+/g, '-')).removeClass('ctwp-metabox-hide').addClass('ctwp-metabox-show');
            });
        }
    };

    /**
     * Metabox Post Formats Listener
     * @returns 
     */
    $.fn.ctwp_post_formats = function() {
        if (this.length) {

            $(document).on('change', '.editor-post-format select, #formatdiv input[name="post_format"]', function() {
                var maybe_value = $(this).val() || 'default';
                maybe_value = (maybe_value === '0') ? 'default' : maybe_value;
                $('.ctwp-post-formats').removeClass('ctwp-metabox-show').addClass('ctwp-metabox-hide');
                $('.ctwp-post-format-' + maybe_value).removeClass('ctwp-metabox-hide').addClass('ctwp-metabox-show');
            });
        }
    };

    /**
     * Search
     * @returns 
     */
    $.fn.ctwp_search = function() {
        return this.each(function() {
            var $this = $(this),
                $input = $this.find('input');
            $input.on('change keyup', function() {
                var value = $(this).val(),
                    $wrapper = $('.ctwp-wrapper'),
                    $section = $wrapper.find('.ctwp-section'),
                    $fields = $section.find('> .ctwp-field:not(.ctwp-depend-on)'),
                    $titles = $fields.find('> .ctwp-title, .ctwp-search-tags');
                if (value.length > 3) {
                    $fields.addClass('ctwp-metabox-hide');
                    $wrapper.addClass('ctwp-search-all');
                    $titles.each(function() {
                        var $title = $(this);
                        if ($title.text().match(new RegExp('.*?' + value + '.*?', 'i'))) {
                            var $field = $title.closest('.ctwp-field');
                            $field.removeClass('ctwp-metabox-hide');
                            $field.parent().ctwp_reload_script();
                        }
                    });
                } else {
                    $fields.removeClass('ctwp-metabox-hide');
                    $wrapper.removeClass('ctwp-search-all');
                }
            });
        });
    };

    /**
     * Sticky Header
     * @returns 
     */
    $.fn.ctwp_sticky = function() {
        return this.each(function() {

            var $this = $(this),
                $window = $(window),
                $inner = $this.find('.ctwp-header-inner'),
                padding = parseInt($inner.css('padding-left')) + parseInt($inner.css('padding-right')),
                offset = 32,
                scrollTop = 0,
                lastTop = 0,
                ticking = false,
                stickyUpdate = function() {
                    var offsetTop = $this.offset().top,
                        stickyTop = Math.max(offset, offsetTop - scrollTop),
                        winWidth = $window.innerWidth();
                    if (stickyTop <= offset && winWidth > 782) {
                        $inner.css({ width: $this.outerWidth() - padding });
                        $this.css({ height: $this.outerHeight() }).addClass('ctwp-sticky');
                    } else {
                        $inner.removeAttr('style');
                        $this.removeAttr('style').removeClass('ctwp-sticky');
                    }
                },
                requestTick = function() {
                    if (!ticking) {
                        requestAnimationFrame(function() {
                            stickyUpdate();
                            ticking = false;
                        });
                    }
                    ticking = true;
                },
                onSticky = function() {
                    scrollTop = $window.scrollTop();
                    requestTick();
                };
            $window.on('scroll resize', onSticky);
            onSticky();
        });
    };

    /**
     * Dependency System
     * @returns 
     */
    $.fn.ctwp_dependency = function() {
        return this.each(function() {
            var $this = $(this),
                $fields = $this.children('[data-controller]');
            if ($fields.length) {
                var normal_ruleset = $.ctwp_deps.createRuleset(),
                    global_ruleset = $.ctwp_deps.createRuleset(),
                    normal_depends = [],
                    global_depends = [];
                $fields.each(function() {
                    var $field = $(this),
                        controllers = $field.data('controller').split('|'),
                        conditions = $field.data('condition').split('|'),
                        values = $field.data('value').toString().split('|'),
                        is_global = $field.data('depend-global') ? true : false,
                        ruleset = (is_global) ? global_ruleset : normal_ruleset;
                    $.each(controllers, function(index, depend_id) {
                        var value = values[index] || '',
                            condition = conditions[index] || conditions[0];
                        ruleset = ruleset.createRule('[data-depend-id="' + depend_id + '"]', condition, value);
                        ruleset.include($field);
                        if (is_global) {
                            global_depends.push(depend_id);
                        } else {
                            normal_depends.push(depend_id);
                        }
                    });
                });
                if (normal_depends.length) {
                    $.ctwp_deps.enable($this, normal_ruleset, normal_depends);
                }
                if (global_depends.length) {
                    $.ctwp_deps.enable(CTWP.vars.$body, global_ruleset, global_depends);
                }
            }
        });
    };

    /**
     * Field: accordion
    */
    $.fn.ctwp_field_accordion = function() {
        return this.each(function() {
            var $titles = $(this).find('.ctwp-accordion-title');
            $titles.on('click', function() {
                var $title = $(this),
                    $icon = $title.find('.ctwp-accordion-icon'),
                    $content = $title.next();
                if ($icon.hasClass('fa-angle-right')) {
                    $icon.removeClass('fa-angle-right').addClass('fa-angle-down');
                } else {
                    $icon.removeClass('fa-angle-down').addClass('fa-angle-right');
                }
                if (!$content.data('opened')) {
                    $content.ctwp_reload_script();
                    $content.data('opened', true);
                }
                $content.toggleClass('ctwp-accordion-open');
            });
        });
    };

    /**
     * Field: backup
     * @returns 
     */
    $.fn.ctwp_field_backup = function() {
        return this.each(function() {
            if (window.wp.customize === undefined) { return; }
            var base = this,
                $this = $(this),
                $body = $('body'),
                $import = $this.find('.ctwp-import'),
                $reset = $this.find('.ctwp-reset');
            base.notificationOverlay = function() {
                if (wp.customize.notifications && wp.customize.OverlayNotification) {
                    if (!wp.customize.state('saved').get()) {
                        wp.customize.state('changesetStatus').set('trash');
                        wp.customize.each(function(setting) { setting._dirty = false; });
                        wp.customize.state('saved').set(true);
                    }
                    wp.customize.notifications.add(new wp.customize.OverlayNotification('ctwp_field_backup_notification', {
                        type: 'default',
                        message: '&nbsp;',
                        loading: true
                    }));
                }
            };
            $reset.on('click', function(e) {
                e.preventDefault();
                if (CTWP.vars.is_confirm) {
                    base.notificationOverlay();
                    window.wp.ajax.post('ctwp-reset', {
                        unique: $reset.data('unique'),
                        nonce: $reset.data('nonce')
                    })
                    .done(function(response) {
                        window.location.reload(true);
                    })
                    .fail(function(response) {
                        alert(response.error);
                        wp.customize.notifications.remove('ctwp_field_backup_notification');
                    });
                }
            });

            $import.on('click', function(e) {
                e.preventDefault();
                if (CTWP.vars.is_confirm) {
                    base.notificationOverlay();
                    window.wp.ajax.post('ctwp-import', {
                        unique: $import.data('unique'),
                        nonce: $import.data('nonce'),
                        data: $this.find('.ctwp-import-data').val()
                    }).done(function(response) {
                        window.location.reload(true);
                    }).fail(function(response) {
                        alert(response.error);
                        wp.customize.notifications.remove('ctwp_field_backup_notification');
                    });
                }
            });
        });
    };

    /**
     * Field: background
     * @returns 
     */
    $.fn.ctwp_field_background = function() {
        return this.each(function() {
            $(this).find('.ctwp--background-image').ctwp_reload_script();
        });
    };

    /**
     * Field: code_editor
     * @returns 
     */
    $.fn.ctwp_field_code_editor = function() {
        return this.each(function() {
            if (typeof CodeMirror !== 'function') { return; }
            var $this = $(this),
                $textarea = $this.find('textarea'),
                $inited = $this.find('.CodeMirror'),
                data_editor = $textarea.data('editor');
            if ($inited.length) {
                $inited.remove();
            }
            var interval = setInterval(function() {
                if ($this.is(':visible')) {
                    var code_editor = CodeMirror.fromTextArea($textarea[0], data_editor);
                    if (data_editor.theme !== 'default' && CTWP.vars.code_themes.indexOf(data_editor.theme) === -1) {
                        var $cssLink = $('<link>');
                        $('#ctwp-codemirror-css').after($cssLink);
                        $cssLink.attr({
                            rel: 'stylesheet',
                            id: 'ctwp-codemirror-' + data_editor.theme + '-css',
                            href: data_editor.cdnURL + '/theme/' + data_editor.theme + '.min.css',
                            type: 'text/css',
                            media: 'all'
                        });
                        CTWP.vars.code_themes.push(data_editor.theme);
                    }
                    CodeMirror.modeURL = data_editor.cdnURL + '/mode/%N/%N.min.js';
                    CodeMirror.autoLoadMode(code_editor, data_editor.mode);
                    code_editor.on('change', function(editor, event) {
                        $textarea.val(code_editor.getValue()).trigger('change');
                    });
                    clearInterval(interval);
                }
            });
        });
    };

    /**
     * Field: date
     * @returns 
     */
    $.fn.ctwp_field_date = function() {
        return this.each(function() {
            var $this = $(this),
                $inputs = $this.find('input'),
                settings = $this.find('.ctwp-date-settings').data('settings'),
                wrapper = '<div class="ctwp-datepicker-wrapper"></div>',
                $datepicker;
            var defaults = {
                showAnim: '',
                beforeShow: function(input, inst) {
                    $(inst.dpDiv).addClass('ctwp-datepicker-wrapper');
                },
                onClose: function(input, inst) {
                    $(inst.dpDiv).removeClass('ctwp-datepicker-wrapper');
                },
            };
            settings = $.extend({}, settings, defaults);
            if ($inputs.length === 2) {
                settings = $.extend({}, settings, {
                    onSelect: function(selectedDate) {
                        var $this = $(this),
                            $from = $inputs.first(),
                            option = ($inputs.first().attr('id') === $(this).attr('id')) ? 'minDate' : 'maxDate',
                            date = $.datepicker.parseDate(settings.dateFormat, selectedDate);
                        $inputs.not(this).datepicker('option', option, date);
                    }
                });
            }
            $inputs.each(function() {
                var $input = $(this);
                if ($input.hasClass('hasDatepicker')) {
                    $input.removeAttr('id').removeClass('hasDatepicker');
                }
                $input.datepicker(settings);
            });
        });
    };

    /**
     * Field: fieldset
     * @returns 
     */
    $.fn.ctwp_field_fieldset = function() {
        return this.each(function() {
            $(this).find('.ctwp-fieldset-content').ctwp_reload_script();
        });
    };

    $.fn.ctwp_field_gallery = function() {
        return this.each(function() {
            var $this = $(this),
                $edit = $this.find('.ctwp-edit-gallery'),
                $clear = $this.find('.ctwp-clear-gallery'),
                $list = $this.find('ul'),
                $input = $this.find('input'),
                $img = $this.find('img'),
                wp_media_frame;
                $this.on('click', '.ctwp-button, .ctwp-edit-gallery', function(e) {
                    var $el = $(this),
                        ids = $input.val(),
                        what = ($el.hasClass('ctwp-edit-gallery')) ? 'edit' : 'add',
                        state = (what === 'add' && !ids.length) ? 'gallery' : 'gallery-edit';
                    e.preventDefault();
                    if (typeof window.wp === 'undefined' || !window.wp.media || !window.wp.media.gallery) { return; }
                    if (state === 'gallery') {
                        wp_media_frame = window.wp.media({
                            library: {
                                type: 'image'
                            },
                            frame: 'post',
                            state: 'gallery',
                            multiple: true
                        });
                        wp_media_frame.open();
                    } else {
                        wp_media_frame = window.wp.media.gallery.edit('[gallery ids="' + ids + '"]');
                        if (what === 'add') {
                            wp_media_frame.setState('gallery-library');
                        }
                    }
                    wp_media_frame.on('update', function(selection) {
                    $list.empty();
                    var selectedIds = selection.models.map(function(attachment) {
                        var item = attachment.toJSON();
                        var thumb = (item.sizes && item.sizes.thumbnail && item.sizes.thumbnail.url) ? item.sizes.thumbnail.url : item.url;
                        $list.append('<li><img src="' + thumb + '"></li>');
                        return item.id;
                    });
                    $input.val(selectedIds.join(',')).trigger('change');
                    $clear.removeClass('hidden');
                    $edit.removeClass('hidden');
                });
            });

            $clear.on('click', function(e) {
                e.preventDefault();
                $list.empty();
                $input.val('').trigger('change');
                $clear.addClass('hidden');
                $edit.addClass('hidden');
            });
        });
    };

    /**
     * Field: group
     * @returns 
     */
    $.fn.ctwp_field_group = function() {
        return this.each(function() {
            var $this = $(this),
            $fieldset = $this.children('.ctwp-fieldset'),
            $group = $fieldset.length ? $fieldset : $this,
            $wrapper = $group.children('.ctwp-cloneable-wrapper'),
            $hidden = $group.children('.ctwp-cloneable-hidden'),
            $max = $group.children('.ctwp-cloneable-max'),
            $min = $group.children('.ctwp-cloneable-min'),
            field_id = $wrapper.data('field-id'),
            is_number = Boolean(Number($wrapper.data('title-number'))),
            max = parseInt($wrapper.data('max')),
            min = parseInt($wrapper.data('min'));

            if ($wrapper.hasClass('ui-accordion')) {
                $wrapper.find('.ui-accordion-header-icon').remove();
            }
            var update_title_numbers = function($selector) {
                $selector.find('.ctwp-cloneable-title-number').each(function(index) {
                    $(this).html(($(this).closest('.ctwp-cloneable-item').index() + 1) + '.');
                });
            };
            $wrapper.accordion({
                header: '> .ctwp-cloneable-item > .ctwp-cloneable-title',
                collapsible: true,
                active: false,
                animate: false,
                heightStyle: 'content',
                icons: {
                    'header': 'ctwp-cloneable-header-icon fas fa-angle-right',
                    'activeHeader': 'ctwp-cloneable-header-icon fas fa-angle-down'
                },
                activate: function(event, ui) {
                    var $panel = ui.newPanel;
                    var $header = ui.newHeader;
                    if ($panel.length && !$panel.data('opened')) {
                        var $fields = $panel.children();
                        var $first = $fields.first().find(':input').first();
                        var $title = $header.find('.ctwp-cloneable-value');
                        $first.on('change keyup', function(event) {
                            $title.text($first.val());
                        });
                        $panel.ctwp_reload_script();
                        $panel.data('opened', true);
                        $panel.data('retry', false);
                    } else if ($panel.data('retry')) {
                        $panel.ctwp_reload_script_retry();
                        $panel.data('retry', false);
                    }
                }
            });
            $wrapper.sortable({
                axis: 'y',
                handle: '.ctwp-cloneable-title,.ctwp-cloneable-sort',
                helper: 'original',
                cursor: 'move',
                placeholder: 'widget-placeholder',
                start: function(event, ui) {
                    $wrapper.accordion({ active: false });
                    $wrapper.sortable('refreshPositions');
                    ui.item.children('.ctwp-cloneable-content').data('retry', true);
                },
                update: function(event, ui) {
                    CTWP.helper.name_nested_replace($wrapper.children('.ctwp-cloneable-item'), field_id);
                    $wrapper.ctwp_customizer_refresh();
                    if (is_number) {
                        update_title_numbers($wrapper);
                    }
                },
            });

            $group.children('.ctwp-cloneable-add').on('click', function(e) {
                e.preventDefault();
                var count = $wrapper.children('.ctwp-cloneable-item').length;
                $min.hide();
                if (max && (count + 1) > max) {
                    $max.show();
                    return;
                }
                var $cloned_item = $hidden.ctwp_clone(true);
                $cloned_item.removeClass('ctwp-cloneable-hidden');
                $cloned_item.find(':input[name!="_pseudo"]').each(function() {
                    this.name = this.name.replace('___', '').replace(field_id + '[0]', field_id + '[' + count + ']');
                });
                $wrapper.append($cloned_item);
                $wrapper.accordion('refresh');
                $wrapper.accordion({ active: count });
                $wrapper.ctwp_customizer_refresh();
                $wrapper.ctwp_customizer_listen({ closest: true });
                if (is_number) {
                    update_title_numbers($wrapper);
                }
            });
            var event_clone = function(e) {
                e.preventDefault();
                var count = $wrapper.children('.ctwp-cloneable-item').length;
                $min.hide();
                if (max && (count + 1) > max) {
                    $max.show();
                    return;
                }
                var $this = $(this),
                    $parent = $this.parent().parent(),
                    $cloned_helper = $parent.children('.ctwp-cloneable-helper').ctwp_clone(true),
                    $cloned_title = $parent.children('.ctwp-cloneable-title').ctwp_clone(),
                    $cloned_content = $parent.children('.ctwp-cloneable-content').ctwp_clone(),
                    $cloned_item = $('<div class="ctwp-cloneable-item" />');
                $cloned_item.append($cloned_helper);
                $cloned_item.append($cloned_title);
                $cloned_item.append($cloned_content);
                $wrapper.children().eq($parent.index()).after($cloned_item);
                CTWP.helper.name_nested_replace($wrapper.children('.ctwp-cloneable-item'), field_id);
                $wrapper.accordion('refresh');
                $wrapper.ctwp_customizer_refresh();
                $wrapper.ctwp_customizer_listen({ closest: true });
                if (is_number) {
                    update_title_numbers($wrapper);
                }
            };

            $wrapper.children('.ctwp-cloneable-item').children('.ctwp-cloneable-helper').on('click', '.ctwp-cloneable-clone', event_clone);
            $group.children('.ctwp-cloneable-hidden').children('.ctwp-cloneable-helper').on('click', '.ctwp-cloneable-clone', event_clone);
            var event_remove = function(e) {
                e.preventDefault();
                var count = $wrapper.children('.ctwp-cloneable-item').length;
                $max.hide();
                $min.hide();
                if (min && (count - 1) < min) {
                    $min.show();
                    return;
                }
                $(this).closest('.ctwp-cloneable-item').remove();
                CTWP.helper.name_nested_replace($wrapper.children('.ctwp-cloneable-item'), field_id);
                $wrapper.ctwp_customizer_refresh();
                if (is_number) {
                    update_title_numbers($wrapper);
                }
            };
            $wrapper.children('.ctwp-cloneable-item').children('.ctwp-cloneable-helper').on('click', '.ctwp-cloneable-remove', event_remove);
            $group.children('.ctwp-cloneable-hidden').children('.ctwp-cloneable-helper').on('click', '.ctwp-cloneable-remove', event_remove);
        });
    };

    /**
     * Field: icon
     */
    $.fn.ctwp_field_icon = function() {
        return this.each(function() {
            var $this = $(this);
            $this.on('click', '.ctwp-icon-add', function(e) {
                e.preventDefault();
                var $button = $(this);
                var $modal = $('#ctwp-modal-icon');
                $modal.removeClass('hidden');
                CTWP.vars.$icon_target = $this;
                if (!CTWP.vars.icon_modal_loaded) {
                    $modal.find('.ctwp-modal-loading').show();
                    window.wp.ajax.post('ctwp-get-icons', {
                        nonce: $button.data('nonce')
                    }).done(function(response) {
                        $modal.find('.ctwp-modal-loading').hide();
                        CTWP.vars.icon_modal_loaded = true;
                        var $load = $modal.find('.ctwp-modal-load').html(response.content);
                        $load.on('click', 'i', function(e) {
                            e.preventDefault();
                            var icon = $(this).attr('title');
                            CTWP.vars.$icon_target.find('.ctwp-icon-preview i').removeAttr('class').addClass(icon);
                            CTWP.vars.$icon_target.find('.ctwp-icon-preview').removeClass('hidden');
                            CTWP.vars.$icon_target.find('.ctwp-icon-remove').removeClass('hidden');
                            CTWP.vars.$icon_target.find('input').val(icon).trigger('change');
                            $modal.addClass('hidden');
                        });
                        $modal.on('change keyup', '.ctwp-icon-search', function() {
                            var value = $(this).val(),
                            $icons = $load.find('i');
                            $icons.each(function() {
                                var $elem = $(this);
                                if ($elem.attr('title').search(new RegExp(value, 'i')) < 0) {
                                    $elem.hide();
                                } else {
                                    $elem.show();
                                }
                            });
                        });
                        $modal.on('click', '.ctwp-modal-close, .ctwp-modal-overlay', function() {
                            $modal.addClass('hidden');
                        });
                    }).fail(function(response) {
                        $modal.find('.ctwp-modal-loading').hide();
                        $modal.find('.ctwp-modal-load').html(response.error);
                        $modal.on('click', function() {
                            $modal.addClass('hidden');
                        });
                    });
                }
            });
            $this.on('click', '.ctwp-icon-remove', function(e) {
                e.preventDefault();
                $this.find('.ctwp-icon-preview').addClass('hidden');
                $this.find('input').val('').trigger('change');
                $(this).addClass('hidden');
            });
        });
    };

    /**
     * Field: map
     */
    // 
    //
    $.fn.ctwp_field_map = function() {
        return this.each(function() {
            if (typeof L === 'undefined') { return; }
            var $this = $(this),
            $map = $this.find('.ctwp--map-osm'),
            $search_input = $this.find('.ctwp--map-search input'),
            $latitude = $this.find('.ctwp--latitude'),
            $longitude = $this.find('.ctwp--longitude'),
            $zoom = $this.find('.ctwp--zoom'),
            map_data = $map.data('map');
            var mapInit = L.map($map.get(0), map_data);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(mapInit);
            var mapMarker = L.marker(map_data.center, { draggable: true }).addTo(mapInit);
            var update_latlng = function(data) {
                $latitude.val(data.lat);
                $longitude.val(data.lng);
                $zoom.val(mapInit.getZoom());
            };
            mapInit.on('click', function(data) {
                mapMarker.setLatLng(data.latlng);
                update_latlng(data.latlng);
            });
            mapInit.on('zoom', function() {
                update_latlng(mapMarker.getLatLng());
            });
            mapMarker.on('drag', function() {
                update_latlng(mapMarker.getLatLng());
            });
            if (!$search_input.length) {
                $search_input = $('[data-depend-id="' + $this.find('.ctwp--address-field').data('address-field') + '"]');
            }
            var cache = {};
            $search_input.autocomplete({
                source: function(request, response) {
                    var term = request.term;
                    if (term in cache) {
                        response(cache[term]);
                        return;
                    }
                    $.get('https://nominatim.openstreetmap.org/search', {
                        format: 'json',
                        q: term,
                    }, function(results) {
                        var data;
                        if (results.length) {
                            data = results.map(function(item) {
                                return {
                                    value: item.display_name,
                                    label: item.display_name,
                                    lat: item.lat,
                                    lon: item.lon
                                };
                            }, 'json');
                        } else {
                            data = [{
                                value: 'no-data',
                                label: 'No Results.'
                            }];
                        }
                        cache[term] = data;
                        response(data);
                    });
                },
                select: function(event, ui) {
                    if (ui.item.value === 'no-data') { return false; }
                    var latLng = L.latLng(ui.item.lat, ui.item.lon);
                    mapInit.panTo(latLng);
                    mapMarker.setLatLng(latLng);
                    update_latlng(latLng);
                },
                create: function(event, ui) {
                    $(this).autocomplete('widget').addClass('ctwp-map-ui-autocomplate');
                }
            });
            var input_update_latlng = function() {
                var latLng = L.latLng($latitude.val(), $longitude.val());
                mapInit.panTo(latLng);
                mapMarker.setLatLng(latLng);
            };
            $latitude.on('change', input_update_latlng);
            $longitude.on('change', input_update_latlng);
        });
    };

    /**
     * Field: link
     */
    $.fn.ctwp_field_link = function() {
        return this.each(function() {
            var $this = $(this),
            $link = $this.find('.ctwp--link'),
            $add = $this.find('.ctwp--add'),
            $edit = $this.find('.ctwp--edit'),
            $remove = $this.find('.ctwp--remove'),
            $result = $this.find('.ctwp--result'),
            uniqid = CTWP.helper.uid('ctwp-wplink-textarea-');
            $add.on('click', function(e) {
                e.preventDefault();
                window.wpLink.open(uniqid);
            });
            $edit.on('click', function(e) {
                e.preventDefault();
                $add.trigger('click');
                $('#wp-link-url').val($this.find('.ctwp--url').val());
                $('#wp-link-text').val($this.find('.ctwp--text').val());
                $('#wp-link-target').prop('checked', ($this.find('.ctwp--target').val() === '_blank'));
            });
            $remove.on('click', function(e) {
                e.preventDefault();
                $this.find('.ctwp--url').val('').trigger('change');
                $this.find('.ctwp--text').val('');
                $this.find('.ctwp--target').val('');
                $add.removeClass('hidden');
                $edit.addClass('hidden');
                $remove.addClass('hidden');
                $result.parent().addClass('hidden');
            });
            $link.attr('id', uniqid).on('change', function() {
                var atts = window.wpLink.getAttrs(),
                    href = atts.href,
                    text = $('#wp-link-text').val(),
                    target = (atts.target) ? atts.target : '';
                $this.find('.ctwp--url').val(href).trigger('change');
                $this.find('.ctwp--text').val(text);
                $this.find('.ctwp--target').val(target);
                $result.html('{url:"' + href + '", text:"' + text + '", target:"' + target + '"}');
                $add.addClass('hidden');
                $edit.removeClass('hidden');
                $remove.removeClass('hidden');
                $result.parent().removeClass('hidden');
            });
        });
    };

    /**
     * Field: media
     */
    $.fn.ctwp_field_media = function() {
        return this.each(function() {
            var $this = $(this),
            $upload_button = $this.find('.ctwp--button'),
            $remove_button = $this.find('.ctwp--remove'),
            $library = $upload_button.data('library') && $upload_button.data('library').split(',') || '',
            $auto_attributes = ($this.hasClass('ctwp-assign-field-background')) ? $this.closest('.ctwp-field-background').find('.ctwp--auto-attributes') : false,
            wp_media_frame;
            $upload_button.on('click', function(e) {
                e.preventDefault();
                if (typeof window.wp === 'undefined' || !window.wp.media || !window.wp.media.gallery) {
                    return;
                }
                if (wp_media_frame) {
                    wp_media_frame.open();
                    return;
                }
                wp_media_frame = window.wp.media({
                    library: {
                        type: $library
                    }
                });
                wp_media_frame.on('select', function() {
                    var thumbnail;
                    var attributes = wp_media_frame.state().get('selection').first().attributes;
                    var preview_size = $upload_button.data('preview-size') || 'thumbnail';
                    if ($library.length && $library.indexOf(attributes.subtype) === -1 && $library.indexOf(attributes.type) === -1) {
                        return;
                    }
                    $this.find('.ctwp--id').val(attributes.id);
                    $this.find('.ctwp--width').val(attributes.width);
                    $this.find('.ctwp--height').val(attributes.height);
                    $this.find('.ctwp--alt').val(attributes.alt);
                    $this.find('.ctwp--title').val(attributes.title);
                    $this.find('.ctwp--description').val(attributes.description);
                    if (typeof attributes.sizes !== 'undefined' && typeof attributes.sizes.thumbnail !== 'undefined' && preview_size === 'thumbnail') {
                        thumbnail = attributes.sizes.thumbnail.url;
                    } else if (typeof attributes.sizes !== 'undefined' && typeof attributes.sizes.full !== 'undefined') {
                        thumbnail = attributes.sizes.full.url;
                    } else if (attributes.type === 'image') {
                        thumbnail = attributes.url;
                    } else {
                        thumbnail = attributes.icon;
                    }
                    console.log(attributes);
                    if ($auto_attributes) {
                        $auto_attributes.removeClass('ctwp--attributes-hidden');
                    }
                    $remove_button.removeClass('hidden');
                    $this.find('.ctwp--preview').removeClass('hidden');
                    $this.find('.ctwp--src').attr('src', thumbnail);
                    $this.find('.ctwp--thumbnail').val(thumbnail);
                    $this.find('.ctwp--url').val(attributes.url).trigger('change');
                });
                wp_media_frame.open();
            });
            $remove_button.on('click', function(e) {
                e.preventDefault();
                if ($auto_attributes) {
                    $auto_attributes.addClass('ctwp--attributes-hidden');
                }
                $remove_button.addClass('hidden');
                $this.find('input').val('');
                $this.find('.ctwp--preview').addClass('hidden');
                $this.find('.ctwp--url').trigger('change');
            });
        });
    };

    /**
     * Field: repeater
     */
    $.fn.ctwp_field_repeater = function() {
        return this.each(function() {
            var $this = $(this),
            $fieldset = $this.children('.ctwp-fieldset'),
            $repeater = $fieldset.length ? $fieldset : $this,
            $wrapper = $repeater.children('.ctwp-repeater-wrapper'),
            $hidden = $repeater.children('.ctwp-repeater-hidden'),
            $max = $repeater.children('.ctwp-repeater-max'),
            $min = $repeater.children('.ctwp-repeater-min'),
            field_id = $wrapper.data('field-id'),
            max = parseInt($wrapper.data('max')),
            min = parseInt($wrapper.data('min'));
            $wrapper.children('.ctwp-repeater-item').children('.ctwp-repeater-content').ctwp_reload_script();
            $wrapper.sortable({
                axis: 'y',
                handle: '.ctwp-repeater-sort',
                helper: 'original',
                cursor: 'move',
                placeholder: 'widget-placeholder',
                update: function(event, ui) {
                    CTWP.helper.name_nested_replace($wrapper.children('.ctwp-repeater-item'), field_id);
                    $wrapper.ctwp_customizer_refresh();
                    ui.item.ctwp_reload_script_retry();
                }
            });

            $repeater.children('.ctwp-repeater-add').on('click', function(e) {
                e.preventDefault();
                var count = $wrapper.children('.ctwp-repeater-item').length;
                $min.hide();
                if (max && (count + 1) > max) {
                    $max.show();
                    return;
                }
                var $cloned_item = $hidden.ctwp_clone(true);
                $cloned_item.removeClass('ctwp-repeater-hidden');
                $cloned_item.find(':input[name!="_pseudo"]').each(function() {
                    this.name = this.name.replace('___', '').replace(field_id + '[0]', field_id + '[' + count + ']');
                });
                $wrapper.append($cloned_item);
                $cloned_item.children('.ctwp-repeater-content').ctwp_reload_script();
                $wrapper.ctwp_customizer_refresh();
                $wrapper.ctwp_customizer_listen({ closest: true });
            });

            var event_clone = function(e) {
                e.preventDefault();
                var count = $wrapper.children('.ctwp-repeater-item').length;
                $min.hide();
                if (max && (count + 1) > max) {
                    $max.show();
                    return;
                }
                var $this = $(this),
                    $parent = $this.parent().parent().parent(),
                    $cloned_content = $parent.children('.ctwp-repeater-content').ctwp_clone(),
                    $cloned_helper = $parent.children('.ctwp-repeater-helper').ctwp_clone(true),
                    $cloned_item = $('<div class="ctwp-repeater-item" />');
                $cloned_item.append($cloned_content);
                $cloned_item.append($cloned_helper);
                $wrapper.children().eq($parent.index()).after($cloned_item);
                $cloned_item.children('.ctwp-repeater-content').ctwp_reload_script();
                CTWP.helper.name_nested_replace($wrapper.children('.ctwp-repeater-item'), field_id);
                $wrapper.ctwp_customizer_refresh();
                $wrapper.ctwp_customizer_listen({ closest: true });
            };

            $wrapper.children('.ctwp-repeater-item').children('.ctwp-repeater-helper').on('click', '.ctwp-repeater-clone', event_clone);
            $repeater.children('.ctwp-repeater-hidden').children('.ctwp-repeater-helper').on('click', '.ctwp-repeater-clone', event_clone);
            var event_remove = function(e) {
                e.preventDefault();
                var count = $wrapper.children('.ctwp-repeater-item').length;
                $max.hide();
                $min.hide();
                if (min && (count - 1) < min) {
                    $min.show();
                    return;
                }
                $(this).closest('.ctwp-repeater-item').remove();
                CTWP.helper.name_nested_replace($wrapper.children('.ctwp-repeater-item'), field_id);
                $wrapper.ctwp_customizer_refresh();
            };
            $wrapper.children('.ctwp-repeater-item').children('.ctwp-repeater-helper').on('click', '.ctwp-repeater-remove', event_remove);
            $repeater.children('.ctwp-repeater-hidden').children('.ctwp-repeater-helper').on('click', '.ctwp-repeater-remove', event_remove);
        });
    };

    /**
     * Field: slider
     */
    $.fn.ctwp_field_slider = function() {
        return this.each(function() {
            var $this = $(this),
                $input = $this.find('input'),
                $slider = $this.find('.ctwp-slider-ui'),
                data = $input.data(),
                value = $input.val() || 0;
            if ($slider.hasClass('ui-slider')) {
                $slider.empty();
            }
            $slider.slider({
                range: 'min',
                value: value,
                min: data.min || 0,
                max: data.max || 100,
                step: data.step || 1,
                slide: function(e, o) {
                    $input.val(o.value).trigger('change');
                }
            });
            $input.on('keyup', function() {
                $slider.slider('value', $input.val());
            });
        });
    };

    /**
     * Field: sortable
     */
    $.fn.ctwp_field_sortable = function() {
        return this.each(function() {
            var $sortable = $(this).find('.ctwp-sortable');
            $sortable.sortable({
                axis: 'y',
                helper: 'original',
                cursor: 'move',
                placeholder: 'widget-placeholder',
                update: function(event, ui) {
                    $sortable.ctwp_customizer_refresh();
                }
            });
            $sortable.find('.ctwp-sortable-content').ctwp_reload_script();
        });
    };

    $.fn.ctwp_field_sorter = function() {
        return this.each(function() {
            var $this = $(this),
                $enabled = $this.find('.ctwp-enabled'),
                $has_disabled = $this.find('.ctwp-disabled'),
                $disabled = ($has_disabled.length) ? $has_disabled : false;
            $enabled.sortable({
                connectWith: $disabled,
                placeholder: 'ui-sortable-placeholder',
                update: function(event, ui) {
                    var $el = ui.item.find('input');
                    if (ui.item.parent().hasClass('ctwp-enabled')) {
                        $el.attr('name', $el.attr('name').replace('disabled', 'enabled'));
                    } else {
                        $el.attr('name', $el.attr('name').replace('enabled', 'disabled'));
                    }
                    $this.ctwp_customizer_refresh();
                }
            });

            if ($disabled) {
                $disabled.sortable({
                    connectWith: $enabled,
                    placeholder: 'ui-sortable-placeholder',
                    update: function(event, ui) {
                        $this.ctwp_customizer_refresh();
                    }
                });
            }
        });
    };

    $.fn.ctwp_field_spinner = function() {
        return this.each(function() {
            var $this = $(this),
                $input = $this.find('input'),
                $inited = $this.find('.ui-button'),
                data = $input.data();
            if ($inited.length) {
                $inited.remove();
            }
            $input.spinner({
                min: data.min || 0,
                max: data.max || 100,
                step: data.step || 1,
                create: function(event, ui) {
                    if (data.unit) {
                        $input.after('<span class="ui-button ctwp--unit">' + data.unit + '</span>');
                    }
                },
                spin: function(event, ui) {
                    $input.val(ui.value).trigger('change');
                }
            });
        });
    };

    $.fn.ctwp_field_switcher = function() {
        return this.each(function() {
            var $switcher = $(this).find('.ctwp--switcher');
            $switcher.on('click', function() {
                var value = 0;
                var $input = $switcher.find('input');
                if ($switcher.hasClass('ctwp--active')) {
                    $switcher.removeClass('ctwp--active');
                } else {
                    value = 1;
                    $switcher.addClass('ctwp--active');
                }
                $input.val(value).trigger('change');
            });
        });
    };

    $.fn.ctwp_field_tabbed = function() {
        return this.each(function() {
            var $this = $(this),
                $links = $this.find('.ctwp-tabbed-nav a'),
                $contents = $this.find('.ctwp-tabbed-content');
            $contents.eq(0).ctwp_reload_script();
            $links.on('click', function(e) {
                e.preventDefault();
                var $link = $(this),
                    index = $link.index(),
                    $content = $contents.eq(index);
                $link.addClass('ctwp-tabbed-active').siblings().removeClass('ctwp-tabbed-active');
                $content.ctwp_reload_script();
                $content.removeClass('hidden').siblings().addClass('hidden');
            });
        });
    };

    $.fn.ctwp_field_typography = function() {
        return this.each(function() {
            var base = this;
            var $this = $(this);
            var loaded_fonts = [];
            var webfonts = ctwp_typography_json.webfonts;
            var googlestyles = ctwp_typography_json.googlestyles;
            var defaultstyles = ctwp_typography_json.defaultstyles;
            
            base.sanitize_subset = function(subset) {
                subset = subset.replace('-ext', ' Extended');
                subset = subset.charAt(0).toUpperCase() + subset.slice(1);
                return subset;
            };
            base.sanitize_style = function(style) {
                return googlestyles[style] ? googlestyles[style] : style;
            };
            base.load_google_font = function(font_family, weight, style) {
                if (font_family && typeof WebFont === 'object') {
                    weight = weight ? weight.replace('normal', '') : '';
                    style = style ? style.replace('normal', '') : '';
                    if (weight || style) {
                        font_family = font_family + ':' + weight + style;
                    }
                    if (loaded_fonts.indexOf(font_family) === -1) {
                        WebFont.load({ google: { families: [font_family] } });
                    }
                    loaded_fonts.push(font_family);
                }
            };
            base.append_select_options = function($select, options, condition, type, is_multi) {
                $select.find('option').not(':first').remove();
                var opts = '';
                $.each(options, function(key, value) {
                    var selected;
                    var name = value;
                    if (is_multi) {
                        selected = (condition && condition.indexOf(value) !== -1) ? ' selected' : '';
                    } else {
                        selected = (condition && condition === value) ? ' selected' : '';
                    }
                    if (type === 'subset') {
                        name = base.sanitize_subset(value);
                    } else if (type === 'style') {
                        name = base.sanitize_style(value);
                    }
                    opts += '<option value="' + value + '"' + selected + '>' + name + '</option>';
                });
                $select.append(opts).trigger('ctwp.change').trigger('chosen:updated');
            };
            base.init = function() {
                var selected_styles = [];
                var $typography = $this.find('.ctwp--typography');
                var $type = $this.find('.ctwp--type');
                var $styles = $this.find('.ctwp--block-font-style');
                var unit = $typography.data('unit');
                var line_height_unit = $typography.data('line-height-unit');
                var exclude_fonts = $typography.data('exclude') ? $typography.data('exclude').split(',') : [];
                if ($this.find('.ctwp--chosen').length) {
                    var $chosen_selects = $this.find('select');
                    $chosen_selects.each(function() {
                        var $chosen_select = $(this),
                            $chosen_inited = $chosen_select.parent().find('.chosen-container');
                        if ($chosen_inited.length) {
                            $chosen_inited.remove();
                        }
                        $chosen_select.chosen({
                            allow_single_deselect: true,
                            disable_search_threshold: 15,
                            width: '100%'
                        });
                    });
                }
                var $font_family_select = $this.find('.ctwp--font-family');
                var first_font_family = $font_family_select.val();
                $font_family_select.find('option').not(':first-child').remove();
                var opts = '';
                $.each(webfonts, function(type, group) {
                    if (exclude_fonts && exclude_fonts.indexOf(type) !== -1) { return; }
                    opts += '<optgroup label="' + group.label + '">';
                    $.each(group.fonts, function(key, value) {
                        value = (typeof value === 'object') ? key : value;
                        var selected = (value === first_font_family) ? ' selected' : '';
                        opts += '<option value="' + value + '" data-type="' + type + '"' + selected + '>' + value + '</option>';
                    });
                    opts += '</optgroup>';
                });
                $font_family_select.append(opts).trigger('chosen:updated');
                var $font_style_block = $this.find('.ctwp--block-font-style');
                if ($font_style_block.length) {

                    var $font_style_select = $this.find('.ctwp--font-style-select');
                    var first_style_value = $font_style_select.val() ? $font_style_select.val().replace(/normal/g, '') : '';
                    $font_style_select.on('change ctwp.change', function(event) {
                        var style_value = $font_style_select.val();
                        if (!style_value && selected_styles && selected_styles.indexOf('normal') === -1) {
                            style_value = selected_styles[0];
                        }
                        var font_normal = (style_value && style_value !== 'italic' && style_value === 'normal') ? 'normal' : '';
                        var font_weight = (style_value && style_value !== 'italic' && style_value !== 'normal') ? style_value.replace('italic', '') : font_normal;
                        var font_style = (style_value && style_value.substr(-6) === 'italic') ? 'italic' : '';
                        $this.find('.ctwp--font-weight').val(font_weight);
                        $this.find('.ctwp--font-style').val(font_style);
                    });
                    var $extra_font_style_block = $this.find('.ctwp--block-extra-styles');
                    if ($extra_font_style_block.length) {
                        var $extra_font_style_select = $this.find('.ctwp--extra-styles');
                        var first_extra_style_value = $extra_font_style_select.val();
                    }
                }
                var $subset_block = $this.find('.ctwp--block-subset');
                if ($subset_block.length) {
                    var $subset_select = $this.find('.ctwp--subset');
                    var first_subset_select_value = $subset_select.val();
                    var subset_multi_select = $subset_select.data('multiple') || false;
                }
                var $backup_font_family_block = $this.find('.ctwp--block-backup-font-family');
                $font_family_select.on('change ctwp.change', function(event) {
                    if ($subset_block.length) {
                        $subset_block.addClass('hidden');
                    }
                    if ($extra_font_style_block.length) {
                        $extra_font_style_block.addClass('hidden');
                    }
                    if ($backup_font_family_block.length) {
                        $backup_font_family_block.addClass('hidden');
                    }
                    var $selected = $font_family_select.find(':selected');
                    var value = $selected.val();
                    var type = $selected.data('type');
                    if (type && value) {
                        if ((type === 'google' || type === 'custom') && $backup_font_family_block.length) {
                            $backup_font_family_block.removeClass('hidden');
                        }
                        if ($font_style_block.length) {
                            var styles = defaultstyles;
                            if (type === 'google' && webfonts[type].fonts[value][0]) {
                                styles = webfonts[type].fonts[value][0];
                            } else if (type === 'custom' && webfonts[type].fonts[value]) {
                                styles = webfonts[type].fonts[value];
                            }
                            selected_styles = styles;
                            var set_auto_style = (styles.indexOf('normal') !== -1) ? 'normal' : styles[0];
                            var set_style_value = (first_style_value && styles.indexOf(first_style_value) !== -1) ? first_style_value : set_auto_style;
                            base.append_select_options($font_style_select, styles, set_style_value, 'style');
                            first_style_value = false;
                            $font_style_block.removeClass('hidden');
                            if (type === 'google' && $extra_font_style_block.length && styles.length > 1) {
                                base.append_select_options($extra_font_style_select, styles, first_extra_style_value, 'style', true);
                                first_extra_style_value = false;
                                $extra_font_style_block.removeClass('hidden');
                            }
                        }
                        if (type === 'google' && $subset_block.length && webfonts[type].fonts[value][1]) {
                            var subsets = webfonts[type].fonts[value][1];
                            var set_auto_subset = (subsets.length < 2 && subsets[0] !== 'latin') ? subsets[0] : '';
                            var set_subset_value = (first_subset_select_value && subsets.indexOf(first_subset_select_value) !== -1) ? first_subset_select_value : set_auto_subset;
                            set_subset_value = (subset_multi_select && first_subset_select_value) ? first_subset_select_value : set_subset_value;
                            base.append_select_options($subset_select, subsets, set_subset_value, 'subset', subset_multi_select);
                            first_subset_select_value = false;
                            $subset_block.removeClass('hidden');
                        }
                    } else {
                        $styles.find(':input').val('');
                        if ($subset_block.length) {
                            $subset_select.find('option').not(':first-child').remove();
                            $subset_select.trigger('chosen:updated');
                        }
                        if ($font_style_block.length) {
                            $font_style_select.find('option').not(':first-child').remove();
                            $font_style_select.trigger('chosen:updated');
                        }
                    }
                    $type.val(type);
                }).trigger('ctwp.change');
                var $preview_block = $this.find('.ctwp--block-preview');
                if ($preview_block.length) {
                    var $preview = $this.find('.ctwp--preview');
                    $this.on('change', CTWP.helper.debounce(function(event) {
                        $preview_block.removeClass('hidden');
                        var font_family = $font_family_select.val(),
                            font_weight = $this.find('.ctwp--font-weight').val(),
                            font_style = $this.find('.ctwp--font-style').val(),
                            font_size = $this.find('.ctwp--font-size').val(),
                            font_variant = $this.find('.ctwp--font-variant').val(),
                            line_height = $this.find('.ctwp--line-height').val(),
                            text_align = $this.find('.ctwp--text-align').val(),
                            text_transform = $this.find('.ctwp--text-transform').val(),
                            text_decoration = $this.find('.ctwp--text-decoration').val(),
                            text_color = $this.find('.ctwp--color').val(),
                            word_spacing = $this.find('.ctwp--word-spacing').val(),
                            letter_spacing = $this.find('.ctwp--letter-spacing').val(),
                            custom_style = $this.find('.ctwp--custom-style').val(),
                            type = $this.find('.ctwp--type').val();
                        if (type === 'google') {
                            base.load_google_font(font_family, font_weight, font_style);
                        }
                        var properties = {};
                        if (font_family) { properties.fontFamily = font_family; }
                        if (font_weight) { properties.fontWeight = font_weight; }
                        if (font_style) { properties.fontStyle = font_style; }
                        if (font_variant) { properties.fontVariant = font_variant; }
                        if (font_size) { properties.fontSize = font_size + unit; }
                        if (line_height) { properties.lineHeight = line_height + line_height_unit; }
                        if (letter_spacing) { properties.letterSpacing = letter_spacing + unit; }
                        if (word_spacing) { properties.wordSpacing = word_spacing + unit; }
                        if (text_align) { properties.textAlign = text_align; }
                        if (text_transform) { properties.textTransform = text_transform; }
                        if (text_decoration) { properties.textDecoration = text_decoration; }
                        if (text_color) { properties.color = text_color; }
                        $preview.removeAttr('style');
                        if (custom_style) { $preview.attr('style', custom_style); }
                        $preview.css(properties);
                    }, 100));
                    $preview_block.on('click', function() {
                        $preview.toggleClass('ctwp--black-background');
                        var $toggle = $preview_block.find('.ctwp--toggle');
                        if ($toggle.hasClass('fa-toggle-off')) {
                            $toggle.removeClass('fa-toggle-off').addClass('fa-toggle-on');
                        } else {
                            $toggle.removeClass('fa-toggle-on').addClass('fa-toggle-off');
                        }
                    });
                    if (!$preview_block.hasClass('hidden')) {
                        $this.trigger('change');
                    }
                }
            };
            base.init();
        });
    };

    $.fn.ctwp_field_upload = function() {
        return this.each(function() {
            var $this = $(this),
                $input = $this.find('input'),
                $upload_button = $this.find('.ctwp--button'),
                $remove_button = $this.find('.ctwp--remove'),
                $preview_wrap = $this.find('.ctwp--preview'),
                $preview_src = $this.find('.ctwp--src'),
                $library = $upload_button.data('library') && $upload_button.data('library').split(',') || '',
                wp_media_frame;
            $upload_button.on('click', function(e) {
                e.preventDefault();
                if (typeof window.wp === 'undefined' || !window.wp.media || !window.wp.media.gallery) {
                    return;
                }
                if (wp_media_frame) {
                    wp_media_frame.open();
                    return;
                }
                wp_media_frame = window.wp.media({
                    library: {
                        type: $library
                    },
                });
                wp_media_frame.on('select', function() {
                    var src;
                    var attributes = wp_media_frame.state().get('selection').first().attributes;
                    if ($library.length && $library.indexOf(attributes.subtype) === -1 && $library.indexOf(attributes.type) === -1) {
                        return;
                    }
                    $input.val(attributes.url).trigger('change');
                });
                wp_media_frame.open();
            });
            $remove_button.on('click', function(e) {
                e.preventDefault();
                $input.val('').trigger('change');
            });
            $input.on('change', function(e) {
                var $value = $input.val();
                if ($value) {
                    $remove_button.removeClass('hidden');
                } else {
                    $remove_button.addClass('hidden');
                }
                if ($preview_wrap.length) {
                    if ($.inArray($value.split('.').pop().toLowerCase(), ['jpg', 'jpeg', 'gif', 'png', 'svg', 'webp']) !== -1) {
                        $preview_wrap.removeClass('hidden');
                        $preview_src.attr('src', $value);
                    } else {
                        $preview_wrap.addClass('hidden');
                    }
                }
            });
        });
    };
    $.fn.ctwp_field_wp_editor = function() {
        return this.each(function() {
            if (typeof window.wp.editor === 'undefined' || typeof window.tinyMCEPreInit === 'undefined' || typeof window.tinyMCEPreInit.mceInit.ctwp_wp_editor === 'undefined') {
                return;
            }
            var $this = $(this),
                $editor = $this.find('.ctwp-wp-editor'),
                $textarea = $this.find('textarea');
            var $has_wp_editor = $this.find('.wp-editor-wrap').length || $this.find('.mce-container').length;
            if ($has_wp_editor) {
                $editor.empty();
                $editor.append($textarea);
                $textarea.css('display', '');
            }
            var uid = CTWP.helper.uid('ctwp-editor-');
            $textarea.attr('id', uid);
            var default_editor_settings = {
                tinymce: window.tinyMCEPreInit.mceInit.ctwp_wp_editor,
                quicktags: window.tinyMCEPreInit.qtInit.ctwp_wp_editor
            };
            var field_editor_settings = $editor.data('editor-settings');
            var wpEditor = wp.oldEditor ? wp.oldEditor : wp.editor;
            if (wpEditor && wpEditor.hasOwnProperty('autop')) {
                wp.editor.autop = wpEditor.autop;
                wp.editor.removep = wpEditor.removep;
                wp.editor.initialize = wpEditor.initialize;
            }
            var editor_on_change = function(editor) {
                editor.on('change keyup', function() {
                    var value = (field_editor_settings.wpautop) ? editor.getContent() : wp.editor.removep(editor.getContent());
                    $textarea.val(value).trigger('change');
                });
            };
            default_editor_settings.tinymce = $.extend({}, default_editor_settings.tinymce, { selector: '#' + uid, setup: editor_on_change });
            if (field_editor_settings.tinymce === false) {
                default_editor_settings.tinymce = false;
                $editor.addClass('ctwp-no-tinymce');
            }
            if (field_editor_settings.quicktags === false) {
                default_editor_settings.quicktags = false;
                $editor.addClass('ctwp-no-quicktags');
            }
            var interval = setInterval(function() {
                if ($this.is(':visible')) {
                    window.wp.editor.initialize(uid, default_editor_settings);
                    clearInterval(interval);
                }
            });
            if (field_editor_settings.media_buttons && window.ctwp_media_buttons) {
                var $editor_buttons = $editor.find('.wp-media-buttons');
                if ($editor_buttons.length) {
                    $editor_buttons.find('.ctwp-shortcode-button').data('editor-id', uid);
                } else {
                    var $media_buttons = $(window.ctwp_media_buttons);
                    $media_buttons.find('.ctwp-shortcode-button').data('editor-id', uid);
                    $editor.prepend($media_buttons);
                }
            }
        });
    };
    $.fn.ctwp_confirm = function() {
        return this.each(function() {
            $(this).on('click', function(e) {
                var confirm_text = $(this).data('confirm') || window.ctwp_vars.i18n.confirm;
                var confirm_answer = confirm(confirm_text);
                if (confirm_answer) {
                    CTWP.vars.is_confirm = true;
                    CTWP.vars.form_modified = false;
                } else {
                    e.preventDefault();
                    return false;
                }
            });
        });
    };
    $.fn.serializeObject = function() {
        var obj = {};
        $.each(this.serializeArray(), function(i, o) {
            var n = o.name,
                v = o.value;
            obj[n] = obj[n] === undefined ? v :
                $.isArray(obj[n]) ? obj[n].concat(v) : [obj[n], v];
        });
        return obj;
    };
    $.fn.ctwp_save = function() {
        return this.each(function() {
            var $this = $(this),
                $buttons = $('.ctwp-save'),
                $panel = $('.ctwp-options'),
                flooding = false,
                timeout;
            $this.on('click', function(e) {
                if (!flooding) {
                    var $text = $this.data('save'),
                        $value = $this.val();
                    $buttons.attr('value', $text);
                    if ($this.hasClass('ctwp-save-ajax')) {
                        e.preventDefault();
                        $panel.addClass('ctwp-saving');
                        $buttons.prop('disabled', true);
                        window.wp.ajax.post('ctwp_' + $panel.data('unique') + '_ajax_save', {
                                data: $('#ctwp-form').serializeJSONCTWP()
                            })
                            .done(function(response) {
                                $('.ctwp-error').remove();
                                if (Object.keys(response.errors).length) {
                                    var error_icon = '<i class="ctwp-label-error ctwp-error">!</i>';
                                    $.each(response.errors, function(key, error_message) {
                                        var $field = $('[data-depend-id="' + key + '"]'),
                                            $link = $('a[href="#tab=' + $field.closest('.ctwp-section').data('section-id') + '"]'),
                                            $tab = $link.closest('.ctwp-tab-item');
                                        $field.closest('.ctwp-fieldset').append('<p class="ctwp-error ctwp-error-text">' + error_message + '</p>');
                                        if (!$link.find('.ctwp-error').length) {
                                            $link.append(error_icon);
                                        }
                                        if (!$tab.find('.ctwp-arrow .ctwp-error').length) {
                                            $tab.find('.ctwp-arrow').append(error_icon);
                                        }
                                    });
                                }
                                $panel.removeClass('ctwp-saving');
                                $buttons.prop('disabled', false).attr('value', $value);
                                flooding = false;
                                CTWP.vars.form_modified = false;
                                CTWP.vars.$form_warning.hide();
                                clearTimeout(timeout);
                                var $result_success = $('.ctwp-form-success');
                                $result_success.empty().append(response.notice).fadeIn('fast', function() {
                                    timeout = setTimeout(function() {
                                        $result_success.fadeOut('fast');
                                    }, 1000);
                                });
                            })
                            .fail(function(response) {
                                alert(response.error);
                            });
                    } else {
                        CTWP.vars.form_modified = false;
                    }
                }
                flooding = true;
            });
        });
    };

    $.fn.ctwp_options = function() {
        return this.each(function() {
            var $this = $(this),
                $content = $this.find('.ctwp-content'),
                $form_success = $this.find('.ctwp-form-success'),
                $form_warning = $this.find('.ctwp-form-warning'),
                $save_button = $this.find('.ctwp-header .ctwp-save');
            CTWP.vars.$form_warning = $form_warning;
            if ($form_warning.length) {
                window.onbeforeunload = function() {
                    return (CTWP.vars.form_modified) ? true : undefined;
                };
                $content.on('change keypress', ':input', function() {
                    if (!CTWP.vars.form_modified) {
                        $form_success.hide();
                        $form_warning.fadeIn('fast');
                        CTWP.vars.form_modified = true;
                    }
                });
            }
            if ($form_success.hasClass('ctwp-form-show')) {
                setTimeout(function() {
                    $form_success.fadeOut('fast');
                }, 1000);
            }
            $(document).keydown(function(event) {
                if ((event.ctrlKey || event.metaKey) && event.which === 83) {
                    $save_button.trigger('click');
                    event.preventDefault();
                    return false;
                }
            });
        });
    };

    $.fn.ctwp_taxonomy = function() {
        return this.each(function() {
            var $this = $(this),
                $form = $this.parents('form');
            if ($form.attr('id') === 'addtag') {
                var $submit = $form.find('#submit'),
                    $cloned = $this.find('.ctwp-field').ctwp_clone();
                $submit.on('click', function() {
                    if (!$form.find('.form-required').hasClass('form-invalid')) {
                        $this.data('inited', false);
                        $this.empty();
                        $this.html($cloned);
                        $cloned = $cloned.ctwp_clone();
                        $this.ctwp_reload_script();
                    }
                });
            }
        });
    };

    $.fn.ctwp_shortcode = function() {
        var base = this;
        base.shortcode_parse = function(serialize, key) {
            var shortcode = '';
            $.each(serialize, function(shortcode_key, shortcode_values) {
                key = (key) ? key : shortcode_key;
                shortcode += '[' + key;
                $.each(shortcode_values, function(shortcode_tag, shortcode_value) {
                    if (shortcode_tag === 'content') {
                        shortcode += ']';
                        shortcode += shortcode_value;
                        shortcode += '[/' + key + '';
                    } else {
                        shortcode += base.shortcode_tags(shortcode_tag, shortcode_value);
                    }
                });
                shortcode += ']';
            });
            return shortcode;
        };
        base.shortcode_tags = function(shortcode_tag, shortcode_value) {
            var shortcode = '';
            if (shortcode_value !== '') {
                if (typeof shortcode_value === 'object' && !$.isArray(shortcode_value)) {
                    $.each(shortcode_value, function(sub_shortcode_tag, sub_shortcode_value) {
                        switch (sub_shortcode_tag) {
                            case 'background-image':
                                sub_shortcode_value = (sub_shortcode_value.url) ? sub_shortcode_value.url : '';
                                break;
                        }
                        if (sub_shortcode_value !== '') {
                            shortcode += ' ' + sub_shortcode_tag + '="' + sub_shortcode_value.toString() + '"';
                        }
                    });
                } else {
                    shortcode += ' ' + shortcode_tag + '="' + shortcode_value.toString() + '"';
                }
            }
            return shortcode;
        };

        base.insertAtChars = function(_this, currentValue) {
            var obj = (typeof _this[0].name !== 'undefined') ? _this[0] : _this;
            if (obj.value.length && typeof obj.selectionStart !== 'undefined') {
                obj.focus();
                return obj.value.substring(0, obj.selectionStart) + currentValue + obj.value.substring(obj.selectionEnd, obj.value.length);
            } else {
                obj.focus();
                return currentValue;
            }
        };

        base.send_to_editor = function(html, editor_id) {
            var tinymce_editor;
            if (typeof tinymce !== 'undefined') {
                tinymce_editor = tinymce.get(editor_id);
            }
            if (tinymce_editor && !tinymce_editor.isHidden()) {
                tinymce_editor.execCommand('mceInsertContent', false, html);
            } else {
                var $editor = $('#' + editor_id);
                $editor.val(base.insertAtChars($editor, html)).trigger('change');
            }
        };

        return this.each(function() {
            var $modal = $(this),
                $load = $modal.find('.ctwp-modal-load'),
                $content = $modal.find('.ctwp-modal-content'),
                $insert = $modal.find('.ctwp-modal-insert'),
                $loading = $modal.find('.ctwp-modal-loading'),
                $select = $modal.find('select'),
                modal_id = $modal.data('modal-id'),
                nonce = $modal.data('nonce'),
                editor_id,
                target_id,
                sc_key,
                sc_name,
                sc_view,
                sc_group,
                $cloned,
                $button;
            $(document).on('click', '.ctwp-shortcode-button[data-modal-id="' + modal_id + '"]', function(e) {
                e.preventDefault();
                $button = $(this);
                editor_id = $button.data('editor-id') || false;
                target_id = $button.data('target-id') || false;
                $modal.removeClass('hidden');
                if ($modal.hasClass('ctwp-shortcode-single') && sc_name === undefined) {
                    $select.trigger('change');
                }
            });

            $select.on('change', function() {
                var $option = $(this);
                var $selected = $option.find(':selected');
                sc_key = $option.val();
                sc_name = $selected.data('shortcode');
                sc_view = $selected.data('view') || 'normal';
                sc_group = $selected.data('group') || sc_name;
                $load.empty();
                if (sc_key) {
                    $loading.show();
                    window.wp.ajax.post('ctwp-get-shortcode-' + modal_id, {
                            shortcode_key: sc_key,
                            nonce: nonce
                        })
                        .done(function(response) {
                            $loading.hide();
                            var $appended = $(response.content).appendTo($load);
                            $insert.parent().removeClass('hidden');
                            $cloned = $appended.find('.ctwp--repeat-shortcode').ctwp_clone();
                            $appended.ctwp_reload_script();
                            $appended.find('.ctwp-fields').ctwp_reload_script();
                        });
                } else {
                    $insert.parent().addClass('hidden');
                }
            });
            $insert.on('click', function(e) {
                e.preventDefault();
                if ($insert.prop('disabled') || $insert.attr('disabled')) { return; }
                var shortcode = '';
                var serialize = $modal.find('.ctwp-field:not(.ctwp-depend-on)').find(':input:not(.ignore)').serializeObjectCTWP();
                switch (sc_view) {
                    case 'contents':
                        var contentsObj = (sc_name) ? serialize[sc_name] : serialize;
                        $.each(contentsObj, function(sc_key, sc_value) {
                            var sc_tag = (sc_name) ? sc_name : sc_key;
                            shortcode += '[' + sc_tag + ']' + sc_value + '[/' + sc_tag + ']';
                        });
                        break;
                    case 'group':
                        shortcode += '[' + sc_name;
                        $.each(serialize[sc_name], function(sc_key, sc_value) {
                            shortcode += base.shortcode_tags(sc_key, sc_value);
                        });
                        shortcode += ']';
                        shortcode += base.shortcode_parse(serialize[sc_group], sc_group);
                        shortcode += '[/' + sc_name + ']';
                        break;
                    case 'repeater':
                        shortcode += base.shortcode_parse(serialize[sc_group], sc_group);
                        break;
                    default:
                        shortcode += base.shortcode_parse(serialize);
                        break;
                }
                shortcode = (shortcode === '') ? '[' + sc_name + ']' : shortcode;
                if (editor_id) {
                    base.send_to_editor(shortcode, editor_id);
                } else {
                    var $textarea = (target_id) ? $(target_id) : $button.parent().find('textarea');
                    $textarea.val(base.insertAtChars($textarea, shortcode)).trigger('change');
                }
                $modal.addClass('hidden');
            });

            $modal.on('click', '.ctwp--repeat-button', function(e) {
                e.preventDefault();
                var $repeatable = $modal.find('.ctwp--repeatable');
                var $new_clone = $cloned.ctwp_clone();
                var $remove_btn = $new_clone.find('.ctwp-repeat-remove');
                var $appended = $new_clone.appendTo($repeatable);
                $new_clone.find('.ctwp-fields').ctwp_reload_script();
                CTWP.helper.name_nested_replace($modal.find('.ctwp--repeat-shortcode'), sc_group);
                $remove_btn.on('click', function() {
                    $new_clone.remove();
                    CTWP.helper.name_nested_replace($modal.find('.ctwp--repeat-shortcode'), sc_group);
                });
            });
            $modal.on('click', '.ctwp-modal-close, .ctwp-modal-overlay', function() {
                $modal.addClass('hidden');
            });
        });
    };

    if (typeof Color === 'function') {
        Color.prototype.toString = function() {
            if (this._alpha < 1) {
                return this.toCSS('rgba', this._alpha).replace(/\s+/g, '');
            }
            var hex = parseInt(this._color, 10).toString(16);
            if (this.error) { return ''; }
            if (hex.length < 6) {
                for (var i = 6 - hex.length - 1; i >= 0; i--) {
                    hex = '0' + hex;
                }
            }
            return '#' + hex;
        };
    }

    CTWP.funcs.parse_color = function(color) {
        var value = color.replace(/\s+/g, ''),
            trans = (value.indexOf('rgba') !== -1) ? parseFloat(value.replace(/^.*,(.+)\)/, '$1') * 100) : 100,
            rgba = (trans < 100) ? true : false;
        return { value: value, transparent: trans, rgba: rgba };
    };
    $.fn.ctwp_color = function() {
        return this.each(function() {
            var $input = $(this),
                picker_color = CTWP.funcs.parse_color($input.val()),
                palette_color = window.ctwp_vars.color_palette.length ? window.ctwp_vars.color_palette : true,
                $container;
            if ($input.hasClass('wp-color-picker')) {
                $input.closest('.wp-picker-container').after($input).remove();
            }
            $input.wpColorPicker({
                palettes: palette_color,
                change: function(event, ui) {
                    var ui_color_value = ui.color.toString();
                    $container.removeClass('ctwp--transparent-active');
                    $container.find('.ctwp--transparent-offset').css('background-color', ui_color_value);
                    $input.val(ui_color_value).trigger('change');
                },
                create: function() {
                    $container = $input.closest('.wp-picker-container');
                    var a8cIris = $input.data('a8cIris'),
                        $transparent_wrap = $('<div class="ctwp--transparent-wrap">' +
                            '<div class="ctwp--transparent-slider"></div>' +
                            '<div class="ctwp--transparent-offset"></div>' +
                            '<div class="ctwp--transparent-text"></div>' +
                            '<div class="ctwp--transparent-button">transparent <i class="fas fa-toggle-off"></i></div>' +
                            '</div>').appendTo($container.find('.wp-picker-holder')),
                        $transparent_slider = $transparent_wrap.find('.ctwp--transparent-slider'),
                        $transparent_text = $transparent_wrap.find('.ctwp--transparent-text'),
                        $transparent_offset = $transparent_wrap.find('.ctwp--transparent-offset'),
                        $transparent_button = $transparent_wrap.find('.ctwp--transparent-button');
                    if ($input.val() === 'transparent') {
                        $container.addClass('ctwp--transparent-active');
                    }
                    $transparent_button.on('click', function() {
                        if ($input.val() !== 'transparent') {
                            $input.val('transparent').trigger('change').removeClass('iris-error');
                            $container.addClass('ctwp--transparent-active');
                        } else {
                            $input.val(a8cIris._color.toString()).trigger('change');
                            $container.removeClass('ctwp--transparent-active');
                        }
                    });
                    $transparent_slider.slider({
                        value: picker_color.transparent,
                        step: 1,
                        min: 0,
                        max: 100,
                        slide: function(event, ui) {
                            var slide_value = parseFloat(ui.value / 100);
                            a8cIris._color._alpha = slide_value;
                            $input.wpColorPicker('color', a8cIris._color.toString());
                            $transparent_text.text((slide_value === 1 || slide_value === 0 ? '' : slide_value));
                        },
                        create: function() {
                            var slide_value = parseFloat(picker_color.transparent / 100),
                                text_value = slide_value < 1 ? slide_value : '';
                            $transparent_text.text(text_value);
                            $transparent_offset.css('background-color', picker_color.value);
                            $container.on('click', '.wp-picker-clear', function() {
                                a8cIris._color._alpha = 1;
                                $transparent_text.text('');
                                $transparent_slider.slider('option', 'value', 100);
                                $container.removeClass('ctwp--transparent-active');
                                $input.trigger('change');
                            });
                            $container.on('click', '.wp-picker-default', function() {
                                var default_color = CTWP.funcs.parse_color($input.data('default-color')),
                                    default_value = parseFloat(default_color.transparent / 100),
                                    default_text = default_value < 1 ? default_value : '';
                                a8cIris._color._alpha = default_value;
                                $transparent_text.text(default_text);
                                $transparent_slider.slider('option', 'value', default_color.transparent);
                                if (default_color.value === 'transparent') {
                                    $input.removeClass('iris-error');
                                    $container.addClass('ctwp--transparent-active');
                                }
                            });
                        }
                    });
                }
            });
        });
    };

    $.fn.ctwp_chosen = function() {
        return this.each(function() {
            var $this = $(this),
                $inited = $this.parent().find('.chosen-container'),
                is_sortable = $this.hasClass('ctwp-chosen-sortable') || false,
                is_ajax = $this.hasClass('ctwp-chosen-ajax') || false,
                is_multiple = $this.attr('multiple') || false,
                set_width = is_multiple ? '100%' : 'auto',
                set_options = $.extend({
                    allow_single_deselect: true,
                    disable_search_threshold: 10,
                    width: set_width,
                    no_results_text: window.ctwp_vars.i18n.no_results_text,
                }, $this.data('chosen-settings'));
            if ($inited.length) {
                $inited.remove();
            }
            if (is_ajax) {
                var set_ajax_options = $.extend({
                    data: {
                        type: 'post',
                        nonce: '',
                    },
                    allow_single_deselect: true,
                    disable_search_threshold: -1,
                    width: '100%',
                    min_length: 3,
                    type_delay: 500,
                    typing_text: window.ctwp_vars.i18n.typing_text,
                    searching_text: window.ctwp_vars.i18n.searching_text,
                    no_results_text: window.ctwp_vars.i18n.no_results_text,
                }, $this.data('chosen-settings'));
                $this.CTWPAjaxChosen(set_ajax_options);
            } else {
                $this.chosen(set_options);
            }
            if (is_multiple) {
                var $hidden_select = $this.parent().find('.ctwp-hide-select');
                var $hidden_value = $hidden_select.val() || [];
                $this.on('change', function(obj, result) {
                    if (result && result.selected) {
                        $hidden_select.append('<option value="' + result.selected + '" selected="selected">' + result.selected + '</option>');
                    } else if (result && result.deselected) {
                        $hidden_select.find('option[value="' + result.deselected + '"]').remove();
                    }
                    if (window.wp.customize !== undefined && $hidden_select.children().length === 0 && $hidden_select.data('customize-setting-link')) {
                        window.wp.customize.control($hidden_select.data('customize-setting-link')).setting.set('');
                    }
                    $hidden_select.trigger('change');
                });
                $this.CTWPChosenOrder($hidden_value, true);
            }
            if (is_sortable) {
                var $chosen_container = $this.parent().find('.chosen-container');
                var $chosen_choices = $chosen_container.find('.chosen-choices');
                $chosen_choices.bind('mousedown', function(event) {
                    if ($(event.target).is('span')) {
                        event.stopPropagation();
                    }
                });
                $chosen_choices.sortable({
                    items: 'li:not(.search-field)',
                    helper: 'orginal',
                    cursor: 'move',
                    placeholder: 'search-choice-placeholder',
                    start: function(e, ui) {
                        ui.placeholder.width(ui.item.innerWidth());
                        ui.placeholder.height(ui.item.innerHeight());
                    },
                    update: function(e, ui) {
                        var select_options = '';
                        var chosen_object = $this.data('chosen');
                        var $prev_select = $this.parent().find('.ctwp-hide-select');
                        $chosen_choices.find('.search-choice-close').each(function() {
                            var option_array_index = $(this).data('option-array-index');
                            $.each(chosen_object.results_data, function(index, data) {
                                if (data.array_index === option_array_index) {
                                    select_options += '<option value="' + data.value + '" selected>' + data.value + '</option>';
                                }
                            });
                        });
                        $prev_select.children().remove();
                        $prev_select.append(select_options);
                        $prev_select.trigger('change');
                    }
                });
            }
        });
    };

    $.fn.ctwp_checkbox = function() {
        return this.each(function() {
            var $this = $(this),
                $input = $this.find('.ctwp--input'),
                $checkbox = $this.find('.ctwp--checkbox');
            $checkbox.on('click', function() {
                $input.val(Number($checkbox.prop('checked'))).trigger('change');
            });
        });
    };

    $.fn.ctwp_siblings = function() {
        return this.each(function() {
            var $this = $(this),
                $siblings = $this.find('.ctwp--sibling'),
                multiple = $this.data('multiple') || false;
            $siblings.on('click', function() {
                var $sibling = $(this);
                if (multiple) {
                    if ($sibling.hasClass('ctwp--active')) {
                        $sibling.removeClass('ctwp--active');
                        $sibling.find('input').prop('checked', false).trigger('change');
                    } else {
                        $sibling.addClass('ctwp--active');
                        $sibling.find('input').prop('checked', true).trigger('change');
                    }
                } else {
                    $this.find('input').prop('checked', false);
                    $sibling.find('input').prop('checked', true).trigger('change');
                    $sibling.addClass('ctwp--active').siblings().removeClass('ctwp--active');
                }
            });
        });
    };

    $.fn.ctwp_help = function() {
        return this.each(function() {
            var $this = $(this),
                $tooltip,
                offset_left;
            $this.on({
                mouseenter: function() {
                    $tooltip = $('<div class="ctwp-tooltip"></div>').html($this.find('.ctwp-help-text').html()).appendTo('body');
                    offset_left = (CTWP.vars.is_rtl) ? ($this.offset().left + 24) : ($this.offset().left - $tooltip.outerWidth());
                    $tooltip.css({
                        top: $this.offset().top - (($tooltip.outerHeight() / 2) - 14),
                        left: offset_left,
                    });
                },
                mouseleave: function() {
                    if ($tooltip !== undefined) {
                        $tooltip.remove();
                    }
                }
            });
        });
    };

    $.fn.ctwp_customizer_refresh = function() {
        return this.each(function() {
            var $this = $(this),
                $complex = $this.closest('.ctwp-customize-complex');
            if ($complex.length) {
                var unique_id = $complex.data('unique-id');
                if (unique_id === undefined) {
                    return;
                }
                var $input = $complex.find(':input'),
                    option_id = $complex.data('option-id'),
                    obj = $input.serializeObjectCTWP(),
                    data = (!$.isEmptyObject(obj) && obj[unique_id] && obj[unique_id][option_id]) ? obj[unique_id][option_id] : '',
                    control = window.wp.customize.control(unique_id + '[' + option_id + ']');
                control.setting._value = null;
                control.setting.set(data);
            } else {
                $this.find(':input').first().trigger('change');
            }
            $(document).trigger('ctwp-customizer-refresh', $this);
        });
    };

    $.fn.ctwp_customizer_listen = function(options) {
        var settings = $.extend({
            closest: false,
        }, options);
        return this.each(function() {
            if (window.wp.customize === undefined) { return; }
            var $this = (settings.closest) ? $(this).closest('.ctwp-customize-complex') : $(this),
                $input = $this.find(':input'),
                unique_id = $this.data('unique-id'),
                option_id = $this.data('option-id');
            if (unique_id === undefined) {
                return;
            }
            $input.on('change keyup', function() {
                var obj = $this.find(':input').serializeObjectCTWP();
                var val = (!$.isEmptyObject(obj) && obj[unique_id] && obj[unique_id][option_id]) ? obj[unique_id][option_id] : '';
                window.wp.customize.control(unique_id + '[' + option_id + ']').setting.set(val);
            });
        });
    };

    $(document).on('expanded', '.control-section', function() {
        var $this = $(this);
        if ($this.hasClass('open') && !$this.data('inited')) {
            var $fields = $this.find('.ctwp-customize-field');
            var $complex = $this.find('.ctwp-customize-complex');
            if ($fields.length) {
                $this.ctwp_dependency();
                $fields.ctwp_reload_script({ dependency: false });
                $complex.ctwp_customizer_listen();
            }
            $this.data('inited', true);
        }
    });

    CTWP.vars.$window.on('resize ctwp.resize', CTWP.helper.debounce(function(event) {
        var window_width = navigator.userAgent.indexOf('AppleWebKit/') > -1 ? CTWP.vars.$window.width() : window.innerWidth;
        if (window_width <= 782 && !CTWP.vars.onloaded) {
            $('.ctwp-section').ctwp_reload_script();
            CTWP.vars.onloaded = true;
        }
    }, 200)).trigger('ctwp.resize');

    $.fn.ctwp_widgets = function() {
        return this.each(function() {
            $(document).on('widget-added widget-updated', function(event, $widget) {
                var $fields = $widget.find('.ctwp-fields');
                if ($fields.length) {
                    $fields.ctwp_reload_script();
                }
            });
            $(document).on('click', '.widget-top', function(event) {
                var $fields = $(this).parent().find('.ctwp-fields');
                if ($fields.length) {
                    $fields.ctwp_reload_script();
                }
            });
            $('.widgets-sortables, .control-section-sidebar').on('sortstop', function(event, ui) {
                ui.item.find('.ctwp-fields').ctwp_reload_script_retry();
            });
        });
    };

    $.fn.ctwp_nav_menu = function() {
        return this.each(function() {
            var $navmenu = $(this);
            $navmenu.on('click', 'a.item-edit', function() {
                $(this).closest('li.menu-item').find('.ctwp-fields').ctwp_reload_script();
            });
            $navmenu.on('sortstop', function(event, ui) {
                ui.item.find('.ctwp-fields').ctwp_reload_script_retry();
            });
        });
    };

    $.fn.ctwp_reload_script_retry = function() {
        return this.each(function() {
            var $this = $(this);
            if ($this.data('inited')) {
                $this.children('.ctwp-field-wp_editor').ctwp_field_wp_editor();
            }
        });
    };

    $.fn.ctwp_reload_script = function(options) {
        var settings = $.extend({
            dependency: true,
        }, options);
        return this.each(function() {
            var $this = $(this);
            if (!$this.data('inited')) {
                $this.children('.ctwp-field-accordion').ctwp_field_accordion();
                $this.children('.ctwp-field-backup').ctwp_field_backup();
                $this.children('.ctwp-field-background').ctwp_field_background();
                $this.children('.ctwp-field-code_editor').ctwp_field_code_editor();
                $this.children('.ctwp-field-date').ctwp_field_date();
                $this.children('.ctwp-field-fieldset').ctwp_field_fieldset();
                $this.children('.ctwp-field-gallery').ctwp_field_gallery();
                $this.children('.ctwp-field-group').ctwp_field_group();
                $this.children('.ctwp-field-icon').ctwp_field_icon();
                $this.children('.ctwp-field-link').ctwp_field_link();
                $this.children('.ctwp-field-media').ctwp_field_media();
                $this.children('.ctwp-field-map').ctwp_field_map();
                $this.children('.ctwp-field-repeater').ctwp_field_repeater();
                $this.children('.ctwp-field-slider').ctwp_field_slider();
                $this.children('.ctwp-field-sortable').ctwp_field_sortable();
                $this.children('.ctwp-field-sorter').ctwp_field_sorter();
                $this.children('.ctwp-field-spinner').ctwp_field_spinner();
                $this.children('.ctwp-field-switcher').ctwp_field_switcher();
                $this.children('.ctwp-field-tabbed').ctwp_field_tabbed();
                $this.children('.ctwp-field-typography').ctwp_field_typography();
                $this.children('.ctwp-field-upload').ctwp_field_upload();
                $this.children('.ctwp-field-wp_editor').ctwp_field_wp_editor();
                $this.children('.ctwp-field-border').find('.ctwp-color').ctwp_color();
                $this.children('.ctwp-field-background').find('.ctwp-color').ctwp_color();
                $this.children('.ctwp-field-color').find('.ctwp-color').ctwp_color();
                $this.children('.ctwp-field-color_group').find('.ctwp-color').ctwp_color();
                $this.children('.ctwp-field-link_color').find('.ctwp-color').ctwp_color();
                $this.children('.ctwp-field-typography').find('.ctwp-color').ctwp_color();
                $this.children('.ctwp-field-select').find('.ctwp-chosen').ctwp_chosen();
                $this.children('.ctwp-field-checkbox').find('.ctwp-checkbox').ctwp_checkbox();
                $this.children('.ctwp-field-button_set').find('.ctwp-siblings').ctwp_siblings();
                $this.children('.ctwp-field-image_select').find('.ctwp-siblings').ctwp_siblings();
                $this.children('.ctwp-field-palette').find('.ctwp-siblings').ctwp_siblings();
                $this.children('.ctwp-field').find('.ctwp-help').ctwp_help();
                if (settings.dependency) {
                    $this.ctwp_dependency();
                }
                $this.data('inited', true);
                $(document).trigger('ctwp-reload-script', $this);
            }
        });
    };

    $(document).ready(function() {
        $('.ctwp-save').ctwp_save();
        $('.ctwp-options').ctwp_options();
        $('.ctwp-sticky-header').ctwp_sticky();
        $('.ctwp-nav-options').ctwp_nav_options();
        $('.ctwp-nav-metabox').ctwp_nav_metabox();
        $('.ctwp-taxonomy').ctwp_taxonomy();
        $('.ctwp-page-templates').ctwp_page_templates();
        $('.ctwp-post-formats').ctwp_post_formats();
        $('.ctwp-shortcode').ctwp_shortcode();
        $('.ctwp-search').ctwp_search();
        $('.ctwp-confirm').ctwp_confirm();
        $('.ctwp-expand-all').ctwp_expand_all();
        $('.ctwp-onload').ctwp_reload_script();
        $('#widgets-editor').ctwp_widgets();
        $('#widgets-right').ctwp_widgets();
        $('#menu-to-edit').ctwp_nav_menu();
    });
})(jQuery, window, document);