if ("undefined" == typeof jQuery) throw new Error("Bootstrap's JavaScript requires jQuery");
if (+ function(a) {
        "use strict";
        var b = a.fn.jquery.split(" ")[0].split(".");
        if (b[0] < 2 && b[1] < 9 || 1 == b[0] && 9 == b[1] && b[2] < 1) throw new Error("Bootstrap's JavaScript requires jQuery version 1.9.1 or higher")
    }(jQuery), + function(a) {
        "use strict";

        function b() {
            var a = document.createElement("bootstrap"),
                b = {
                    WebkitTransition: "webkitTransitionEnd",
                    MozTransition: "transitionend",
                    OTransition: "oTransitionEnd otransitionend",
                    transition: "transitionend"
                };
            for (var c in b)
                if (void 0 !== a.style[c]) return {
                    end: b[c]
                };
            return !1
        }
        a.fn.emulateTransitionEnd = function(b) {
            var c = !1,
                d = this;
            a(this).one("bsTransitionEnd", function() {
                c = !0
            });
            var e = function() {
                c || a(d).trigger(a.support.transition.end)
            };
            return setTimeout(e, b), this
        }, a(function() {
            a.support.transition = b(), a.support.transition && (a.event.special.bsTransitionEnd = {
                bindType: a.support.transition.end,
                delegateType: a.support.transition.end,
                handle: function(b) {
                    return a(b.target).is(this) ? b.handleObj.handler.apply(this, arguments) : void 0
                }
            })
        })
    }(jQuery), + function(a) {
        "use strict";

        function b(b) {
            return this.each(function() {
                var c = a(this),
                    e = c.data("bs.alert");
                e || c.data("bs.alert", e = new d(this)), "string" == typeof b && e[b].call(c)
            })
        }
        var c = '[data-dismiss="alert"]',
            d = function(b) {
                a(b).on("click", c, this.close)
            };
        d.VERSION = "3.3.4", d.TRANSITION_DURATION = 150, d.prototype.close = function(b) {
            function c() {
                g.detach().trigger("closed.bs.alert").remove()
            }
            var e = a(this),
                f = e.attr("data-target");
            f || (f = e.attr("href"), f = f && f.replace(/.*(?=#[^\s]*$)/, ""));
            var g = a(f);
            b && b.preventDefault(), g.length || (g = e.closest(".alert")), g.trigger(b = a.Event("close.bs.alert")), b.isDefaultPrevented() || (g.removeClass("in"), a.support.transition && g.hasClass("fade") ? g.one("bsTransitionEnd", c).emulateTransitionEnd(d.TRANSITION_DURATION) : c())
        };
        var e = a.fn.alert;
        a.fn.alert = b, a.fn.alert.Constructor = d, a.fn.alert.noConflict = function() {
            return a.fn.alert = e, this
        }, a(document).on("click.bs.alert.data-api", c, d.prototype.close)
    }(jQuery), + function(a) {
        "use strict";

        function b(b) {
            return this.each(function() {
                var d = a(this),
                    e = d.data("bs.button"),
                    f = "object" == typeof b && b;
                e || d.data("bs.button", e = new c(this, f)), "toggle" == b ? e.toggle() : b && e.setState(b)
            })
        }
        var c = function(b, d) {
            this.$element = a(b), this.options = a.extend({}, c.DEFAULTS, d), this.isLoading = !1
        };
        c.VERSION = "3.3.4", c.DEFAULTS = {
            loadingText: "loading..."
        }, c.prototype.setState = function(b) {
            var c = "disabled",
                d = this.$element,
                e = d.is("input") ? "val" : "html",
                f = d.data();
            b += "Text", null == f.resetText && d.data("resetText", d[e]()), setTimeout(a.proxy(function() {
                d[e](null == f[b] ? this.options[b] : f[b]), "loadingText" == b ? (this.isLoading = !0, d.addClass(c).attr(c, c)) : this.isLoading && (this.isLoading = !1, d.removeClass(c).removeAttr(c))
            }, this), 0)
        }, c.prototype.toggle = function() {
            var a = !0,
                b = this.$element.closest('[data-toggle="buttons"]');
            if (b.length) {
                var c = this.$element.find("input");
                "radio" == c.prop("type") && (c.prop("checked") && this.$element.hasClass("active") ? a = !1 : b.find(".active").removeClass("active")), a && c.prop("checked", !this.$element.hasClass("active")).trigger("change")
            } else this.$element.attr("aria-pressed", !this.$element.hasClass("active"));
            a && this.$element.toggleClass("active")
        };
        var d = a.fn.button;
        a.fn.button = b, a.fn.button.Constructor = c, a.fn.button.noConflict = function() {
            return a.fn.button = d, this
        }, a(document).on("click.bs.button.data-api", '[data-toggle^="button"]', function(c) {
            var d = a(c.target);
            d.hasClass("btn") || (d = d.closest(".btn")), b.call(d, "toggle"), c.preventDefault()
        }).on("focus.bs.button.data-api blur.bs.button.data-api", '[data-toggle^="button"]', function(b) {
            a(b.target).closest(".btn").toggleClass("focus", /^focus(in)?$/.test(b.type))
        })
    }(jQuery), + function(a) {
        "use strict";

        function b(b) {
            return this.each(function() {
                var d = a(this),
                    e = d.data("bs.carousel"),
                    f = a.extend({}, c.DEFAULTS, d.data(), "object" == typeof b && b),
                    g = "string" == typeof b ? b : f.slide;
                e || d.data("bs.carousel", e = new c(this, f)), "number" == typeof b ? e.to(b) : g ? e[g]() : f.interval && e.pause().cycle()
            })
        }
        var c = function(b, c) {
            this.$element = a(b), this.$indicators = this.$element.find(".carousel-indicators"), this.options = c, this.paused = null, this.sliding = null, this.interval = null, this.$active = null, this.$items = null, this.options.keyboard && this.$element.on("keydown.bs.carousel", a.proxy(this.keydown, this)), "hover" == this.options.pause && !("ontouchstart" in document.documentElement) && this.$element.on("mouseenter.bs.carousel", a.proxy(this.pause, this)).on("mouseleave.bs.carousel", a.proxy(this.cycle, this))
        };
        c.VERSION = "3.3.4", c.TRANSITION_DURATION = 600, c.DEFAULTS = {
            interval: 5e3,
            pause: "hover",
            wrap: !0,
            keyboard: !0
        }, c.prototype.keydown = function(a) {
            if (!/input|textarea/i.test(a.target.tagName)) {
                switch (a.which) {
                    case 37:
                        this.prev();
                        break;
                    case 39:
                        this.next();
                        break;
                    default:
                        return
                }
                a.preventDefault()
            }
        }, c.prototype.cycle = function(b) {
            return b || (this.paused = !1), this.interval && clearInterval(this.interval), this.options.interval && !this.paused && (this.interval = setInterval(a.proxy(this.next, this), this.options.interval)), this
        }, c.prototype.getItemIndex = function(a) {
            return this.$items = a.parent().children(".item"), this.$items.index(a || this.$active)
        }, c.prototype.getItemForDirection = function(a, b) {
            var c = this.getItemIndex(b),
                d = "prev" == a && 0 === c || "next" == a && c == this.$items.length - 1;
            if (d && !this.options.wrap) return b;
            var e = "prev" == a ? -1 : 1,
                f = (c + e) % this.$items.length;
            return this.$items.eq(f)
        }, c.prototype.to = function(a) {
            var b = this,
                c = this.getItemIndex(this.$active = this.$element.find(".item.active"));
            return a > this.$items.length - 1 || 0 > a ? void 0 : this.sliding ? this.$element.one("slid.bs.carousel", function() {
                b.to(a)
            }) : c == a ? this.pause().cycle() : this.slide(a > c ? "next" : "prev", this.$items.eq(a))
        }, c.prototype.pause = function(b) {
            return b || (this.paused = !0), this.$element.find(".next, .prev").length && a.support.transition && (this.$element.trigger(a.support.transition.end), this.cycle(!0)), this.interval = clearInterval(this.interval), this
        }, c.prototype.next = function() {
            return this.sliding ? void 0 : this.slide("next")
        }, c.prototype.prev = function() {
            return this.sliding ? void 0 : this.slide("prev")
        }, c.prototype.slide = function(b, d) {
            var e = this.$element.find(".item.active"),
                f = d || this.getItemForDirection(b, e),
                g = this.interval,
                h = "next" == b ? "left" : "right",
                i = this;
            if (f.hasClass("active")) return this.sliding = !1;
            var j = f[0],
                k = a.Event("slide.bs.carousel", {
                    relatedTarget: j,
                    direction: h
                });
            if (this.$element.trigger(k), !k.isDefaultPrevented()) {
                if (this.sliding = !0, g && this.pause(), this.$indicators.length) {
                    this.$indicators.find(".active").removeClass("active");
                    var l = a(this.$indicators.children()[this.getItemIndex(f)]);
                    l && l.addClass("active")
                }
                var m = a.Event("slid.bs.carousel", {
                    relatedTarget: j,
                    direction: h
                });
                return a.support.transition && this.$element.hasClass("slide") ? (f.addClass(b), f[0].offsetWidth, e.addClass(h), f.addClass(h), e.one("bsTransitionEnd", function() {
                    f.removeClass([b, h].join(" ")).addClass("active"), e.removeClass(["active", h].join(" ")), i.sliding = !1, setTimeout(function() {
                        i.$element.trigger(m)
                    }, 0)
                }).emulateTransitionEnd(c.TRANSITION_DURATION)) : (e.removeClass("active"), f.addClass("active"), this.sliding = !1, this.$element.trigger(m)), g && this.cycle(), this
            }
        };
        var d = a.fn.carousel;
        a.fn.carousel = b, a.fn.carousel.Constructor = c, a.fn.carousel.noConflict = function() {
            return a.fn.carousel = d, this
        };
        var e = function(c) {
            var d, e = a(this),
                f = a(e.attr("data-target") || (d = e.attr("href")) && d.replace(/.*(?=#[^\s]+$)/, ""));
            if (f.hasClass("carousel")) {
                var g = a.extend({}, f.data(), e.data()),
                    h = e.attr("data-slide-to");
                h && (g.interval = !1), b.call(f, g), h && f.data("bs.carousel").to(h), c.preventDefault()
            }
        };
        a(document).on("click.bs.carousel.data-api", "[data-slide]", e).on("click.bs.carousel.data-api", "[data-slide-to]", e), a(window).on("load", function() {
            a('[data-ride="carousel"]').each(function() {
                var c = a(this);
                b.call(c, c.data())
            })
        })
    }(jQuery), + function(a) {
        "use strict";

        function b(b) {
            var c, d = b.attr("data-target") || (c = b.attr("href")) && c.replace(/.*(?=#[^\s]+$)/, "");
            return a(d)
        }

        function c(b) {
            return this.each(function() {
                var c = a(this),
                    e = c.data("bs.collapse"),
                    f = a.extend({}, d.DEFAULTS, c.data(), "object" == typeof b && b);
                !e && f.toggle && /show|hide/.test(b) && (f.toggle = !1), e || c.data("bs.collapse", e = new d(this, f)), "string" == typeof b && e[b]()
            })
        }
        var d = function(b, c) {
            this.$element = a(b), this.options = a.extend({}, d.DEFAULTS, c), this.$trigger = a('[data-toggle="collapse"][href="#' + b.id + '"],[data-toggle="collapse"][data-target="#' + b.id + '"]'), this.transitioning = null, this.options.parent ? this.$parent = this.getParent() : this.addAriaAndCollapsedClass(this.$element, this.$trigger), this.options.toggle && this.toggle()
        };
        d.VERSION = "3.3.4", d.TRANSITION_DURATION = 350, d.DEFAULTS = {
            toggle: !0
        }, d.prototype.dimension = function() {
            var a = this.$element.hasClass("width");
            return a ? "width" : "height"
        }, d.prototype.show = function() {
            if (!this.transitioning && !this.$element.hasClass("in")) {
                var b, e = this.$parent && this.$parent.children(".panel").children(".in, .collapsing");
                if (!(e && e.length && (b = e.data("bs.collapse"), b && b.transitioning))) {
                    var f = a.Event("show.bs.collapse");
                    if (this.$element.trigger(f), !f.isDefaultPrevented()) {
                        e && e.length && (c.call(e, "hide"), b || e.data("bs.collapse", null));
                        var g = this.dimension();
                        this.$element.removeClass("collapse").addClass("collapsing")[g](0).attr("aria-expanded", !0), this.$trigger.removeClass("collapsed").attr("aria-expanded", !0), this.transitioning = 1;
                        var h = function() {
                            this.$element.removeClass("collapsing").addClass("collapse in")[g](""), this.transitioning = 0, this.$element.trigger("shown.bs.collapse")
                        };
                        if (!a.support.transition) return h.call(this);
                        var i = a.camelCase(["scroll", g].join("-"));
                        this.$element.one("bsTransitionEnd", a.proxy(h, this)).emulateTransitionEnd(d.TRANSITION_DURATION)[g](this.$element[0][i])
                    }
                }
            }
        }, d.prototype.hide = function() {
            if (!this.transitioning && this.$element.hasClass("in")) {
                var b = a.Event("hide.bs.collapse");
                if (this.$element.trigger(b), !b.isDefaultPrevented()) {
                    var c = this.dimension();
                    this.$element[c](this.$element[c]())[0].offsetHeight, this.$element.addClass("collapsing").removeClass("collapse in").attr("aria-expanded", !1), this.$trigger.addClass("collapsed").attr("aria-expanded", !1), this.transitioning = 1;
                    var e = function() {
                        this.transitioning = 0, this.$element.removeClass("collapsing").addClass("collapse").trigger("hidden.bs.collapse")
                    };
                    return a.support.transition ? void this.$element[c](0).one("bsTransitionEnd", a.proxy(e, this)).emulateTransitionEnd(d.TRANSITION_DURATION) : e.call(this)
                }
            }
        }, d.prototype.toggle = function() {
            this[this.$element.hasClass("in") ? "hide" : "show"]()
        }, d.prototype.getParent = function() {
            return a(this.options.parent).find('[data-toggle="collapse"][data-parent="' + this.options.parent + '"]').each(a.proxy(function(c, d) {
                var e = a(d);
                this.addAriaAndCollapsedClass(b(e), e)
            }, this)).end()
        }, d.prototype.addAriaAndCollapsedClass = function(a, b) {
            var c = a.hasClass("in");
            a.attr("aria-expanded", c), b.toggleClass("collapsed", !c).attr("aria-expanded", c)
        };
        var e = a.fn.collapse;
        a.fn.collapse = c, a.fn.collapse.Constructor = d, a.fn.collapse.noConflict = function() {
            return a.fn.collapse = e, this
        }, a(document).on("click.bs.collapse.data-api", '[data-toggle="collapse"]', function(d) {
            var e = a(this);
            e.attr("data-target") || d.preventDefault();
            var f = b(e),
                g = f.data("bs.collapse"),
                h = g ? "toggle" : e.data();
            c.call(f, h)
        })
    }(jQuery), + function(a) {
        "use strict";

        function b(b) {
            b && 3 === b.which || (a(e).remove(), a(f).each(function() {
                var d = a(this),
                    e = c(d),
                    f = {
                        relatedTarget: this
                    };
                e.hasClass("open") && (b && "click" == b.type && /input|textarea/i.test(b.target.tagName) && a.contains(e[0], b.target) || (e.trigger(b = a.Event("hide.bs.dropdown", f)), b.isDefaultPrevented() || (d.attr("aria-expanded", "false"), e.removeClass("open").trigger("hidden.bs.dropdown", f))))
            }))
        }

        function c(b) {
            var c = b.attr("data-target");
            c || (c = b.attr("href"), c = c && /#[A-Za-z]/.test(c) && c.replace(/.*(?=#[^\s]*$)/, ""));
            var d = c && a(c);
            return d && d.length ? d : b.parent()
        }

        function d(b) {
            return this.each(function() {
                var c = a(this),
                    d = c.data("bs.dropdown");
                d || c.data("bs.dropdown", d = new g(this)), "string" == typeof b && d[b].call(c)
            })
        }
        var e = ".dropdown-backdrop",
            f = '[data-toggle="dropdown"]',
            g = function(b) {
                a(b).on("click.bs.dropdown", this.toggle)
            };
        g.VERSION = "3.3.4", g.prototype.toggle = function(d) {
            var e = a(this);
            if (!e.is(".disabled, :disabled")) {
                var f = c(e),
                    g = f.hasClass("open");
                if (b(), !g) {
                    "ontouchstart" in document.documentElement && !f.closest(".navbar-nav").length && a(document.createElement("div")).addClass("dropdown-backdrop").insertAfter(a(this)).on("click", b);
                    var h = {
                        relatedTarget: this
                    };
                    if (f.trigger(d = a.Event("show.bs.dropdown", h)), d.isDefaultPrevented()) return;
                    e.trigger("focus").attr("aria-expanded", "true"), f.toggleClass("open").trigger("shown.bs.dropdown", h)
                }
                return !1
            }
        }, g.prototype.keydown = function(b) {
            if (/(38|40|27|32)/.test(b.which) && !/input|textarea/i.test(b.target.tagName)) {
                var d = a(this);
                if (b.preventDefault(), b.stopPropagation(), !d.is(".disabled, :disabled")) {
                    var e = c(d),
                        g = e.hasClass("open");
                    if (!g && 27 != b.which || g && 27 == b.which) return 27 == b.which && e.find(f).trigger("focus"), d.trigger("click");
                    var h = " li:not(.disabled):visible a",
                        i = e.find('[role="menu"]' + h + ', [role="listbox"]' + h);
                    if (i.length) {
                        var j = i.index(b.target);
                        38 == b.which && j > 0 && j--, 40 == b.which && j < i.length - 1 && j++, ~j || (j = 0), i.eq(j).trigger("focus")
                    }
                }
            }
        };
        var h = a.fn.dropdown;
        a.fn.dropdown = d, a.fn.dropdown.Constructor = g, a.fn.dropdown.noConflict = function() {
            return a.fn.dropdown = h, this
        }, a(document).on("click.bs.dropdown.data-api", b).on("click.bs.dropdown.data-api", ".dropdown form", function(a) {
            a.stopPropagation()
        }).on("click.bs.dropdown.data-api", f, g.prototype.toggle).on("keydown.bs.dropdown.data-api", f, g.prototype.keydown).on("keydown.bs.dropdown.data-api", ".dropdown-menu", g.prototype.keydown)
    }(jQuery), + function(a) {
        "use strict";

        function b(b, d) {
            return this.each(function() {
                var e = a(this),
                    f = e.data("bs.modal"),
                    g = a.extend({}, c.DEFAULTS, e.data(), "object" == typeof b && b);
                f || e.data("bs.modal", f = new c(this, g)), "string" == typeof b ? f[b](d) : g.show && f.show(d)
            })
        }
        var c = function(b, c) {
            this.options = c, this.$body = a(document.body), this.$element = a(b), this.$dialog = this.$element.find(".modal-dialog"), this.$backdrop = null, this.isShown = null, this.originalBodyPad = null, this.scrollbarWidth = 0, this.ignoreBackdropClick = !1, this.options.remote && this.$element.find(".modal-content").load(this.options.remote, a.proxy(function() {
                this.$element.trigger("loaded.bs.modal")
            }, this))
        };
        c.VERSION = "3.3.4", c.TRANSITION_DURATION = 300, c.BACKDROP_TRANSITION_DURATION = 150, c.DEFAULTS = {
            backdrop: !0,
            keyboard: !0,
            show: !0
        }, c.prototype.toggle = function(a) {
            return this.isShown ? this.hide() : this.show(a)
        }, c.prototype.show = function(b) {
            var d = this,
                e = a.Event("show.bs.modal", {
                    relatedTarget: b
                });
            this.$element.trigger(e), this.isShown || e.isDefaultPrevented() || (this.isShown = !0, this.checkScrollbar(), this.setScrollbar(), this.$body.addClass("modal-open"), this.escape(), this.resize(), this.$element.on("click.dismiss.bs.modal", '[data-dismiss="modal"]', a.proxy(this.hide, this)), this.$dialog.on("mousedown.dismiss.bs.modal", function() {
                d.$element.one("mouseup.dismiss.bs.modal", function(b) {
                    a(b.target).is(d.$element) && (d.ignoreBackdropClick = !0)
                })
            }), this.backdrop(function() {
                var e = a.support.transition && d.$element.hasClass("fade");
                d.$element.parent().length || d.$element.appendTo(d.$body), d.$element.show().scrollTop(0), d.adjustDialog(), e && d.$element[0].offsetWidth, d.$element.addClass("in"), d.enforceFocus();
                var f = a.Event("shown.bs.modal", {
                    relatedTarget: b
                });
                e ? d.$dialog.one("bsTransitionEnd", function() {
                    d.$element.trigger("focus").trigger(f)
                }).emulateTransitionEnd(c.TRANSITION_DURATION) : d.$element.trigger("focus").trigger(f)
            }))
        }, c.prototype.hide = function(b) {
            b && b.preventDefault(), b = a.Event("hide.bs.modal"), this.$element.trigger(b), this.isShown && !b.isDefaultPrevented() && (this.isShown = !1, this.escape(), this.resize(), a(document).off("focusin.bs.modal"), this.$element.removeClass("in").off("click.dismiss.bs.modal").off("mouseup.dismiss.bs.modal"), this.$dialog.off("mousedown.dismiss.bs.modal"), a.support.transition && this.$element.hasClass("fade") ? this.$element.one("bsTransitionEnd", a.proxy(this.hideModal, this)).emulateTransitionEnd(c.TRANSITION_DURATION) : this.hideModal())
        }, c.prototype.enforceFocus = function() {
            a(document).off("focusin.bs.modal").on("focusin.bs.modal", a.proxy(function(a) {
                this.$element[0] === a.target || this.$element.has(a.target).length || this.$element.trigger("focus")
            }, this))
        }, c.prototype.escape = function() {
            this.isShown && this.options.keyboard ? this.$element.on("keydown.dismiss.bs.modal", a.proxy(function(a) {
                27 == a.which && this.hide()
            }, this)) : this.isShown || this.$element.off("keydown.dismiss.bs.modal")
        }, c.prototype.resize = function() {
            this.isShown ? a(window).on("resize.bs.modal", a.proxy(this.handleUpdate, this)) : a(window).off("resize.bs.modal")
        }, c.prototype.hideModal = function() {
            var a = this;
            this.$element.hide(), this.backdrop(function() {
                a.$body.removeClass("modal-open"), a.resetAdjustments(), a.resetScrollbar(), a.$element.trigger("hidden.bs.modal")
            })
        }, c.prototype.removeBackdrop = function() {
            this.$backdrop && this.$backdrop.remove(), this.$backdrop = null
        }, c.prototype.backdrop = function(b) {
            var d = this,
                e = this.$element.hasClass("fade") ? "fade" : "";
            if (this.isShown && this.options.backdrop) {
                var f = a.support.transition && e;
                if (this.$backdrop = a(document.createElement("div")).addClass("modal-backdrop " + e).appendTo(this.$body), this.$element.on("click.dismiss.bs.modal", a.proxy(function(a) {
                        return this.ignoreBackdropClick ? void(this.ignoreBackdropClick = !1) : void(a.target === a.currentTarget && ("static" == this.options.backdrop ? this.$element[0].focus() : this.hide()))
                    }, this)), f && this.$backdrop[0].offsetWidth, this.$backdrop.addClass("in"), !b) return;
                f ? this.$backdrop.one("bsTransitionEnd", b).emulateTransitionEnd(c.BACKDROP_TRANSITION_DURATION) : b()
            } else if (!this.isShown && this.$backdrop) {
                this.$backdrop.removeClass("in");
                var g = function() {
                    d.removeBackdrop(), b && b()
                };
                a.support.transition && this.$element.hasClass("fade") ? this.$backdrop.one("bsTransitionEnd", g).emulateTransitionEnd(c.BACKDROP_TRANSITION_DURATION) : g()
            } else b && b()
        }, c.prototype.handleUpdate = function() {
            this.adjustDialog()
        }, c.prototype.adjustDialog = function() {
            var a = this.$element[0].scrollHeight > document.documentElement.clientHeight;
            this.$element.css({
                paddingLeft: !this.bodyIsOverflowing && a ? this.scrollbarWidth : "",
                paddingRight: this.bodyIsOverflowing && !a ? this.scrollbarWidth : ""
            })
        }, c.prototype.resetAdjustments = function() {
            this.$element.css({
                paddingLeft: "",
                paddingRight: ""
            })
        }, c.prototype.checkScrollbar = function() {
            var a = window.innerWidth;
            if (!a) {
                var b = document.documentElement.getBoundingClientRect();
                a = b.right - Math.abs(b.left)
            }
            this.bodyIsOverflowing = document.body.clientWidth < a, this.scrollbarWidth = this.measureScrollbar()
        }, c.prototype.setScrollbar = function() {
            var a = parseInt(this.$body.css("padding-right") || 0, 10);
            this.originalBodyPad = document.body.style.paddingRight || "", this.bodyIsOverflowing && this.$body.css("padding-right", a + this.scrollbarWidth)
        }, c.prototype.resetScrollbar = function() {
            this.$body.css("padding-right", this.originalBodyPad)
        }, c.prototype.measureScrollbar = function() {
            var a = document.createElement("div");
            a.className = "modal-scrollbar-measure", this.$body.append(a);
            var b = a.offsetWidth - a.clientWidth;
            return this.$body[0].removeChild(a), b
        };
        var d = a.fn.modal;
        a.fn.modal = b, a.fn.modal.Constructor = c, a.fn.modal.noConflict = function() {
            return a.fn.modal = d, this
        }, a(document).on("click.bs.modal.data-api", '[data-toggle="modal"]', function(c) {
            var d = a(this),
                e = d.attr("href"),
                f = a(d.attr("data-target") || e && e.replace(/.*(?=#[^\s]+$)/, "")),
                g = f.data("bs.modal") ? "toggle" : a.extend({
                    remote: !/#/.test(e) && e
                }, f.data(), d.data());
            d.is("a") && c.preventDefault(), f.one("show.bs.modal", function(a) {
                a.isDefaultPrevented() || f.one("hidden.bs.modal", function() {
                    d.is(":visible") && d.trigger("focus")
                })
            }), b.call(f, g, this)
        })
    }(jQuery), + function(a) {
        "use strict";

        function b(b) {
            return this.each(function() {
                var d = a(this),
                    e = d.data("bs.tooltip"),
                    f = "object" == typeof b && b;
                (e || !/destroy|hide/.test(b)) && (e || d.data("bs.tooltip", e = new c(this, f)), "string" == typeof b && e[b]())
            })
        }
        var c = function(a, b) {
            this.type = null, this.options = null, this.enabled = null, this.timeout = null, this.hoverState = null, this.$element = null, this.init("tooltip", a, b)
        };
        c.VERSION = "3.3.4", c.TRANSITION_DURATION = 150, c.DEFAULTS = {
            animation: !0,
            placement: "top",
            selector: !1,
            template: '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',
            trigger: "hover focus",
            title: "",
            delay: 0,
            html: !1,
            container: !1,
            viewport: {
                selector: "body",
                padding: 0
            }
        }, c.prototype.init = function(b, c, d) {
            if (this.enabled = !0, this.type = b, this.$element = a(c), this.options = this.getOptions(d), this.$viewport = this.options.viewport && a(this.options.viewport.selector || this.options.viewport), this.$element[0] instanceof document.constructor && !this.options.selector) throw new Error("`selector` option must be specified when initializing " + this.type + " on the window.document object!");
            for (var e = this.options.trigger.split(" "), f = e.length; f--;) {
                var g = e[f];
                if ("click" == g) this.$element.on("click." + this.type, this.options.selector, a.proxy(this.toggle, this));
                else if ("manual" != g) {
                    var h = "hover" == g ? "mouseenter" : "focusin",
                        i = "hover" == g ? "mouseleave" : "focusout";
                    this.$element.on(h + "." + this.type, this.options.selector, a.proxy(this.enter, this)), this.$element.on(i + "." + this.type, this.options.selector, a.proxy(this.leave, this))
                }
            }
            this.options.selector ? this._options = a.extend({}, this.options, {
                trigger: "manual",
                selector: ""
            }) : this.fixTitle()
        }, c.prototype.getDefaults = function() {
            return c.DEFAULTS
        }, c.prototype.getOptions = function(b) {
            return b = a.extend({}, this.getDefaults(), this.$element.data(), b), b.delay && "number" == typeof b.delay && (b.delay = {
                show: b.delay,
                hide: b.delay
            }), b
        }, c.prototype.getDelegateOptions = function() {
            var b = {},
                c = this.getDefaults();
            return this._options && a.each(this._options, function(a, d) {
                c[a] != d && (b[a] = d)
            }), b
        }, c.prototype.enter = function(b) {
            var c = b instanceof this.constructor ? b : a(b.currentTarget).data("bs." + this.type);
            return c && c.$tip && c.$tip.is(":visible") ? void(c.hoverState = "in") : (c || (c = new this.constructor(b.currentTarget, this.getDelegateOptions()), a(b.currentTarget).data("bs." + this.type, c)), clearTimeout(c.timeout), c.hoverState = "in", c.options.delay && c.options.delay.show ? void(c.timeout = setTimeout(function() {
                "in" == c.hoverState && c.show()
            }, c.options.delay.show)) : c.show())
        }, c.prototype.leave = function(b) {
            var c = b instanceof this.constructor ? b : a(b.currentTarget).data("bs." + this.type);
            return c || (c = new this.constructor(b.currentTarget, this.getDelegateOptions()), a(b.currentTarget).data("bs." + this.type, c)), clearTimeout(c.timeout), c.hoverState = "out", c.options.delay && c.options.delay.hide ? void(c.timeout = setTimeout(function() {
                "out" == c.hoverState && c.hide()
            }, c.options.delay.hide)) : c.hide()
        }, c.prototype.show = function() {
            var b = a.Event("show.bs." + this.type);
            if (this.hasContent() && this.enabled) {
                this.$element.trigger(b);
                var d = a.contains(this.$element[0].ownerDocument.documentElement, this.$element[0]);
                if (b.isDefaultPrevented() || !d) return;
                var e = this,
                    f = this.tip(),
                    g = this.getUID(this.type);
                this.setContent(), f.attr("id", g), this.$element.attr("aria-describedby", g), this.options.animation && f.addClass("fade");
                var h = "function" == typeof this.options.placement ? this.options.placement.call(this, f[0], this.$element[0]) : this.options.placement,
                    i = /\s?auto?\s?/i,
                    j = i.test(h);
                j && (h = h.replace(i, "") || "top"), f.detach().css({
                    top: 0,
                    left: 0,
                    display: "block"
                }).addClass(h).data("bs." + this.type, this), this.options.container ? f.appendTo(this.options.container) : f.insertAfter(this.$element);
                var k = this.getPosition(),
                    l = f[0].offsetWidth,
                    m = f[0].offsetHeight;
                if (j) {
                    var n = h,
                        o = this.options.container ? a(this.options.container) : this.$element.parent(),
                        p = this.getPosition(o);
                    h = "bottom" == h && k.bottom + m > p.bottom ? "top" : "top" == h && k.top - m < p.top ? "bottom" : "right" == h && k.right + l > p.width ? "left" : "left" == h && k.left - l < p.left ? "right" : h, f.removeClass(n).addClass(h)
                }
                var q = this.getCalculatedOffset(h, k, l, m);
                this.applyPlacement(q, h);
                var r = function() {
                    var a = e.hoverState;
                    e.$element.trigger("shown.bs." + e.type), e.hoverState = null, "out" == a && e.leave(e)
                };
                a.support.transition && this.$tip.hasClass("fade") ? f.one("bsTransitionEnd", r).emulateTransitionEnd(c.TRANSITION_DURATION) : r()
            }
        }, c.prototype.applyPlacement = function(b, c) {
            var d = this.tip(),
                e = d[0].offsetWidth,
                f = d[0].offsetHeight,
                g = parseInt(d.css("margin-top"), 10),
                h = parseInt(d.css("margin-left"), 10);
            isNaN(g) && (g = 0), isNaN(h) && (h = 0), b.top = b.top + g, b.left = b.left + h, a.offset.setOffset(d[0], a.extend({
                using: function(a) {
                    d.css({
                        top: Math.round(a.top),
                        left: Math.round(a.left)
                    })
                }
            }, b), 0), d.addClass("in");
            var i = d[0].offsetWidth,
                j = d[0].offsetHeight;
            "top" == c && j != f && (b.top = b.top + f - j);
            var k = this.getViewportAdjustedDelta(c, b, i, j);
            k.left ? b.left += k.left : b.top += k.top;
            var l = /top|bottom/.test(c),
                m = l ? 2 * k.left - e + i : 2 * k.top - f + j,
                n = l ? "offsetWidth" : "offsetHeight";
            d.offset(b), this.replaceArrow(m, d[0][n], l)
        }, c.prototype.replaceArrow = function(a, b, c) {
            this.arrow().css(c ? "left" : "top", 50 * (1 - a / b) + "%").css(c ? "top" : "left", "")
        }, c.prototype.setContent = function() {
            var a = this.tip(),
                b = this.getTitle();
            a.find(".tooltip-inner")[this.options.html ? "html" : "text"](b), a.removeClass("fade in top bottom left right")
        }, c.prototype.hide = function(b) {
            function d() {
                "in" != e.hoverState && f.detach(), e.$element.removeAttr("aria-describedby").trigger("hidden.bs." + e.type), b && b()
            }
            var e = this,
                f = a(this.$tip),
                g = a.Event("hide.bs." + this.type);
            return this.$element.trigger(g), g.isDefaultPrevented() ? void 0 : (f.removeClass("in"), a.support.transition && f.hasClass("fade") ? f.one("bsTransitionEnd", d).emulateTransitionEnd(c.TRANSITION_DURATION) : d(), this.hoverState = null, this)
        }, c.prototype.fixTitle = function() {
            var a = this.$element;
            (a.attr("title") || "string" != typeof a.attr("data-original-title")) && a.attr("data-original-title", a.attr("title") || "").attr("title", "")
        }, c.prototype.hasContent = function() {
            return this.getTitle()
        }, c.prototype.getPosition = function(b) {
            b = b || this.$element;
            var c = b[0],
                d = "BODY" == c.tagName,
                e = c.getBoundingClientRect();
            null == e.width && (e = a.extend({}, e, {
                width: e.right - e.left,
                height: e.bottom - e.top
            }));
            var f = d ? {
                    top: 0,
                    left: 0
                } : b.offset(),
                g = {
                    scroll: d ? document.documentElement.scrollTop || document.body.scrollTop : b.scrollTop()
                },
                h = d ? {
                    width: a(window).width(),
                    height: a(window).height()
                } : null;
            return a.extend({}, e, g, h, f)
        }, c.prototype.getCalculatedOffset = function(a, b, c, d) {
            return "bottom" == a ? {
                top: b.top + b.height,
                left: b.left + b.width / 2 - c / 2
            } : "top" == a ? {
                top: b.top - d,
                left: b.left + b.width / 2 - c / 2
            } : "left" == a ? {
                top: b.top + b.height / 2 - d / 2,
                left: b.left - c
            } : {
                top: b.top + b.height / 2 - d / 2,
                left: b.left + b.width
            }
        }, c.prototype.getViewportAdjustedDelta = function(a, b, c, d) {
            var e = {
                top: 0,
                left: 0
            };
            if (!this.$viewport) return e;
            var f = this.options.viewport && this.options.viewport.padding || 0,
                g = this.getPosition(this.$viewport);
            if (/right|left/.test(a)) {
                var h = b.top - f - g.scroll,
                    i = b.top + f - g.scroll + d;
                h < g.top ? e.top = g.top - h : i > g.top + g.height && (e.top = g.top + g.height - i)
            } else {
                var j = b.left - f,
                    k = b.left + f + c;
                j < g.left ? e.left = g.left - j : k > g.width && (e.left = g.left + g.width - k)
            }
            return e
        }, c.prototype.getTitle = function() {
            var a, b = this.$element,
                c = this.options;
            return a = b.attr("data-original-title") || ("function" == typeof c.title ? c.title.call(b[0]) : c.title)
        }, c.prototype.getUID = function(a) {
            do a += ~~(1e6 * Math.random()); while (document.getElementById(a));
            return a
        }, c.prototype.tip = function() {
            return this.$tip = this.$tip || a(this.options.template)
        }, c.prototype.arrow = function() {
            return this.$arrow = this.$arrow || this.tip().find(".tooltip-arrow")
        }, c.prototype.enable = function() {
            this.enabled = !0
        }, c.prototype.disable = function() {
            this.enabled = !1
        }, c.prototype.toggleEnabled = function() {
            this.enabled = !this.enabled
        }, c.prototype.toggle = function(b) {
            var c = this;
            b && (c = a(b.currentTarget).data("bs." + this.type), c || (c = new this.constructor(b.currentTarget, this.getDelegateOptions()), a(b.currentTarget).data("bs." + this.type, c))), c.tip().hasClass("in") ? c.leave(c) : c.enter(c)
        }, c.prototype.destroy = function() {
            var a = this;
            clearTimeout(this.timeout), this.hide(function() {
                a.$element.off("." + a.type).removeData("bs." + a.type)
            })
        };
        var d = a.fn.tooltip;
        a.fn.tooltip = b, a.fn.tooltip.Constructor = c, a.fn.tooltip.noConflict = function() {
            return a.fn.tooltip = d, this
        }
    }(jQuery), + function(a) {
        "use strict";

        function b(b) {
            return this.each(function() {
                var d = a(this),
                    e = d.data("bs.popover"),
                    f = "object" == typeof b && b;
                (e || !/destroy|hide/.test(b)) && (e || d.data("bs.popover", e = new c(this, f)), "string" == typeof b && e[b]())
            })
        }
        var c = function(a, b) {
            this.init("popover", a, b)
        };
        if (!a.fn.tooltip) throw new Error("Popover requires tooltip.js");
        c.VERSION = "3.3.4", c.DEFAULTS = a.extend({}, a.fn.tooltip.Constructor.DEFAULTS, {
            placement: "right",
            trigger: "click",
            content: "",
            template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
        }), c.prototype = a.extend({}, a.fn.tooltip.Constructor.prototype), c.prototype.constructor = c, c.prototype.getDefaults = function() {
            return c.DEFAULTS
        }, c.prototype.setContent = function() {
            var a = this.tip(),
                b = this.getTitle(),
                c = this.getContent();
            a.find(".popover-title")[this.options.html ? "html" : "text"](b), a.find(".popover-content").children().detach().end()[this.options.html ? "string" == typeof c ? "html" : "append" : "text"](c), a.removeClass("fade top bottom left right in"), a.find(".popover-title").html() || a.find(".popover-title").hide()
        }, c.prototype.hasContent = function() {
            return this.getTitle() || this.getContent()
        }, c.prototype.getContent = function() {
            var a = this.$element,
                b = this.options;
            return a.attr("data-content") || ("function" == typeof b.content ? b.content.call(a[0]) : b.content)
        }, c.prototype.arrow = function() {
            return this.$arrow = this.$arrow || this.tip().find(".arrow")
        };
        var d = a.fn.popover;
        a.fn.popover = b, a.fn.popover.Constructor = c, a.fn.popover.noConflict = function() {
            return a.fn.popover = d, this
        }
    }(jQuery), + function(a) {
        "use strict";

        function b(c, d) {
            this.$body = a(document.body), this.$scrollElement = a(a(c).is(document.body) ? window : c), this.options = a.extend({}, b.DEFAULTS, d), this.selector = (this.options.target || "") + " .nav li > a", this.offsets = [], this.targets = [], this.activeTarget = null, this.scrollHeight = 0, this.$scrollElement.on("scroll.bs.scrollspy", a.proxy(this.process, this)), this.refresh(), this.process()
        }

        function c(c) {
            return this.each(function() {
                var d = a(this),
                    e = d.data("bs.scrollspy"),
                    f = "object" == typeof c && c;
                e || d.data("bs.scrollspy", e = new b(this, f)), "string" == typeof c && e[c]()
            })
        }
        b.VERSION = "3.3.4", b.DEFAULTS = {
            offset: 10
        }, b.prototype.getScrollHeight = function() {
            return this.$scrollElement[0].scrollHeight || Math.max(this.$body[0].scrollHeight, document.documentElement.scrollHeight)
        }, b.prototype.refresh = function() {
            var b = this,
                c = "offset",
                d = 0;
            this.offsets = [], this.targets = [], this.scrollHeight = this.getScrollHeight(), a.isWindow(this.$scrollElement[0]) || (c = "position", d = this.$scrollElement.scrollTop()), this.$body.find(this.selector).map(function() {
                var b = a(this),
                    e = b.data("target") || b.attr("href"),
                    f = /^#./.test(e) && a(e);
                return f && f.length && f.is(":visible") && [
                    [f[c]().top + d, e]
                ] || null
            }).sort(function(a, b) {
                return a[0] - b[0]
            }).each(function() {
                b.offsets.push(this[0]), b.targets.push(this[1])
            })
        }, b.prototype.process = function() {
            var a, b = this.$scrollElement.scrollTop() + this.options.offset,
                c = this.getScrollHeight(),
                d = this.options.offset + c - this.$scrollElement.height(),
                e = this.offsets,
                f = this.targets,
                g = this.activeTarget;
            if (this.scrollHeight != c && this.refresh(), b >= d) return g != (a = f[f.length - 1]) && this.activate(a);
            if (g && b < e[0]) return this.activeTarget = null, this.clear();
            for (a = e.length; a--;) g != f[a] && b >= e[a] && (void 0 === e[a + 1] || b < e[a + 1]) && this.activate(f[a])
        }, b.prototype.activate = function(b) {
            this.activeTarget = b, this.clear();
            var c = this.selector + '[data-target="' + b + '"],' + this.selector + '[href="' + b + '"]',
                d = a(c).parents("li").addClass("active");
            d.parent(".dropdown-menu").length && (d = d.closest("li.dropdown").addClass("active")), d.trigger("activate.bs.scrollspy")
        }, b.prototype.clear = function() {
            a(this.selector).parentsUntil(this.options.target, ".active").removeClass("active")
        };
        var d = a.fn.scrollspy;
        a.fn.scrollspy = c, a.fn.scrollspy.Constructor = b, a.fn.scrollspy.noConflict = function() {
            return a.fn.scrollspy = d, this
        }, a(window).on("load.bs.scrollspy.data-api", function() {
            a('[data-spy="scroll"]').each(function() {
                var b = a(this);
                c.call(b, b.data())
            })
        })
    }(jQuery), + function(a) {
        "use strict";

        function b(b) {
            return this.each(function() {
                var d = a(this),
                    e = d.data("bs.tab");
                e || d.data("bs.tab", e = new c(this)), "string" == typeof b && e[b]()
            })
        }
        var c = function(b) {
            this.element = a(b)
        };
        c.VERSION = "3.3.4", c.TRANSITION_DURATION = 150, c.prototype.show = function() {
            var b = this.element,
                c = b.closest("ul:not(.dropdown-menu)"),
                d = b.data("target");
            if (d || (d = b.attr("href"), d = d && d.replace(/.*(?=#[^\s]*$)/, "")), !b.parent("li").hasClass("active")) {
                var e = c.find(".active:last a"),
                    f = a.Event("hide.bs.tab", {
                        relatedTarget: b[0]
                    }),
                    g = a.Event("show.bs.tab", {
                        relatedTarget: e[0]
                    });
                if (e.trigger(f), b.trigger(g), !g.isDefaultPrevented() && !f.isDefaultPrevented()) {
                    var h = a(d);
                    this.activate(b.closest("li"), c), this.activate(h, h.parent(), function() {
                        e.trigger({
                            type: "hidden.bs.tab",
                            relatedTarget: b[0]
                        }), b.trigger({
                            type: "shown.bs.tab",
                            relatedTarget: e[0]
                        })
                    })
                }
            }
        }, c.prototype.activate = function(b, d, e) {
            function f() {
                g.removeClass("active").find("> .dropdown-menu > .active").removeClass("active").end().find('[data-toggle="tab"]').attr("aria-expanded", !1), b.addClass("active").find('[data-toggle="tab"]').attr("aria-expanded", !0), h ? (b[0].offsetWidth, b.addClass("in")) : b.removeClass("fade"), b.parent(".dropdown-menu").length && b.closest("li.dropdown").addClass("active").end().find('[data-toggle="tab"]').attr("aria-expanded", !0), e && e()
            }
            var g = d.find("> .active"),
                h = e && a.support.transition && (g.length && g.hasClass("fade") || !!d.find("> .fade").length);
            g.length && h ? g.one("bsTransitionEnd", f).emulateTransitionEnd(c.TRANSITION_DURATION) : f(), g.removeClass("in")
        };
        var d = a.fn.tab;
        a.fn.tab = b, a.fn.tab.Constructor = c, a.fn.tab.noConflict = function() {
            return a.fn.tab = d, this
        };
        var e = function(c) {
            c.preventDefault(), b.call(a(this), "show")
        };
        a(document).on("click.bs.tab.data-api", '[data-toggle="tab"]', e).on("click.bs.tab.data-api", '[data-toggle="pill"]', e)
    }(jQuery), + function(a) {
        "use strict";

        function b(b) {
            return this.each(function() {
                var d = a(this),
                    e = d.data("bs.affix"),
                    f = "object" == typeof b && b;
                e || d.data("bs.affix", e = new c(this, f)), "string" == typeof b && e[b]()
            })
        }
        var c = function(b, d) {
            this.options = a.extend({}, c.DEFAULTS, d), this.$target = a(this.options.target).on("scroll.bs.affix.data-api", a.proxy(this.checkPosition, this)).on("click.bs.affix.data-api", a.proxy(this.checkPositionWithEventLoop, this)), this.$element = a(b), this.affixed = null, this.unpin = null, this.pinnedOffset = null, this.checkPosition()
        };
        c.VERSION = "3.3.4", c.RESET = "affix affix-top affix-bottom", c.DEFAULTS = {
            offset: 0,
            target: window
        }, c.prototype.getState = function(a, b, c, d) {
            var e = this.$target.scrollTop(),
                f = this.$element.offset(),
                g = this.$target.height();
            if (null != c && "top" == this.affixed) return c > e ? "top" : !1;
            if ("bottom" == this.affixed) return null != c ? e + this.unpin <= f.top ? !1 : "bottom" : a - d >= e + g ? !1 : "bottom";
            var h = null == this.affixed,
                i = h ? e : f.top,
                j = h ? g : b;
            return null != c && c >= e ? "top" : null != d && i + j >= a - d ? "bottom" : !1
        }, c.prototype.getPinnedOffset = function() {
            if (this.pinnedOffset) return this.pinnedOffset;
            this.$element.removeClass(c.RESET).addClass("affix");
            var a = this.$target.scrollTop(),
                b = this.$element.offset();
            return this.pinnedOffset = b.top - a
        }, c.prototype.checkPositionWithEventLoop = function() {
            setTimeout(a.proxy(this.checkPosition, this), 1)
        }, c.prototype.checkPosition = function() {
            if (this.$element.is(":visible")) {
                var b = this.$element.height(),
                    d = this.options.offset,
                    e = d.top,
                    f = d.bottom,
                    g = a(document.body).height();
                "object" != typeof d && (f = e = d), "function" == typeof e && (e = d.top(this.$element)), "function" == typeof f && (f = d.bottom(this.$element));
                var h = this.getState(g, b, e, f);
                if (this.affixed != h) {
                    null != this.unpin && this.$element.css("top", "");
                    var i = "affix" + (h ? "-" + h : ""),
                        j = a.Event(i + ".bs.affix");
                    if (this.$element.trigger(j), j.isDefaultPrevented()) return;
                    this.affixed = h, this.unpin = "bottom" == h ? this.getPinnedOffset() : null, this.$element.removeClass(c.RESET).addClass(i).trigger(i.replace("affix", "affixed") + ".bs.affix")
                }
                "bottom" == h && this.$element.offset({
                    top: g - b - f
                })
            }
        };
        var d = a.fn.affix;
        a.fn.affix = b, a.fn.affix.Constructor = c, a.fn.affix.noConflict = function() {
            return a.fn.affix = d, this
        }, a(window).on("load", function() {
            a('[data-spy="affix"]').each(function() {
                var c = a(this),
                    d = c.data();
                d.offset = d.offset || {}, null != d.offsetBottom && (d.offset.bottom = d.offsetBottom), null != d.offsetTop && (d.offset.top = d.offsetTop), b.call(c, d)
            })
        })
    }(jQuery), window.Modernizr = function(a, b, c) {
        function d(a) {
            o.cssText = a
        }

        function e(a, b) {
            return typeof a === b
        }
        var f, g, h, i = "2.8.2",
            j = {},
            k = !0,
            l = b.documentElement,
            m = "modernizr",
            n = b.createElement(m),
            o = n.style,
            p = ({}.toString, " -webkit- -moz- -o- -ms- ".split(" ")),
            q = {},
            r = [],
            s = r.slice,
            t = function(a, c, d, e) {
                var f, g, h, i, j = b.createElement("div"),
                    k = b.body,
                    n = k || b.createElement("body");
                if (parseInt(d, 10))
                    for (; d--;) h = b.createElement("div"), h.id = e ? e[d] : m + (d + 1), j.appendChild(h);
                return f = ["&#173;", '<style id="s', m, '">', a, "</style>"].join(""), j.id = m, (k ? j : n).innerHTML += f, n.appendChild(j), k || (n.style.background = "", n.style.overflow = "hidden", i = l.style.overflow, l.style.overflow = "hidden", l.appendChild(n)), g = c(j, a), k ? j.parentNode.removeChild(j) : (n.parentNode.removeChild(n), l.style.overflow = i), !!g
            },
            u = {}.hasOwnProperty;
        h = e(u, "undefined") || e(u.call, "undefined") ? function(a, b) {
            return b in a && e(a.constructor.prototype[b], "undefined")
        } : function(a, b) {
            return u.call(a, b)
        }, Function.prototype.bind || (Function.prototype.bind = function(a) {
            var b = this;
            if ("function" != typeof b) throw new TypeError;
            var c = s.call(arguments, 1),
                d = function() {
                    if (this instanceof d) {
                        var e = function() {};
                        e.prototype = b.prototype;
                        var f = new e,
                            g = b.apply(f, c.concat(s.call(arguments)));
                        return Object(g) === g ? g : f
                    }
                    return b.apply(a, c.concat(s.call(arguments)))
                };
            return d
        }), q.touch = function() {
            var c;
            return "ontouchstart" in a || a.DocumentTouch && b instanceof DocumentTouch ? c = !0 : t(["@media (", p.join("touch-enabled),("), m, ")", "{#modernizr{top:9px;position:absolute}}"].join(""), function(a) {
                c = 9 === a.offsetTop
            }), c
        };
        for (var v in q) h(q, v) && (g = v.toLowerCase(), j[g] = q[v](), r.push((j[g] ? "" : "no-") + g));
        return j.addTest = function(a, b) {
                if ("object" == typeof a)
                    for (var d in a) h(a, d) && j.addTest(d, a[d]);
                else {
                    if (a = a.toLowerCase(), j[a] !== c) return j;
                    b = "function" == typeof b ? b() : b, "undefined" != typeof k && k && (l.className += " " + (b ? "" : "no-") + a), j[a] = b
                }
                return j
            }, d(""), n = f = null,
            function(a, b) {
                function c(a, b) {
                    var c = a.createElement("p"),
                        d = a.getElementsByTagName("head")[0] || a.documentElement;
                    return c.innerHTML = "x<style>" + b + "</style>", d.insertBefore(c.lastChild, d.firstChild)
                }

                function d() {
                    var a = s.elements;
                    return "string" == typeof a ? a.split(" ") : a
                }

                function e(a) {
                    var b = r[a[p]];
                    return b || (b = {}, q++, a[p] = q, r[q] = b), b
                }

                function f(a, c, d) {
                    if (c || (c = b), k) return c.createElement(a);
                    d || (d = e(c));
                    var f;
                    return f = d.cache[a] ? d.cache[a].cloneNode() : o.test(a) ? (d.cache[a] = d.createElem(a)).cloneNode() : d.createElem(a), !f.canHaveChildren || n.test(a) || f.tagUrn ? f : d.frag.appendChild(f)
                }

                function g(a, c) {
                    if (a || (a = b), k) return a.createDocumentFragment();
                    c = c || e(a);
                    for (var f = c.frag.cloneNode(), g = 0, h = d(), i = h.length; i > g; g++) f.createElement(h[g]);
                    return f
                }

                function h(a, b) {
                    b.cache || (b.cache = {}, b.createElem = a.createElement, b.createFrag = a.createDocumentFragment, b.frag = b.createFrag()), a.createElement = function(c) {
                        return s.shivMethods ? f(c, a, b) : b.createElem(c)
                    }, a.createDocumentFragment = Function("h,f", "return function(){var n=f.cloneNode(),c=n.createElement;h.shivMethods&&(" + d().join().replace(/[\w\-]+/g, function(a) {
                        return b.createElem(a), b.frag.createElement(a), 'c("' + a + '")'
                    }) + ");return n}")(s, b.frag)
                }

                function i(a) {
                    a || (a = b);
                    var d = e(a);
                    return !s.shivCSS || j || d.hasCSS || (d.hasCSS = !!c(a, "article,aside,dialog,figcaption,figure,footer,header,hgroup,main,nav,section{display:block}mark{background:#FF0;color:#000}template{display:none}")), k || h(a, d), a
                }
                var j, k, l = "3.7.0",
                    m = a.html5 || {},
                    n = /^<|^(?:button|map|select|textarea|object|iframe|option|optgroup)$/i,
                    o = /^(?:a|b|code|div|fieldset|h1|h2|h3|h4|h5|h6|i|label|li|ol|p|q|span|strong|style|table|tbody|td|th|tr|ul)$/i,
                    p = "_html5shiv",
                    q = 0,
                    r = {};
                ! function() {
                    try {
                        var a = b.createElement("a");
                        a.innerHTML = "<xyz></xyz>", j = "hidden" in a, k = 1 == a.childNodes.length || function() {
                            b.createElement("a");
                            var a = b.createDocumentFragment();
                            return "undefined" == typeof a.cloneNode || "undefined" == typeof a.createDocumentFragment || "undefined" == typeof a.createElement
                        }()
                    } catch (c) {
                        j = !0, k = !0
                    }
                }();
                var s = {
                    elements: m.elements || "abbr article aside audio bdi canvas data datalist details dialog figcaption figure footer header hgroup main mark meter nav output progress section summary template time video",
                    version: l,
                    shivCSS: m.shivCSS !== !1,
                    supportsUnknownElements: k,
                    shivMethods: m.shivMethods !== !1,
                    type: "default",
                    shivDocument: i,
                    createElement: f,
                    createDocumentFragment: g
                };
                a.html5 = s, i(b)
            }(this, b), j._version = i, j._prefixes = p, j.testStyles = t, l.className = l.className.replace(/(^|\s)no-js(\s|$)/, "$1$2") + (k ? " js " + r.join(" ") : ""), j
    }(this, this.document), function(a, b, c) {
        function d(a) {
            return "[object Function]" == q.call(a)
        }

        function e(a) {
            return "string" == typeof a
        }

        function f() {}

        function g(a) {
            return !a || "loaded" == a || "complete" == a || "uninitialized" == a
        }

        function h() {
            var a = r.shift();
            s = 1, a ? a.t ? o(function() {
                ("c" == a.t ? m.injectCss : m.injectJs)(a.s, 0, a.a, a.x, a.e, 1)
            }, 0) : (a(), h()) : s = 0
        }

        function i(a, c, d, e, f, i, j) {
            function k(b) {
                if (!n && g(l.readyState) && (t.r = n = 1, !s && h(), l.onload = l.onreadystatechange = null, b)) {
                    "img" != a && o(function() {
                        v.removeChild(l)
                    }, 50);
                    for (var d in A[c]) A[c].hasOwnProperty(d) && A[c][d].onload()
                }
            }
            var j = j || m.errorTimeout,
                l = b.createElement(a),
                n = 0,
                q = 0,
                t = {
                    t: d,
                    s: c,
                    e: f,
                    a: i,
                    x: j
                };
            1 === A[c] && (q = 1, A[c] = []), "object" == a ? l.data = c : (l.src = c, l.type = a), l.width = l.height = "0", l.onerror = l.onload = l.onreadystatechange = function() {
                k.call(this, q)
            }, r.splice(e, 0, t), "img" != a && (q || 2 === A[c] ? (v.insertBefore(l, u ? null : p), o(k, j)) : A[c].push(l))
        }

        function j(a, b, c, d, f) {
            return s = 0, b = b || "j", e(a) ? i("c" == b ? x : w, a, b, this.i++, c, d, f) : (r.splice(this.i++, 0, a), 1 == r.length && h()), this
        }

        function k() {
            var a = m;
            return a.loader = {
                load: j,
                i: 0
            }, a
        }
        var l, m, n = b.documentElement,
            o = a.setTimeout,
            p = b.getElementsByTagName("script")[0],
            q = {}.toString,
            r = [],
            s = 0,
            t = "MozAppearance" in n.style,
            u = t && !!b.createRange().compareNode,
            v = u ? n : p.parentNode,
            n = a.opera && "[object Opera]" == q.call(a.opera),
            n = !!b.attachEvent && !n,
            w = t ? "object" : n ? "script" : "img",
            x = n ? "script" : w,
            y = Array.isArray || function(a) {
                return "[object Array]" == q.call(a)
            },
            z = [],
            A = {},
            B = {
                timeout: function(a, b) {
                    return b.length && (a.timeout = b[0]), a
                }
            };
        m = function(a) {
            function b(a) {
                var b, c, d, a = a.split("!"),
                    e = z.length,
                    f = a.pop(),
                    g = a.length,
                    f = {
                        url: f,
                        origUrl: f,
                        prefixes: a
                    };
                for (c = 0; g > c; c++) d = a[c].split("="), (b = B[d.shift()]) && (f = b(f, d));
                for (c = 0; e > c; c++) f = z[c](f);
                return f
            }

            function g(a, e, f, g, h) {
                var i = b(a),
                    j = i.autoCallback;
                i.url.split(".").pop().split("?").shift(), i.bypass || (e && (e = d(e) ? e : e[a] || e[g] || e[a.split("/").pop().split("?")[0]]), i.instead ? i.instead(a, e, f, g, h) : (A[i.url] ? i.noexec = !0 : A[i.url] = 1, f.load(i.url, i.forceCSS || !i.forceJS && "css" == i.url.split(".").pop().split("?").shift() ? "c" : c, i.noexec, i.attrs, i.timeout), (d(e) || d(j)) && f.load(function() {
                    k(), e && e(i.origUrl, h, g), j && j(i.origUrl, h, g), A[i.url] = 2
                })))
            }

            function h(a, b) {
                function c(a, c) {
                    if (a) {
                        if (e(a)) c || (l = function() {
                            var a = [].slice.call(arguments);
                            m.apply(this, a), n()
                        }), g(a, l, b, 0, j);
                        else if (Object(a) === a)
                            for (i in h = function() {
                                    var b, c = 0;
                                    for (b in a) a.hasOwnProperty(b) && c++;
                                    return c
                                }(), a) a.hasOwnProperty(i) && (!c && !--h && (d(l) ? l = function() {
                                var a = [].slice.call(arguments);
                                m.apply(this, a), n()
                            } : l[i] = function(a) {
                                return function() {
                                    var b = [].slice.call(arguments);
                                    a && a.apply(this, b), n()
                                }
                            }(m[i])), g(a[i], l, b, i, j))
                    } else !c && n()
                }
                var h, i, j = !!a.test,
                    k = a.load || a.both,
                    l = a.callback || f,
                    m = l,
                    n = a.complete || f;
                c(j ? a.yep : a.nope, !!k), k && c(k)
            }
            var i, j, l = this.yepnope.loader;
            if (e(a)) g(a, 0, l, 0);
            else if (y(a))
                for (i = 0; i < a.length; i++) j = a[i], e(j) ? g(j, 0, l, 0) : y(j) ? m(j) : Object(j) === j && h(j, l);
            else Object(a) === a && h(a, l)
        }, m.addPrefix = function(a, b) {
            B[a] = b
        }, m.addFilter = function(a) {
            z.push(a)
        }, m.errorTimeout = 1e4, null == b.readyState && b.addEventListener && (b.readyState = "loading", b.addEventListener("DOMContentLoaded", l = function() {
            b.removeEventListener("DOMContentLoaded", l, 0), b.readyState = "complete"
        }, 0)), a.yepnope = k(), a.yepnope.executeStack = h, a.yepnope.injectJs = function(a, c, d, e, i, j) {
            var k, l, n = b.createElement("script"),
                e = e || m.errorTimeout;
            n.src = a;
            for (l in d) n.setAttribute(l, d[l]);
            c = j ? h : c || f, n.onreadystatechange = n.onload = function() {
                !k && g(n.readyState) && (k = 1, c(), n.onload = n.onreadystatechange = null)
            }, o(function() {
                k || (k = 1, c(1))
            }, e), i ? n.onload() : p.parentNode.insertBefore(n, p)
        }, a.yepnope.injectCss = function(a, c, d, e, g, i) {
            var j, e = b.createElement("link"),
                c = i ? h : c || f;
            e.href = a, e.rel = "stylesheet", e.type = "text/css";
            for (j in d) e.setAttribute(j, d[j]);
            g || (p.parentNode.insertBefore(e, p), o(c, 0))
        }
    }(this, document), Modernizr.load = function() {
        yepnope.apply(window, [].slice.call(arguments, 0))
    }, ! function(a, b, c) {
        "object" == typeof module && module && "object" == typeof module.exports ? module.exports = c : (a[b] = c, "function" == typeof define && define.amd && define(b, [], function() {
            return c
        }))
    }(this, "jRespond", function(a, b, c) {
        "use strict";
        return function(a) {
            var b = [],
                d = [],
                e = a,
                f = "",
                g = "",
                h = 0,
                i = 100,
                j = 500,
                k = j,
                l = function() {
                    var a = 0;
                    return a = "number" != typeof window.innerWidth ? 0 !== document.documentElement.clientWidth ? document.documentElement.clientWidth : document.body.clientWidth : window.innerWidth
                },
                m = function(a) {
                    if (a.length === c) n(a);
                    else
                        for (var b = 0; b < a.length; b++) n(a[b])
                },
                n = function(a) {
                    var e = a.breakpoint,
                        h = a.enter || c;
                    b.push(a), d.push(!1), q(e) && (h !== c && h.call(null, {
                        entering: f,
                        exiting: g
                    }), d[b.length - 1] = !0)
                },
                o = function() {
                    for (var a = [], e = [], h = 0; h < b.length; h++) {
                        var i = b[h].breakpoint,
                            j = b[h].enter || c,
                            k = b[h].exit || c;
                        "*" === i ? (j !== c && a.push(j), k !== c && e.push(k)) : q(i) ? (j === c || d[h] || a.push(j), d[h] = !0) : (k !== c && d[h] && e.push(k), d[h] = !1)
                    }
                    for (var l = {
                            entering: f,
                            exiting: g
                        }, m = 0; m < e.length; m++) e[m].call(null, l);
                    for (var n = 0; n < a.length; n++) a[n].call(null, l)
                },
                p = function(a) {
                    for (var b = !1, c = 0; c < e.length; c++)
                        if (a >= e[c].enter && a <= e[c].exit) {
                            b = !0;
                            break
                        }
                    b && f !== e[c].label ? (g = f, f = e[c].label, o()) : b || "" === f || (f = "", o())
                },
                q = function(a) {
                    if ("object" == typeof a) {
                        if (a.join().indexOf(f) >= 0) return !0
                    } else {
                        if ("*" === a) return !0;
                        if ("string" == typeof a && f === a) return !0
                    }
                },
                r = function() {
                    var a = l();
                    a !== h ? (k = i, p(a)) : k = j, h = a, setTimeout(r, k)
                };
            return r(), {
                addFunc: function(a) {
                    m(a)
                },
                getBreakpoint: function() {
                    return f
                }
            }
        }
    }(this, this.document)), function(a) {
        jQuery.fn.extend({
            slimScroll: function(b) {
                var c = a.extend({
                    width: "auto",
                    height: "250px",
                    size: "7px",
                    color: "#000",
                    position: "right",
                    distance: "1px",
                    start: "top",
                    opacity: .4,
                    alwaysVisible: !1,
                    disableFadeOut: !1,
                    railVisible: !1,
                    railColor: "#333",
                    railOpacity: .2,
                    railDraggable: !0,
                    railClass: "slimScrollRail",
                    barClass: "slimScrollBar",
                    wrapperClass: "slimScrollDiv",
                    allowPageScroll: !1,
                    wheelStep: 20,
                    touchScrollStep: 200,
                    borderRadius: "7px",
                    railBorderRadius: "7px"
                }, b);
                return this.each(function() {
                    function d(b) {
                        if (j) {
                            b = b || window.event;
                            var d = 0;
                            b.wheelDelta && (d = -b.wheelDelta / 120), b.detail && (d = b.detail / 3), a(b.target || b.srcTarget || b.srcElement).closest("." + c.wrapperClass).is(u.parent()) && e(d, !0), b.preventDefault && !s && b.preventDefault(), s || (b.returnValue = !1)
                        }
                    }

                    function e(a, b, d) {
                        s = !1;
                        var e = a,
                            f = u.outerHeight() - w.outerHeight();
                        b && (e = parseInt(w.css("top")) + a * parseInt(c.wheelStep) / 100 * w.outerHeight(), e = Math.min(Math.max(e, 0), f), e = a > 0 ? Math.ceil(e) : Math.floor(e), w.css({
                            top: e + "px"
                        })), p = parseInt(w.css("top")) / (u.outerHeight() - w.outerHeight()), e = p * (u[0].scrollHeight - u.outerHeight()), d && (e = a, a = e / u[0].scrollHeight * u.outerHeight(), a = Math.min(Math.max(a, 0), f), w.css({
                            top: a + "px"
                        })), u.scrollTop(e), u.trigger("slimscrolling", ~~e), h(), i()
                    }

                    function f() {
                        window.addEventListener ? (this.addEventListener("DOMMouseScroll", d, !1), this.addEventListener("mousewheel", d, !1), this.addEventListener("MozMousePixelScroll", null, !1)) : document.attachEvent("onmousewheel", d)
                    }

                    function g() {
                        o = Math.max(u.outerHeight() / u[0].scrollHeight * u.outerHeight(), r), w.css({
                            height: o + "px"
                        });
                        var a = o == u.outerHeight() ? "none" : "block";
                        w.css({
                            display: a
                        })
                    }

                    function h() {
                        g(), clearTimeout(m), p == ~~p ? (s = c.allowPageScroll, q != p && u.trigger("slimscroll", 0 == ~~p ? "top" : "bottom")) : s = !1, q = p, o >= u.outerHeight() ? s = !0 : (w.stop(!0, !0).fadeIn("fast"), c.railVisible && x.stop(!0, !0).fadeIn("fast"))
                    }

                    function i() {
                        c.alwaysVisible || (m = setTimeout(function() {
                            c.disableFadeOut && j || k || l || (w.fadeOut("slow"), x.fadeOut("slow"))
                        }, 1e3))
                    }
                    var j, k, l, m, n, o, p, q, r = 30,
                        s = !1,
                        u = a(this);
                    if (u.parent().hasClass(c.wrapperClass)) {
                        var v = u.scrollTop(),
                            w = u.parent().find("." + c.barClass),
                            x = u.parent().find("." + c.railClass);
                        if (g(), a.isPlainObject(b)) {
                            if ("height" in b && "auto" == b.height) {
                                u.parent().css("height", "auto"), u.css("height", "auto");
                                var y = u.parent().parent().height();
                                u.parent().css("height", y), u.css("height", y)
                            }
                            if ("scrollTo" in b) v = parseInt(c.scrollTo);
                            else if ("scrollBy" in b) v += parseInt(c.scrollBy);
                            else if ("destroy" in b) return w.remove(), x.remove(), void u.unwrap();
                            e(v, !1, !0)
                        }
                    } else {
                        c.height = "auto" == c.height ? u.parent().height() : c.height, v = a("<div></div>").addClass(c.wrapperClass).css({
                            position: "relative",
                            overflow: "hidden",
                            width: c.width,
                            height: c.height
                        }), u.css({
                            overflow: "hidden",
                            width: c.width,
                            height: c.height
                        });
                        var x = a("<div></div>").addClass(c.railClass).css({
                                width: c.size,
                                height: "100%",
                                position: "absolute",
                                top: 0,
                                display: c.alwaysVisible && c.railVisible ? "block" : "none",
                                "border-radius": c.railBorderRadius,
                                background: c.railColor,
                                opacity: c.railOpacity,
                                zIndex: 90
                            }),
                            w = a("<div></div>").addClass(c.barClass).css({
                                background: c.color,
                                width: c.size,
                                position: "absolute",
                                top: 0,
                                opacity: c.opacity,
                                display: c.alwaysVisible ? "block" : "none",
                                "border-radius": c.borderRadius,
                                BorderRadius: c.borderRadius,
                                MozBorderRadius: c.borderRadius,
                                WebkitBorderRadius: c.borderRadius,
                                zIndex: 99
                            }),
                            y = "right" == c.position ? {
                                right: c.distance
                            } : {
                                left: c.distance
                            };
                        x.css(y), w.css(y), u.wrap(v), u.parent().append(w), u.parent().append(x), c.railDraggable && w.bind("mousedown", function(b) {
                            var c = a(document);
                            return l = !0, t = parseFloat(w.css("top")), pageY = b.pageY, c.bind("mousemove.slimscroll", function(a) {
                                currTop = t + a.pageY - pageY, w.css("top", currTop), e(0, w.position().top, !1)
                            }), c.bind("mouseup.slimscroll", function() {
                                l = !1, i(), c.unbind(".slimscroll")
                            }), !1
                        }).bind("selectstart.slimscroll", function(a) {
                            return a.stopPropagation(), a.preventDefault(), !1
                        }), x.hover(function() {
                            h()
                        }, function() {
                            i()
                        }), w.hover(function() {
                            k = !0
                        }, function() {
                            k = !1
                        }), u.hover(function() {
                            j = !0, h(), i()
                        }, function() {
                            j = !1, i()
                        }), u.bind("touchstart", function(a) {
                            a.originalEvent.touches.length && (n = a.originalEvent.touches[0].pageY)
                        }), u.bind("touchmove", function(a) {
                            s || a.originalEvent.preventDefault(), a.originalEvent.touches.length && (e((n - a.originalEvent.touches[0].pageY) / c.touchScrollStep, !0), n = a.originalEvent.touches[0].pageY)
                        }), g(), "bottom" === c.start ? (w.css({
                            top: u.outerHeight() - w.outerHeight()
                        }), e(0, !0)) : "top" !== c.start && (e(a(c.start).position().top, null, !0), c.alwaysVisible || w.hide()), f()
                    }
                }), this
            }
        }), jQuery.fn.extend({
            slimscroll: jQuery.fn.slimScroll
        })
    }(jQuery), function(a) {
        jQuery.fn.extend({
            slimScrollHorizontal: function(b) {
                var c = {
                        wheelStep: 20,
                        height: "auto",
                        width: "250px",
                        size: "7px",
                        color: "#000",
                        position: "bottom",
                        distance: "1px",
                        start: "left",
                        opacity: .4,
                        alwaysVisible: !1,
                        disableFadeOut: !1,
                        railVisible: !1,
                        railColor: "#333",
                        railOpacity: "0.2",
                        railClass: "slimScrollRail",
                        barClass: "slimScrollBar",
                        wrapperClass: "slimScrollDiv",
                        allowPageScroll: !1,
                        scroll: 0,
                        touchScrollStep: 200
                    },
                    d = a.extend(c, b);
                return this.each(function() {
                    function b(a, b, c) {
                        var g = a;
                        if ("auto" == u.css("left") && u.css("left", "0px"), b) {
                            g = parseInt(u.css("left")) + a * parseInt(d.wheelStep) / 100 * u.outerWidth();
                            var h = r.outerWidth() - u.outerWidth();
                            g = Math.min(Math.max(g, 0), h), u.css({
                                left: g + "px"
                            })
                        }
                        if (m = parseInt(u.css("left")) / (r.outerWidth() - u.outerWidth()), g = m * (r[0].scrollWidth - r.outerWidth()), c) {
                            g = a;
                            var i = g / r[0].scrollWidth * r.outerWidth();
                            u.css({
                                left: i + "px"
                            })
                        }
                        r.scrollLeft(g), e(), f()
                    }

                    function c() {
                        l = Math.max(r.outerWidth() / r[0].scrollWidth * r.outerWidth(), p), u.css({
                            width: l + "px"
                        })
                    }

                    function e() {
                        if (c(), clearTimeout(j), m == ~~m && (q = d.allowPageScroll, n != m)) {
                            var a = 0 == ~~m ? "left" : "right";
                            r.trigger("slimscroll", a)
                        }
                        return n = m, l >= r.outerWidth() ? void(q = !0) : (u.stop(!0, !0).fadeIn("fast"), void(d.railVisible && t.stop(!0, !0).fadeIn("fast")))
                    }

                    function f() {
                        d.alwaysVisible || (j = setTimeout(function() {
                            d.disableFadeOut && g || h || i || (u.fadeOut("slow"), t.fadeOut("slow"))
                        }, 1e3))
                    }
                    var g, h, i, j, k, l, m, n, o = "<div></div>",
                        p = 30,
                        q = !1,
                        r = a(this);
                    if (r.parent().hasClass("slimScrollDiv")) return void(scroll && (u = r.parent().find(".slimScrollBar"), t = r.parent().find(".slimScrollRail"), b(r.scrollLeft() + parseInt(scroll), !1, !0)));
                    var s = a(o).addClass(d.wrapperClass).css({
                        position: "relative",
                        overflow: "hidden",
                        width: d.width,
                        height: d.height
                    });
                    r.css({
                        overflow: "hidden",
                        width: d.width,
                        height: d.height
                    });
                    var t = a(o).addClass(d.railClass).css({
                            width: "100%",
                            height: d.size,
                            position: "absolute",
                            bottom: 0,
                            display: d.alwaysVisible && d.railVisible ? "block" : "none",
                            "border-radius": d.size,
                            background: d.railColor,
                            opacity: d.railOpacity,
                            zIndex: 90
                        }),
                        u = a(o).addClass(d.barClass).css({
                            background: d.color,
                            height: d.size,
                            position: "absolute",
                            bottom: 0,
                            opacity: d.opacity,
                            display: d.alwaysVisible ? "block" : "none",
                            "border-radius": d.size,
                            BorderRadius: d.size,
                            MozBorderRadius: d.size,
                            WebkitBorderRadius: d.size,
                            zIndex: 99
                        }),
                        v = "top" == d.position ? {
                            top: d.distance
                        } : {
                            bottom: d.distance
                        };
                    t.css(v), u.css(v), r.wrap(s), r.parent().append(u), r.parent().append(t), u.draggable({
                        axis: "x",
                        containment: "parent",
                        start: function() {
                            i = !0
                        },
                        stop: function() {
                            i = !1, f()
                        },
                        drag: function() {
                            b(0, a(this).position().left, !1)
                        }
                    }), t.hover(function() {
                        e()
                    }, function() {
                        f()
                    }), u.hover(function() {
                        h = !0
                    }, function() {
                        h = !1
                    }), r.hover(function() {
                        g = !0, e(), f()
                    }, function() {
                        g = !1, f()
                    }), r.bind("touchstart", function(a) {
                        a.originalEvent.touches.length && (k = a.originalEvent.touches[0].pageX)
                    }), r.bind("touchmove", function(a) {
                        if (a.originalEvent.preventDefault(), a.originalEvent.touches.length) {
                            var c = (k - a.originalEvent.touches[0].pageX) / d.touchScrollStep;
                            b(c, !0)
                        }
                    });
                    var w = function(a) {
                            if (g) {
                                var a = a || window.event,
                                    c = 0;
                                a.wheelDelta && (c = -a.wheelDelta / 120), a.detail && (c = a.detail / 3), b(c, !0), a.preventDefault && !q && a.preventDefault(), q || (a.returnValue = !1)
                            }
                        },
                        x = function() {
                            window.addEventListener ? (this.addEventListener("DOMMouseScroll", w, !1), this.addEventListener("mousewheel", w, !1)) : document.attachEvent("onmousewheel", w)
                        };
                    x(), c(), "right" == d.start ? (u.css({
                        left: r.outerWidth() - u.outerWidth()
                    }), b(0, !0)) : "object" == typeof d.start && (b(a(d.start).position().left, null, !0), d.alwaysVisible || u.hide())
                }), this
            }
        }), jQuery.fn.extend({
            slimscrollHorizontal: jQuery.fn.slimScrollHorizontal
        })
    }(jQuery), function() {
        "use strict";

        function a(c, d) {
            function e(a, b) {
                return function() {
                    return a.apply(b, arguments)
                }
            }
            var f;
            if (d = d || {}, this.trackingClick = !1, this.trackingClickStart = 0, this.targetElement = null, this.touchStartX = 0, this.touchStartY = 0, this.lastTouchIdentifier = 0, this.touchBoundary = d.touchBoundary || 10, this.layer = c, this.tapDelay = d.tapDelay || 200, !a.notNeeded(c)) {
                for (var g = ["onMouse", "onClick", "onTouchStart", "onTouchMove", "onTouchEnd", "onTouchCancel"], h = this, i = 0, j = g.length; j > i; i++) h[g[i]] = e(h[g[i]], h);
                b && (c.addEventListener("mouseover", this.onMouse, !0), c.addEventListener("mousedown", this.onMouse, !0), c.addEventListener("mouseup", this.onMouse, !0)), c.addEventListener("click", this.onClick, !0), c.addEventListener("touchstart", this.onTouchStart, !1), c.addEventListener("touchmove", this.onTouchMove, !1), c.addEventListener("touchend", this.onTouchEnd, !1), c.addEventListener("touchcancel", this.onTouchCancel, !1), Event.prototype.stopImmediatePropagation || (c.removeEventListener = function(a, b, d) {
                    var e = Node.prototype.removeEventListener;
                    "click" === a ? e.call(c, a, b.hijacked || b, d) : e.call(c, a, b, d)
                }, c.addEventListener = function(a, b, d) {
                    var e = Node.prototype.addEventListener;
                    "click" === a ? e.call(c, a, b.hijacked || (b.hijacked = function(a) {
                        a.propagationStopped || b(a)
                    }), d) : e.call(c, a, b, d)
                }), "function" == typeof c.onclick && (f = c.onclick, c.addEventListener("click", function(a) {
                    f(a)
                }, !1), c.onclick = null)
            }
        }
        var b = navigator.userAgent.indexOf("Android") > 0,
            c = /iP(ad|hone|od)/.test(navigator.userAgent),
            d = c && /OS 4_\d(_\d)?/.test(navigator.userAgent),
            e = c && /OS ([6-9]|\d{2})_\d/.test(navigator.userAgent),
            f = navigator.userAgent.indexOf("BB10") > 0;
        a.prototype.needsClick = function(a) {
            switch (a.nodeName.toLowerCase()) {
                case "button":
                case "select":
                case "textarea":
                    if (a.disabled) return !0;
                    break;
                case "input":
                    if (c && "file" === a.type || a.disabled) return !0;
                    break;
                case "label":
                case "video":
                    return !0
            }
            return /\bneedsclick\b/.test(a.className)
        }, a.prototype.needsFocus = function(a) {
            switch (a.nodeName.toLowerCase()) {
                case "textarea":
                    return !0;
                case "select":
                    return !b;
                case "input":
                    switch (a.type) {
                        case "button":
                        case "checkbox":
                        case "file":
                        case "image":
                        case "radio":
                        case "submit":
                            return !1
                    }
                    return !a.disabled && !a.readOnly;
                default:
                    return /\bneedsfocus\b/.test(a.className)
            }
        }, a.prototype.sendClick = function(a, b) {
            var c, d;
            document.activeElement && document.activeElement !== a && document.activeElement.blur(), d = b.changedTouches[0], c = document.createEvent("MouseEvents"), c.initMouseEvent(this.determineEventType(a), !0, !0, window, 1, d.screenX, d.screenY, d.clientX, d.clientY, !1, !1, !1, !1, 0, null), c.forwardedTouchEvent = !0, a.dispatchEvent(c)
        }, a.prototype.determineEventType = function(a) {
            return b && "select" === a.tagName.toLowerCase() ? "mousedown" : "click"
        }, a.prototype.focus = function(a) {
            var b;
            c && a.setSelectionRange && 0 !== a.type.indexOf("date") && "time" !== a.type ? (b = a.value.length, a.setSelectionRange(b, b)) : a.focus()
        }, a.prototype.updateScrollParent = function(a) {
            var b, c;
            if (b = a.fastClickScrollParent, !b || !b.contains(a)) {
                c = a;
                do {
                    if (c.scrollHeight > c.offsetHeight) {
                        b = c, a.fastClickScrollParent = c;
                        break
                    }
                    c = c.parentElement
                } while (c)
            }
            b && (b.fastClickLastScrollTop = b.scrollTop)
        }, a.prototype.getTargetElementFromEventTarget = function(a) {
            return a.nodeType === Node.TEXT_NODE ? a.parentNode : a
        }, a.prototype.onTouchStart = function(a) {
            var b, e, f;
            if (a.targetTouches.length > 1) return !0;
            if (b = this.getTargetElementFromEventTarget(a.target), e = a.targetTouches[0], c) {
                if (f = window.getSelection(), f.rangeCount && !f.isCollapsed) return !0;
                if (!d) {
                    if (e.identifier && e.identifier === this.lastTouchIdentifier) return a.preventDefault(), !1;
                    this.lastTouchIdentifier = e.identifier, this.updateScrollParent(b)
                }
            }
            return this.trackingClick = !0, this.trackingClickStart = a.timeStamp, this.targetElement = b, this.touchStartX = e.pageX, this.touchStartY = e.pageY, a.timeStamp - this.lastClickTime < this.tapDelay && a.preventDefault(), !0
        }, a.prototype.touchHasMoved = function(a) {
            var b = a.changedTouches[0],
                c = this.touchBoundary;
            return Math.abs(b.pageX - this.touchStartX) > c || Math.abs(b.pageY - this.touchStartY) > c ? !0 : !1
        }, a.prototype.onTouchMove = function(a) {
            return this.trackingClick ? ((this.targetElement !== this.getTargetElementFromEventTarget(a.target) || this.touchHasMoved(a)) && (this.trackingClick = !1, this.targetElement = null), !0) : !0
        }, a.prototype.findControl = function(a) {
            return void 0 !== a.control ? a.control : a.htmlFor ? document.getElementById(a.htmlFor) : a.querySelector("button, input:not([type=hidden]), keygen, meter, output, progress, select, textarea")
        }, a.prototype.onTouchEnd = function(a) {
            var f, g, h, i, j, k = this.targetElement;
            if (!this.trackingClick) return !0;
            if (a.timeStamp - this.lastClickTime < this.tapDelay) return this.cancelNextClick = !0, !0;
            if (this.cancelNextClick = !1, this.lastClickTime = a.timeStamp, g = this.trackingClickStart, this.trackingClick = !1, this.trackingClickStart = 0, e && (j = a.changedTouches[0], k = document.elementFromPoint(j.pageX - window.pageXOffset, j.pageY - window.pageYOffset) || k, k.fastClickScrollParent = this.targetElement.fastClickScrollParent), h = k.tagName.toLowerCase(), "label" === h) {
                if (f = this.findControl(k)) {
                    if (this.focus(k), b) return !1;
                    k = f
                }
            } else if (this.needsFocus(k)) return a.timeStamp - g > 100 || c && window.top !== window && "input" === h ? (this.targetElement = null, !1) : (this.focus(k), this.sendClick(k, a), c && "select" === h || (this.targetElement = null, a.preventDefault()), !1);
            return c && !d && (i = k.fastClickScrollParent, i && i.fastClickLastScrollTop !== i.scrollTop) ? !0 : (this.needsClick(k) || (a.preventDefault(), this.sendClick(k, a)), !1)
        }, a.prototype.onTouchCancel = function() {
            this.trackingClick = !1, this.targetElement = null
        }, a.prototype.onMouse = function(a) {
            return this.targetElement ? a.forwardedTouchEvent ? !0 : a.cancelable && (!this.needsClick(this.targetElement) || this.cancelNextClick) ? (a.stopImmediatePropagation ? a.stopImmediatePropagation() : a.propagationStopped = !0, a.stopPropagation(), a.preventDefault(), !1) : !0 : !0
        }, a.prototype.onClick = function(a) {
            var b;
            return this.trackingClick ? (this.targetElement = null, this.trackingClick = !1, !0) : "submit" === a.target.type && 0 === a.detail ? !0 : (b = this.onMouse(a), b || (this.targetElement = null), b)
        }, a.prototype.destroy = function() {
            var a = this.layer;
            b && (a.removeEventListener("mouseover", this.onMouse, !0), a.removeEventListener("mousedown", this.onMouse, !0), a.removeEventListener("mouseup", this.onMouse, !0)), a.removeEventListener("click", this.onClick, !0), a.removeEventListener("touchstart", this.onTouchStart, !1), a.removeEventListener("touchmove", this.onTouchMove, !1), a.removeEventListener("touchend", this.onTouchEnd, !1), a.removeEventListener("touchcancel", this.onTouchCancel, !1)
        }, a.notNeeded = function(a) {
            var c, d, e;
            if ("undefined" == typeof window.ontouchstart) return !0;
            if (d = +(/Chrome\/([0-9]+)/.exec(navigator.userAgent) || [, 0])[1]) {
                if (!b) return !0;
                if (c = document.querySelector("meta[name=viewport]")) {
                    if (-1 !== c.content.indexOf("user-scalable=no")) return !0;
                    if (d > 31 && document.documentElement.scrollWidth <= window.outerWidth) return !0
                }
            }
            if (f && (e = navigator.userAgent.match(/Version\/([0-9]*)\.([0-9]*)/), e[1] >= 10 && e[2] >= 3 && (c = document.querySelector("meta[name=viewport]")))) {
                if (-1 !== c.content.indexOf("user-scalable=no")) return !0;
                if (document.documentElement.scrollWidth <= window.outerWidth) return !0
            }
            return "none" === a.style.msTouchAction ? !0 : !1
        }, a.attach = function(b, c) {
            return new a(b, c)
        }, "function" == typeof define && "object" == typeof define.amd && define.amd ? define(function() {
            return a
        }) : "undefined" != typeof module && module.exports ? (module.exports = a.attach, module.exports.FastClick = a) : window.FastClick = a
    }(), ! function(a) {
        "object" == typeof module && "object" == typeof module.exports ? module.exports = a(window.Velocity ? window.jQuery : require("jquery")) : "function" == typeof define && define.amd ? window.Velocity ? define(a) : define(["jquery"], a) : a(window.jQuery)
    }(function(a) {
        return function(b, c, d, e) {
            function f(a) {
                for (var b = -1, c = a ? a.length : 0, d = []; ++b < c;) {
                    var e = a[b];
                    e && d.push(e)
                }
                return d
            }

            function g(a) {
                return q.isNode(a) ? [a] : a
            }

            function h(a) {
                var b = n.data(a, "velocity");
                return null === b ? e : b
            }

            function i(a) {
                return function(b) {
                    return Math.round(b * a) * (1 / a)
                }
            }

            function j(a, b, d, e) {
                function f(a, b) {
                    return 1 - 3 * b + 3 * a
                }

                function g(a, b) {
                    return 3 * b - 6 * a
                }

                function h(a) {
                    return 3 * a
                }

                function i(a, b, c) {
                    return ((f(b, c) * a + g(b, c)) * a + h(b)) * a
                }

                function j(a, b, c) {
                    return 3 * f(b, c) * a * a + 2 * g(b, c) * a + h(b)
                }

                function k(b, c) {
                    for (var e = 0; p > e; ++e) {
                        var f = j(c, a, d);
                        if (0 === f) return c;
                        var g = i(c, a, d) - b;
                        c -= g / f
                    }
                    return c
                }

                function l() {
                    for (var b = 0; t > b; ++b) x[b] = i(b * u, a, d)
                }

                function m(b, c, e) {
                    var f, g, h = 0;
                    do g = c + (e - c) / 2, f = i(g, a, d) - b, f > 0 ? e = g : c = g; while (Math.abs(f) > r && ++h < s);
                    return g
                }

                function n(b) {
                    for (var c = 0, e = 1, f = t - 1; e != f && x[e] <= b; ++e) c += u;
                    --e;
                    var g = (b - x[e]) / (x[e + 1] - x[e]),
                        h = c + g * u,
                        i = j(h, a, d);
                    return i >= q ? k(b, h) : 0 == i ? h : m(b, c, c + u)
                }

                function o() {
                    y = !0, (a != b || d != e) && l()
                }
                var p = 4,
                    q = .001,
                    r = 1e-7,
                    s = 10,
                    t = 11,
                    u = 1 / (t - 1),
                    v = "Float32Array" in c;
                if (4 !== arguments.length) return !1;
                for (var w = 0; 4 > w; ++w)
                    if ("number" != typeof arguments[w] || isNaN(arguments[w]) || !isFinite(arguments[w])) return !1;
                a = Math.min(a, 1), d = Math.min(d, 1), a = Math.max(a, 0), d = Math.max(d, 0);
                var x = v ? new Float32Array(t) : new Array(t),
                    y = !1,
                    z = function(c) {
                        return y || o(), a === b && d === e ? c : 0 === c ? 0 : 1 === c ? 1 : i(n(c), b, e)
                    };
                z.getControlPoints = function() {
                    return [{
                        x: a,
                        y: b
                    }, {
                        x: d,
                        y: e
                    }]
                };
                var A = "generateBezier(" + [a, b, d, e] + ")";
                return z.toString = function() {
                    return A
                }, z
            }

            function k(a, b) {
                var c = a;
                return q.isString(a) ? t.Easings[a] || (c = !1) : c = q.isArray(a) && 1 === a.length ? i.apply(null, a) : q.isArray(a) && 2 === a.length ? u.apply(null, a.concat([b])) : q.isArray(a) && 4 === a.length ? j.apply(null, a) : !1, c === !1 && (c = t.Easings[t.defaults.easing] ? t.defaults.easing : s), c
            }

            function l(a) {
                if (a)
                    for (var b = (new Date).getTime(), c = 0, d = t.State.calls.length; d > c; c++)
                        if (t.State.calls[c]) {
                            var f = t.State.calls[c],
                                g = f[0],
                                i = f[2],
                                j = f[3];
                            j || (j = t.State.calls[c][3] = b - 16);
                            for (var k = Math.min((b - j) / i.duration, 1), n = 0, p = g.length; p > n; n++) {
                                var r = g[n],
                                    s = r.element;
                                if (h(s)) {
                                    var u = !1;
                                    i.display !== e && null !== i.display && "none" !== i.display && ("flex" === i.display && v.setPropertyValue(s, "display", (o ? "-ms-" : "-webkit-") + i.display), v.setPropertyValue(s, "display", i.display)), i.visibility && "hidden" !== i.visibility && v.setPropertyValue(s, "visibility", i.visibility);
                                    for (var w in r)
                                        if ("element" !== w) {
                                            var y, z = r[w],
                                                A = q.isString(z.easing) ? t.Easings[z.easing] : z.easing;
                                            if (y = 1 === k ? z.endValue : z.startValue + (z.endValue - z.startValue) * A(k), z.currentValue = y, v.Hooks.registered[w]) {
                                                var B = v.Hooks.getRoot(w),
                                                    C = h(s).rootPropertyValueCache[B];
                                                C && (z.rootPropertyValue = C)
                                            }
                                            var D = v.setPropertyValue(s, w, z.currentValue + (0 === parseFloat(y) ? "" : z.unitType), z.rootPropertyValue, z.scrollData);
                                            v.Hooks.registered[w] && (h(s).rootPropertyValueCache[B] = v.Normalizations.registered[B] ? v.Normalizations.registered[B]("extract", null, D[1]) : D[1]), "transform" === D[0] && (u = !0)
                                        }
                                    i.mobileHA && h(s).transformCache.translate3d === e && (h(s).transformCache.translate3d = "(0px, 0px, 0px)", u = !0), u && v.flushTransformCache(s)
                                }
                            }
                            i.display !== e && "none" !== i.display && (t.State.calls[c][2].display = !1), i.visibility && "hidden" !== i.visibility && (t.State.calls[c][2].visibility = !1), i.progress && i.progress.call(f[1], f[1], k, Math.max(0, j + i.duration - b), j), 1 === k && m(c)
                        }
                t.State.isTicking && x(l)
            }

            function m(a, b) {
                if (!t.State.calls[a]) return !1;
                for (var c = t.State.calls[a][0], d = t.State.calls[a][1], f = t.State.calls[a][2], g = t.State.calls[a][4], i = !1, j = 0, k = c.length; k > j; j++) {
                    var l = c[j].element;
                    if (b || f.loop || ("none" === f.display && v.setPropertyValue(l, "display", f.display), "hidden" === f.visibility && v.setPropertyValue(l, "visibility", f.visibility)), (n.queue(l)[1] === e || !/\.velocityQueueEntryFlag/i.test(n.queue(l)[1])) && h(l)) {
                        h(l).isAnimating = !1, h(l).rootPropertyValueCache = {};
                        var m = !1;
                        n.each(v.Lists.transforms3D, function(a, b) {
                            var c = /^scale/.test(b) ? 1 : 0,
                                d = h(l).transformCache[b];
                            h(l).transformCache[b] !== e && new RegExp("^\\(" + c + "[^.]").test(d) && (m = !0, delete h(l).transformCache[b])
                        }), f.mobileHA && (m = !0, delete h(l).transformCache.translate3d), m && v.flushTransformCache(l), v.Values.removeClass(l, "velocity-animating")
                    }
                    if (!b && f.complete && !f.loop && j === k - 1) try {
                        f.complete.call(d, d)
                    } catch (o) {
                        setTimeout(function() {
                            throw o
                        }, 1)
                    }
                    g && f.loop !== !0 && g(d), f.loop !== !0 || b || t(l, "reverse", {
                        loop: !0,
                        delay: f.delay
                    }), f.queue !== !1 && n.dequeue(l, f.queue)
                }
                t.State.calls[a] = !1;
                for (var p = 0, q = t.State.calls.length; q > p; p++)
                    if (t.State.calls[p] !== !1) {
                        i = !0;
                        break
                    }
                i === !1 && (t.State.isTicking = !1, delete t.State.calls, t.State.calls = [])
            }
            var n, o = function() {
                    if (d.documentMode) return d.documentMode;
                    for (var a = 7; a > 4; a--) {
                        var b = d.createElement("div");
                        if (b.innerHTML = "<!--[if IE " + a + "]><span></span><![endif]-->", b.getElementsByTagName("span").length) return b = null, a
                    }
                    return e
                }(),
                p = function() {
                    var a = 0;
                    return c.webkitRequestAnimationFrame || c.mozRequestAnimationFrame || function(b) {
                        var c, d = (new Date).getTime();
                        return c = Math.max(0, 16 - (d - a)), a = d + c, setTimeout(function() {
                            b(d + c)
                        }, c)
                    }
                }(),
                q = {
                    isString: function(a) {
                        return "string" == typeof a
                    },
                    isArray: Array.isArray || function(a) {
                        return "[object Array]" === Object.prototype.toString.call(a)
                    },
                    isFunction: function(a) {
                        return "[object Function]" === Object.prototype.toString.call(a)
                    },
                    isNode: function(a) {
                        return a && a.nodeType
                    },
                    isNodeList: function(a) {
                        return "object" == typeof a && /^\[object (HTMLCollection|NodeList|Object)\]$/.test(Object.prototype.toString.call(a)) && a.length !== e && (0 === a.length || "object" == typeof a[0] && a[0].nodeType > 0)
                    },
                    isWrapped: function(a) {
                        return a && (a.jquery || c.Zepto && c.Zepto.zepto.isZ(a))
                    },
                    isSVG: function(a) {
                        return c.SVGElement && a instanceof SVGElement
                    },
                    isEmptyObject: function(a) {
                        var b;
                        for (b in a) return !1;
                        return !0
                    }
                };
            if (a && a.fn !== e ? n = a : c.Velocity && c.Velocity.Utilities && (n = c.Velocity.Utilities), !n) throw new Error("Velocity: Either jQuery or Velocity's jQuery shim must first be loaded.");
            if (b.Velocity !== e && b.Velocity.Utilities == e) throw new Error("Velocity: Namespace is occupied.");
            if (7 >= o) {
                if (a) return void(a.fn.velocity = a.fn.animate);
                throw new Error("Velocity: In IE<=7, Velocity falls back to jQuery, which must first be loaded.")
            }
            if (8 === o && !a) throw new Error("Velocity: In IE8, Velocity requires jQuery proper to be loaded; Velocity's jQuery shim does not work with IE8.");
            var r = 400,
                s = "swing",
                t = {
                    State: {
                        isMobile: /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent),
                        isAndroid: /Android/i.test(navigator.userAgent),
                        isGingerbread: /Android 2\.3\.[3-7]/i.test(navigator.userAgent),
                        isChrome: c.chrome,
                        isFirefox: /Firefox/i.test(navigator.userAgent),
                        prefixElement: d.createElement("div"),
                        prefixMatches: {},
                        scrollAnchor: null,
                        scrollPropertyLeft: null,
                        scrollPropertyTop: null,
                        isTicking: !1,
                        calls: []
                    },
                    CSS: {},
                    Utilities: n,
                    Sequences: {},
                    Easings: {},
                    Promise: c.Promise,
                    defaults: {
                        queue: "",
                        duration: r,
                        easing: s,
                        begin: null,
                        complete: null,
                        progress: null,
                        display: e,
                        loop: !1,
                        delay: !1,
                        mobileHA: !0,
                        _cacheValues: !0
                    },
                    init: function(a) {
                        n.data(a, "velocity", {
                            isSVG: q.isSVG(a),
                            isAnimating: !1,
                            computedStyle: null,
                            tweensContainer: null,
                            rootPropertyValueCache: {},
                            transformCache: {}
                        })
                    },
                    animate: null,
                    hook: null,
                    mock: !1,
                    version: {
                        major: 0,
                        minor: 11,
                        patch: 9
                    },
                    debug: !1
                };
            c.pageYOffset !== e ? (t.State.scrollAnchor = c, t.State.scrollPropertyLeft = "pageXOffset", t.State.scrollPropertyTop = "pageYOffset") : (t.State.scrollAnchor = d.documentElement || d.body.parentNode || d.body, t.State.scrollPropertyLeft = "scrollLeft", t.State.scrollPropertyTop = "scrollTop");
            var u = function() {
                function a(a) {
                    return -a.tension * a.x - a.friction * a.v
                }

                function b(b, c, d) {
                    var e = {
                        x: b.x + d.dx * c,
                        v: b.v + d.dv * c,
                        tension: b.tension,
                        friction: b.friction
                    };
                    return {
                        dx: e.v,
                        dv: a(e)
                    }
                }

                function c(c, d) {
                    var e = {
                            dx: c.v,
                            dv: a(c)
                        },
                        f = b(c, .5 * d, e),
                        g = b(c, .5 * d, f),
                        h = b(c, d, g),
                        i = 1 / 6 * (e.dx + 2 * (f.dx + g.dx) + h.dx),
                        j = 1 / 6 * (e.dv + 2 * (f.dv + g.dv) + h.dv);
                    return c.x = c.x + i * d, c.v = c.v + j * d, c
                }
                return function d(a, b, e) {
                    var f, g, h, i = {
                            x: -1,
                            v: 0,
                            tension: null,
                            friction: null
                        },
                        j = [0],
                        k = 0,
                        l = 1e-4,
                        m = .016;
                    for (a = parseFloat(a) || 500, b = parseFloat(b) || 20, e = e || null, i.tension = a, i.friction = b, f = null !== e, f ? (k = d(a, b), g = k / e * m) : g = m; h = c(h || i, g), j.push(1 + h.x), k += 16, Math.abs(h.x) > l && Math.abs(h.v) > l;);
                    return f ? function(a) {
                        return j[a * (j.length - 1) | 0]
                    } : k
                }
            }();
            t.Easings = {
                linear: function(a) {
                    return a
                },
                swing: function(a) {
                    return .5 - Math.cos(a * Math.PI) / 2
                },
                spring: function(a) {
                    return 1 - Math.cos(4.5 * a * Math.PI) * Math.exp(6 * -a)
                }
            }, n.each([
                ["ease", [.25, .1, .25, 1]],
                ["ease-in", [.42, 0, 1, 1]],
                ["ease-out", [0, 0, .58, 1]],
                ["ease-in-out", [.42, 0, .58, 1]],
                ["easeInSine", [.47, 0, .745, .715]],
                ["easeOutSine", [.39, .575, .565, 1]],
                ["easeInOutSine", [.445, .05, .55, .95]],
                ["easeInQuad", [.55, .085, .68, .53]],
                ["easeOutQuad", [.25, .46, .45, .94]],
                ["easeInOutQuad", [.455, .03, .515, .955]],
                ["easeInCubic", [.55, .055, .675, .19]],
                ["easeOutCubic", [.215, .61, .355, 1]],
                ["easeInOutCubic", [.645, .045, .355, 1]],
                ["easeInQuart", [.895, .03, .685, .22]],
                ["easeOutQuart", [.165, .84, .44, 1]],
                ["easeInOutQuart", [.77, 0, .175, 1]],
                ["easeInQuint", [.755, .05, .855, .06]],
                ["easeOutQuint", [.23, 1, .32, 1]],
                ["easeInOutQuint", [.86, 0, .07, 1]],
                ["easeInExpo", [.95, .05, .795, .035]],
                ["easeOutExpo", [.19, 1, .22, 1]],
                ["easeInOutExpo", [1, 0, 0, 1]],
                ["easeInCirc", [.6, .04, .98, .335]],
                ["easeOutCirc", [.075, .82, .165, 1]],
                ["easeInOutCirc", [.785, .135, .15, .86]]
            ], function(a, b) {
                t.Easings[b[0]] = j.apply(null, b[1])
            });
            var v = t.CSS = {
                RegEx: {
                    isHex: /^#([A-f\d]{3}){1,2}$/i,
                    valueUnwrap: /^[A-z]+\((.*)\)$/i,
                    wrappedValueAlreadyExtracted: /[0-9.]+ [0-9.]+ [0-9.]+( [0-9.]+)?/,
                    valueSplit: /([A-z]+\(.+\))|(([A-z0-9#-.]+?)(?=\s|$))/gi
                },
                Lists: {
                    colors: ["fill", "stroke", "stopColor", "color", "backgroundColor", "borderColor", "borderTopColor", "borderRightColor", "borderBottomColor", "borderLeftColor", "outlineColor"],
                    transformsBase: ["translateX", "translateY", "scale", "scaleX", "scaleY", "skewX", "skewY", "rotateZ"],
                    transforms3D: ["transformPerspective", "translateZ", "scaleZ", "rotateX", "rotateY"]
                },
                Hooks: {
                    templates: {
                        textShadow: ["Color X Y Blur", "black 0px 0px 0px"],
                        boxShadow: ["Color X Y Blur Spread", "black 0px 0px 0px 0px"],
                        clip: ["Top Right Bottom Left", "0px 0px 0px 0px"],
                        backgroundPosition: ["X Y", "0% 0%"],
                        transformOrigin: ["X Y Z", "50% 50% 0px"],
                        perspectiveOrigin: ["X Y", "50% 50%"]
                    },
                    registered: {},
                    register: function() {
                        for (var a = 0; a < v.Lists.colors.length; a++) v.Hooks.templates[v.Lists.colors[a]] = ["Red Green Blue Alpha", "255 255 255 1"];
                        var b, c, d;
                        if (o)
                            for (b in v.Hooks.templates) {
                                c = v.Hooks.templates[b], d = c[0].split(" ");
                                var e = c[1].match(v.RegEx.valueSplit);
                                "Color" === d[0] && (d.push(d.shift()), e.push(e.shift()), v.Hooks.templates[b] = [d.join(" "), e.join(" ")])
                            }
                        for (b in v.Hooks.templates) {
                            c = v.Hooks.templates[b], d = c[0].split(" ");
                            for (var a in d) {
                                var f = b + d[a],
                                    g = a;
                                v.Hooks.registered[f] = [b, g]
                            }
                        }
                    },
                    getRoot: function(a) {
                        var b = v.Hooks.registered[a];
                        return b ? b[0] : a
                    },
                    cleanRootPropertyValue: function(a, b) {
                        return v.RegEx.valueUnwrap.test(b) && (b = b.match(v.Hooks.RegEx.valueUnwrap)[1]), v.Values.isCSSNullValue(b) && (b = v.Hooks.templates[a][1]), b
                    },
                    extractValue: function(a, b) {
                        var c = v.Hooks.registered[a];
                        if (c) {
                            var d = c[0],
                                e = c[1];
                            return b = v.Hooks.cleanRootPropertyValue(d, b), b.toString().match(v.RegEx.valueSplit)[e]
                        }
                        return b
                    },
                    injectValue: function(a, b, c) {
                        var d = v.Hooks.registered[a];
                        if (d) {
                            var e, f, g = d[0],
                                h = d[1];
                            return c = v.Hooks.cleanRootPropertyValue(g, c), e = c.toString().match(v.RegEx.valueSplit), e[h] = b, f = e.join(" ")
                        }
                        return c
                    }
                },
                Normalizations: {
                    registered: {
                        clip: function(a, b, c) {
                            switch (a) {
                                case "name":
                                    return "clip";
                                case "extract":
                                    var d;
                                    return v.RegEx.wrappedValueAlreadyExtracted.test(c) ? d = c : (d = c.toString().match(v.RegEx.valueUnwrap), d = d ? d[1].replace(/,(\s+)?/g, " ") : c), d;
                                case "inject":
                                    return "rect(" + c + ")"
                            }
                        },
                        opacity: function(a, b, c) {
                            if (8 >= o) switch (a) {
                                case "name":
                                    return "filter";
                                case "extract":
                                    var d = c.toString().match(/alpha\(opacity=(.*)\)/i);
                                    return c = d ? d[1] / 100 : 1;
                                case "inject":
                                    return b.style.zoom = 1, parseFloat(c) >= 1 ? "" : "alpha(opacity=" + parseInt(100 * parseFloat(c), 10) + ")"
                            } else switch (a) {
                                case "name":
                                    return "opacity";
                                case "extract":
                                    return c;
                                case "inject":
                                    return c
                            }
                        }
                    },
                    register: function() {
                        9 >= o || t.State.isGingerbread || (v.Lists.transformsBase = v.Lists.transformsBase.concat(v.Lists.transforms3D));
                        for (var a = 0; a < v.Lists.transformsBase.length; a++) ! function() {
                            var b = v.Lists.transformsBase[a];
                            v.Normalizations.registered[b] = function(a, c, d) {
                                switch (a) {
                                    case "name":
                                        return "transform";
                                    case "extract":
                                        return h(c) === e || h(c).transformCache[b] === e ? /^scale/i.test(b) ? 1 : 0 : h(c).transformCache[b].replace(/[()]/g, "");
                                    case "inject":
                                        var f = !1;
                                        switch (b.substr(0, b.length - 1)) {
                                            case "translate":
                                                f = !/(%|px|em|rem|vw|vh|\d)$/i.test(d);
                                                break;
                                            case "scal":
                                            case "scale":
                                                t.State.isAndroid && h(c).transformCache[b] === e && 1 > d && (d = 1), f = !/(\d)$/i.test(d);
                                                break;
                                            case "skew":
                                                f = !/(deg|\d)$/i.test(d);
                                                break;
                                            case "rotate":
                                                f = !/(deg|\d)$/i.test(d)
                                        }
                                        return f || (h(c).transformCache[b] = "(" + d + ")"), h(c).transformCache[b]
                                }
                            }
                        }();
                        for (var a = 0; a < v.Lists.colors.length; a++) ! function() {
                            var b = v.Lists.colors[a];
                            v.Normalizations.registered[b] = function(a, c, d) {
                                switch (a) {
                                    case "name":
                                        return b;
                                    case "extract":
                                        var f;
                                        if (v.RegEx.wrappedValueAlreadyExtracted.test(d)) f = d;
                                        else {
                                            var g, h = {
                                                black: "rgb(0, 0, 0)",
                                                blue: "rgb(0, 0, 255)",
                                                gray: "rgb(128, 128, 128)",
                                                green: "rgb(0, 128, 0)",
                                                red: "rgb(255, 0, 0)",
                                                white: "rgb(255, 255, 255)"
                                            };
                                            /^[A-z]+$/i.test(d) ? g = h[d] !== e ? h[d] : h.black : v.RegEx.isHex.test(d) ? g = "rgb(" + v.Values.hexToRgb(d).join(" ") + ")" : /^rgba?\(/i.test(d) || (g = h.black), f = (g || d).toString().match(v.RegEx.valueUnwrap)[1].replace(/,(\s+)?/g, " ")
                                        }
                                        return 8 >= o || 3 !== f.split(" ").length || (f += " 1"), f;
                                    case "inject":
                                        return 8 >= o ? 4 === d.split(" ").length && (d = d.split(/\s+/).slice(0, 3).join(" ")) : 3 === d.split(" ").length && (d += " 1"), (8 >= o ? "rgb" : "rgba") + "(" + d.replace(/\s+/g, ",").replace(/\.(\d)+(?=,)/g, "") + ")"
                                }
                            }
                        }()
                    }
                },
                Names: {
                    camelCase: function(a) {
                        return a.replace(/-(\w)/g, function(a, b) {
                            return b.toUpperCase()
                        })
                    },
                    SVGAttribute: function(a) {
                        var b = "width|height|x|y|cx|cy|r|rx|ry|x1|x2|y1|y2";
                        return (o || t.State.isAndroid && !t.State.isChrome) && (b += "|transform"), new RegExp("^(" + b + ")$", "i").test(a)
                    },
                    prefixCheck: function(a) {
                        if (t.State.prefixMatches[a]) return [t.State.prefixMatches[a], !0];
                        for (var b = ["", "Webkit", "Moz", "ms", "O"], c = 0, d = b.length; d > c; c++) {
                            var e;
                            if (e = 0 === c ? a : b[c] + a.replace(/^\w/, function(a) {
                                    return a.toUpperCase()
                                }), q.isString(t.State.prefixElement.style[e])) return t.State.prefixMatches[a] = e, [e, !0]
                        }
                        return [a, !1]
                    }
                },
                Values: {
                    hexToRgb: function(a) {
                        var b, c = /^#?([a-f\d])([a-f\d])([a-f\d])$/i,
                            d = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i;
                        return a = a.replace(c, function(a, b, c, d) {
                            return b + b + c + c + d + d
                        }), b = d.exec(a), b ? [parseInt(b[1], 16), parseInt(b[2], 16), parseInt(b[3], 16)] : [0, 0, 0]
                    },
                    isCSSNullValue: function(a) {
                        return 0 == a || /^(none|auto|transparent|(rgba\(0, ?0, ?0, ?0\)))$/i.test(a)
                    },
                    getUnitType: function(a) {
                        return /^(rotate|skew)/i.test(a) ? "deg" : /(^(scale|scaleX|scaleY|scaleZ|alpha|flexGrow|flexHeight|zIndex|fontWeight)$)|((opacity|red|green|blue|alpha)$)/i.test(a) ? "" : "px"
                    },
                    getDisplayType: function(a) {
                        var b = a.tagName.toString().toLowerCase();
                        return /^(b|big|i|small|tt|abbr|acronym|cite|code|dfn|em|kbd|strong|samp|var|a|bdo|br|img|map|object|q|script|span|sub|sup|button|input|label|select|textarea)$/i.test(b) ? "inline" : /^(li)$/i.test(b) ? "list-item" : /^(tr)$/i.test(b) ? "table-row" : "block"
                    },
                    addClass: function(a, b) {
                        a.classList ? a.classList.add(b) : a.className += (a.className.length ? " " : "") + b
                    },
                    removeClass: function(a, b) {
                        a.classList ? a.classList.remove(b) : a.className = a.className.toString().replace(new RegExp("(^|\\s)" + b.split(" ").join("|") + "(\\s|$)", "gi"), " ")
                    }
                },
                getPropertyValue: function(a, b, d, f) {
                    function g(a, b) {
                        function d() {
                            j && v.setPropertyValue(a, "display", "none")
                        }
                        var i = 0;
                        if (8 >= o) i = n.css(a, b);
                        else {
                            var j = !1;
                            if (/^(width|height)$/.test(b) && 0 === v.getPropertyValue(a, "display") && (j = !0, v.setPropertyValue(a, "display", v.Values.getDisplayType(a))), !f) {
                                if ("height" === b && "border-box" !== v.getPropertyValue(a, "boxSizing").toString().toLowerCase()) {
                                    var k = a.offsetHeight - (parseFloat(v.getPropertyValue(a, "borderTopWidth")) || 0) - (parseFloat(v.getPropertyValue(a, "borderBottomWidth")) || 0) - (parseFloat(v.getPropertyValue(a, "paddingTop")) || 0) - (parseFloat(v.getPropertyValue(a, "paddingBottom")) || 0);
                                    return d(), k
                                }
                                if ("width" === b && "border-box" !== v.getPropertyValue(a, "boxSizing").toString().toLowerCase()) {
                                    var l = a.offsetWidth - (parseFloat(v.getPropertyValue(a, "borderLeftWidth")) || 0) - (parseFloat(v.getPropertyValue(a, "borderRightWidth")) || 0) - (parseFloat(v.getPropertyValue(a, "paddingLeft")) || 0) - (parseFloat(v.getPropertyValue(a, "paddingRight")) || 0);
                                    return d(), l
                                }
                            }
                            var m;
                            m = h(a) === e ? c.getComputedStyle(a, null) : h(a).computedStyle ? h(a).computedStyle : h(a).computedStyle = c.getComputedStyle(a, null), (o || t.State.isFirefox) && "borderColor" === b && (b = "borderTopColor"), i = 9 === o && "filter" === b ? m.getPropertyValue(b) : m[b], ("" === i || null === i) && (i = a.style[b]), d()
                        }
                        if ("auto" === i && /^(top|right|bottom|left)$/i.test(b)) {
                            var p = g(a, "position");
                            ("fixed" === p || "absolute" === p && /top|left/i.test(b)) && (i = n(a).position()[b] + "px")
                        }
                        return i
                    }
                    var i;
                    if (v.Hooks.registered[b]) {
                        var j = b,
                            k = v.Hooks.getRoot(j);
                        d === e && (d = v.getPropertyValue(a, v.Names.prefixCheck(k)[0])), v.Normalizations.registered[k] && (d = v.Normalizations.registered[k]("extract", a, d)), i = v.Hooks.extractValue(j, d)
                    } else if (v.Normalizations.registered[b]) {
                        var l, m;
                        l = v.Normalizations.registered[b]("name", a), "transform" !== l && (m = g(a, v.Names.prefixCheck(l)[0]), v.Values.isCSSNullValue(m) && v.Hooks.templates[b] && (m = v.Hooks.templates[b][1])), i = v.Normalizations.registered[b]("extract", a, m)
                    }
                    return /^[\d-]/.test(i) || (i = h(a) && h(a).isSVG && v.Names.SVGAttribute(b) ? /^(height|width)$/i.test(b) ? a.getBBox()[b] : a.getAttribute(b) : g(a, v.Names.prefixCheck(b)[0])), v.Values.isCSSNullValue(i) && (i = 0), t.debug >= 2 && console.log("Get " + b + ": " + i), i
                },
                setPropertyValue: function(a, b, d, e, f) {
                    var g = b;
                    if ("scroll" === b) f.container ? f.container["scroll" + f.direction] = d : "Left" === f.direction ? c.scrollTo(d, f.alternateValue) : c.scrollTo(f.alternateValue, d);
                    else if (v.Normalizations.registered[b] && "transform" === v.Normalizations.registered[b]("name", a)) v.Normalizations.registered[b]("inject", a, d), g = "transform", d = h(a).transformCache[b];
                    else {
                        if (v.Hooks.registered[b]) {
                            var i = b,
                                j = v.Hooks.getRoot(b);
                            e = e || v.getPropertyValue(a, j), d = v.Hooks.injectValue(i, d, e), b = j
                        }
                        if (v.Normalizations.registered[b] && (d = v.Normalizations.registered[b]("inject", a, d), b = v.Normalizations.registered[b]("name", a)), g = v.Names.prefixCheck(b)[0], 8 >= o) try {
                            a.style[g] = d
                        } catch (k) {
                            t.debug && console.log("Browser does not support [" + d + "] for [" + g + "]")
                        } else h(a) && h(a).isSVG && v.Names.SVGAttribute(b) ? a.setAttribute(b, d) : a.style[g] = d;
                        t.debug >= 2 && console.log("Set " + b + " (" + g + "): " + d)
                    }
                    return [g, d]
                },
                flushTransformCache: function(a) {
                    function b(b) {
                        return parseFloat(v.getPropertyValue(a, b))
                    }
                    var c = "";
                    if ((o || t.State.isAndroid && !t.State.isChrome) && h(a).isSVG) {
                        var d = {
                            translate: [b("translateX"), b("translateY")],
                            skewX: [b("skewX")],
                            skewY: [b("skewY")],
                            scale: 1 !== b("scale") ? [b("scale"), b("scale")] : [b("scaleX"), b("scaleY")],
                            rotate: [b("rotateZ"), 0, 0]
                        };
                        n.each(h(a).transformCache, function(a) {
                            /^translate/i.test(a) ? a = "translate" : /^scale/i.test(a) ? a = "scale" : /^rotate/i.test(a) && (a = "rotate"), d[a] && (c += a + "(" + d[a].join(" ") + ") ", delete d[a])
                        })
                    } else {
                        var e, f;
                        n.each(h(a).transformCache, function(b) {
                            return e = h(a).transformCache[b], "transformPerspective" === b ? (f = e, !0) : (9 === o && "rotateZ" === b && (b = "rotate"), void(c += b + e + " "))
                        }), f && (c = "perspective" + f + " " + c)
                    }
                    v.setPropertyValue(a, "transform", c)
                }
            };
            v.Hooks.register(), v.Normalizations.register(), t.hook = function(a, b, c) {
                var d = e;
                return q.isWrapped(a) && (a = [].slice.call(a)), n.each(g(a), function(a, f) {
                    if (h(f) === e && t.init(f), c === e) d === e && (d = t.CSS.getPropertyValue(f, b));
                    else {
                        var g = t.CSS.setPropertyValue(f, b, c);
                        "transform" === g[0] && t.CSS.flushTransformCache(f), d = g
                    }
                }), d
            };
            var w = function() {
                function a() {
                    return i ? B.promise || null : j
                }

                function b() {
                    function a() {
                        function a(a, b) {
                            var c = e,
                                d = e,
                                f = e;
                            return q.isArray(a) ? (c = a[0], !q.isArray(a[1]) && /^[\d-]/.test(a[1]) || q.isFunction(a[1]) || v.RegEx.isHex.test(a[1]) ? f = a[1] : (q.isString(a[1]) && !v.RegEx.isHex.test(a[1]) || q.isArray(a[1])) && (d = b ? a[1] : k(a[1], i.duration), a[2] !== e && (f = a[2]))) : c = a, b || (d = d || i.easing), q.isFunction(c) && (c = c.call(g, y, x)), q.isFunction(f) && (f = f.call(g, y, x)), [c || 0, d, f]
                        }

                        function m(a, b) {
                            var c, d;
                            return d = (b || 0).toString().toLowerCase().replace(/[%A-z]+$/, function(a) {
                                return c = a, ""
                            }), c || (c = v.Values.getUnitType(a)), [d, c]
                        }

                        function o() {
                            var a = {
                                    myParent: g.parentNode || d.body,
                                    position: v.getPropertyValue(g, "position"),
                                    fontSize: v.getPropertyValue(g, "fontSize")
                                },
                                b = a.position === I.lastPosition && a.myParent === I.lastParent,
                                e = a.fontSize === I.lastFontSize;
                            I.lastParent = a.myParent, I.lastPosition = a.position, I.lastFontSize = a.fontSize;
                            var f = 100,
                                i = {};
                            if (e && b) i.emToPx = I.lastEmToPx, i.percentToPxWidth = I.lastPercentToPxWidth, i.percentToPxHeight = I.lastPercentToPxHeight;
                            else {
                                var j = h(g).isSVG ? d.createElementNS("http://www.w3.org/2000/svg", "rect") : d.createElement("div");
                                t.init(j), a.myParent.appendChild(j), n.each(["overflow", "overflowX", "overflowY"], function(a, b) {
                                    t.CSS.setPropertyValue(j, b, "hidden")
                                }), t.CSS.setPropertyValue(j, "position", a.position), t.CSS.setPropertyValue(j, "fontSize", a.fontSize), t.CSS.setPropertyValue(j, "boxSizing", "content-box"), n.each(["minWidth", "maxWidth", "width", "minHeight", "maxHeight", "height"], function(a, b) {
                                    t.CSS.setPropertyValue(j, b, f + "%")
                                }), t.CSS.setPropertyValue(j, "paddingLeft", f + "em"), i.percentToPxWidth = I.lastPercentToPxWidth = (parseFloat(v.getPropertyValue(j, "width", null, !0)) || 1) / f, i.percentToPxHeight = I.lastPercentToPxHeight = (parseFloat(v.getPropertyValue(j, "height", null, !0)) || 1) / f, i.emToPx = I.lastEmToPx = (parseFloat(v.getPropertyValue(j, "paddingLeft")) || 1) / f, a.myParent.removeChild(j)
                            }
                            return null === I.remToPx && (I.remToPx = parseFloat(v.getPropertyValue(d.body, "fontSize")) || 16), null === I.vwToPx && (I.vwToPx = parseFloat(c.innerWidth) / 100, I.vhToPx = parseFloat(c.innerHeight) / 100), i.remToPx = I.remToPx, i.vwToPx = I.vwToPx, i.vhToPx = I.vhToPx, t.debug >= 1 && console.log("Unit ratios: " + JSON.stringify(i), g), i
                        }
                        if (i.begin && 0 === y) try {
                            i.begin.call(p, p)
                        } catch (r) {
                            setTimeout(function() {
                                throw r
                            }, 1)
                        }
                        if ("scroll" === C) {
                            var w, z, A, D = /^x$/i.test(i.axis) ? "Left" : "Top",
                                E = parseFloat(i.offset) || 0;
                            i.container ? q.isWrapped(i.container) || q.isNode(i.container) ? (i.container = i.container[0] || i.container, w = i.container["scroll" + D], A = w + n(g).position()[D.toLowerCase()] + E) : i.container = null : (w = t.State.scrollAnchor[t.State["scrollProperty" + D]], z = t.State.scrollAnchor[t.State["scrollProperty" + ("Left" === D ? "Top" : "Left")]], A = n(g).offset()[D.toLowerCase()] + E), j = {
                                scroll: {
                                    rootPropertyValue: !1,
                                    startValue: w,
                                    currentValue: w,
                                    endValue: A,
                                    unitType: "",
                                    easing: i.easing,
                                    scrollData: {
                                        container: i.container,
                                        direction: D,
                                        alternateValue: z
                                    }
                                },
                                element: g
                            }, t.debug && console.log("tweensContainer (scroll): ", j.scroll, g)
                        } else if ("reverse" === C) {
                            if (!h(g).tweensContainer) return void n.dequeue(g, i.queue);
                            "none" === h(g).opts.display && (h(g).opts.display = "auto"), "hidden" === h(g).opts.visibility && (h(g).opts.visibility = "visible"), h(g).opts.loop = !1, h(g).opts.begin = null, h(g).opts.complete = null, u.easing || delete i.easing, u.duration || delete i.duration, i = n.extend({}, h(g).opts, i);
                            var F = n.extend(!0, {}, h(g).tweensContainer);
                            for (var G in F)
                                if ("element" !== G) {
                                    var H = F[G].startValue;
                                    F[G].startValue = F[G].currentValue = F[G].endValue, F[G].endValue = H, q.isEmptyObject(u) || (F[G].easing = i.easing), t.debug && console.log("reverse tweensContainer (" + G + "): " + JSON.stringify(F[G]), g)
                                }
                            j = F
                        } else if ("start" === C) {
                            var F;
                            h(g).tweensContainer && h(g).isAnimating === !0 && (F = h(g).tweensContainer), n.each(s, function(b, c) {
                                if (RegExp("^" + v.Lists.colors.join("$|^") + "$").test(b)) {
                                    var d = a(c, !0),
                                        f = d[0],
                                        g = d[1],
                                        h = d[2];
                                    if (v.RegEx.isHex.test(f)) {
                                        for (var i = ["Red", "Green", "Blue"], j = v.Values.hexToRgb(f), k = h ? v.Values.hexToRgb(h) : e, l = 0; l < i.length; l++) s[b + i[l]] = [j[l], g, k ? k[l] : k];
                                        delete s[b]
                                    }
                                }
                            });
                            for (var K in s) {
                                var L = a(s[K]),
                                    M = L[0],
                                    N = L[1],
                                    O = L[2];
                                K = v.Names.camelCase(K);
                                var P = v.Hooks.getRoot(K),
                                    Q = !1;
                                if (h(g).isSVG || v.Names.prefixCheck(P)[1] !== !1 || v.Normalizations.registered[P] !== e) {
                                    (i.display !== e && null !== i.display && "none" !== i.display || i.visibility && "hidden" !== i.visibility) && /opacity|filter/.test(K) && !O && 0 !== M && (O = 0), i._cacheValues && F && F[K] ? (O === e && (O = F[K].endValue + F[K].unitType), Q = h(g).rootPropertyValueCache[P]) : v.Hooks.registered[K] ? O === e ? (Q = v.getPropertyValue(g, P), O = v.getPropertyValue(g, K, Q)) : Q = v.Hooks.templates[P][1] : O === e && (O = v.getPropertyValue(g, K));
                                    var R, S, T, U = !1;
                                    if (R = m(K, O), O = R[0], T = R[1], R = m(K, M), M = R[0].replace(/^([+-\/*])=/, function(a, b) {
                                            return U = b, ""
                                        }), S = R[1], O = parseFloat(O) || 0, M = parseFloat(M) || 0, "%" === S && (/^(fontSize|lineHeight)$/.test(K) ? (M /= 100, S = "em") : /^scale/.test(K) ? (M /= 100, S = "") : /(Red|Green|Blue)$/i.test(K) && (M = M / 100 * 255, S = "")), /[\/*]/.test(U)) S = T;
                                    else if (T !== S && 0 !== O)
                                        if (0 === M) S = T;
                                        else {
                                            b = b || o();
                                            var V = /margin|padding|left|right|width|text|word|letter/i.test(K) || /X$/.test(K) || "x" === K ? "x" : "y";
                                            switch (T) {
                                                case "%":
                                                    O *= "x" === V ? b.percentToPxWidth : b.percentToPxHeight;
                                                    break;
                                                case "px":
                                                    break;
                                                default:
                                                    O *= b[T + "ToPx"]
                                            }
                                            switch (S) {
                                                case "%":
                                                    O *= 1 / ("x" === V ? b.percentToPxWidth : b.percentToPxHeight);
                                                    break;
                                                case "px":
                                                    break;
                                                default:
                                                    O *= 1 / b[S + "ToPx"]
                                            }
                                        }
                                    switch (U) {
                                        case "+":
                                            M = O + M;
                                            break;
                                        case "-":
                                            M = O - M;
                                            break;
                                        case "*":
                                            M = O * M;
                                            break;
                                        case "/":
                                            M = O / M
                                    }
                                    j[K] = {
                                        rootPropertyValue: Q,
                                        startValue: O,
                                        currentValue: O,
                                        endValue: M,
                                        unitType: S,
                                        easing: N
                                    }, t.debug && console.log("tweensContainer (" + K + "): " + JSON.stringify(j[K]), g)
                                } else t.debug && console.log("Skipping [" + P + "] due to a lack of browser support.")
                            }
                            j.element = g
                        }
                        j.element && (v.Values.addClass(g, "velocity-animating"), J.push(j), "" === i.queue && (h(g).tweensContainer = j, h(g).opts = i), h(g).isAnimating = !0, y === x - 1 ? (t.State.calls.length > 1e4 && (t.State.calls = f(t.State.calls)), t.State.calls.push([J, p, i, null, B.resolver]), t.State.isTicking === !1 && (t.State.isTicking = !0, l())) : y++)
                    }
                    var b, g = this,
                        i = n.extend({}, t.defaults, u),
                        j = {};
                    if (h(g) === e && t.init(g), parseFloat(i.delay) && i.queue !== !1 && n.queue(g, i.queue, function(a) {
                            t.velocityQueueEntryFlag = !0, h(g).delayTimer = {
                                setTimeout: setTimeout(a, parseFloat(i.delay)),
                                next: a
                            }
                        }), t.mock === !0) i.duration = 1;
                    else switch (i.duration.toString().toLowerCase()) {
                        case "fast":
                            i.duration = 200;
                            break;
                        case "normal":
                            i.duration = r;
                            break;
                        case "slow":
                            i.duration = 600;
                            break;
                        default:
                            i.duration = parseFloat(i.duration) || 1
                    }
                    i.easing = k(i.easing, i.duration), i.begin && !q.isFunction(i.begin) && (i.begin = null), i.progress && !q.isFunction(i.progress) && (i.progress = null), i.complete && !q.isFunction(i.complete) && (i.complete = null), i.display !== e && null !== i.display && (i.display = i.display.toString().toLowerCase(), "auto" === i.display && (i.display = t.CSS.Values.getDisplayType(g))), i.visibility && (i.visibility = i.visibility.toString().toLowerCase()), i.mobileHA = i.mobileHA && t.State.isMobile && !t.State.isGingerbread, i.queue === !1 ? i.delay ? setTimeout(a, i.delay) : a() : n.queue(g, i.queue, function(b, c) {
                        return c === !0 ? (B.promise && B.resolver(p), !0) : (t.velocityQueueEntryFlag = !0, void a(b))
                    }), "" !== i.queue && "fx" !== i.queue || "inprogress" === n.queue(g)[0] || n.dequeue(g)
                }
                var i, j, o, p, s, u, w = arguments[0] && (n.isPlainObject(arguments[0].properties) && !arguments[0].properties.names || q.isString(arguments[0].properties));
                if (q.isWrapped(this) ? (i = !1, o = 0, p = this, j = this) : (i = !0, o = 1, p = w ? arguments[0].elements : arguments[0]), p = q.isWrapped(p) ? [].slice.call(p) : p) {
                    w ? (s = arguments[0].properties, u = arguments[0].options) : (s = arguments[o], u = arguments[o + 1]);
                    var x = q.isArray(p) || q.isNodeList(p) ? p.length : 1,
                        y = 0;
                    if ("stop" !== s && !n.isPlainObject(u)) {
                        var z = o + 1;
                        u = {};
                        for (var A = z; A < arguments.length; A++) !q.isArray(arguments[A]) && /^\d/.test(arguments[A]) ? u.duration = parseFloat(arguments[A]) : q.isString(arguments[A]) || q.isArray(arguments[A]) ? u.easing = arguments[A] : q.isFunction(arguments[A]) && (u.complete = arguments[A])
                    }
                    var B = {
                        promise: null,
                        resolver: null,
                        rejecter: null
                    };
                    i && t.Promise && (B.promise = new t.Promise(function(a, b) {
                        B.resolver = a, B.rejecter = b
                    }));
                    var C;
                    switch (s) {
                        case "scroll":
                            C = "scroll";
                            break;
                        case "reverse":
                            C = "reverse";
                            break;
                        case "stop":
                            n.each(g(p), function(a, b) {
                                h(b) && h(b).delayTimer && (clearTimeout(h(b).delayTimer.setTimeout), h(b).delayTimer.next && h(b).delayTimer.next(), delete h(b).delayTimer)
                            });
                            var D = [];
                            return n.each(t.State.calls, function(a, b) {
                                b && n.each(g(b[1]), function(c, d) {
                                    var f = q.isString(u) ? u : "";
                                    return u !== e && b[2].queue !== f ? !0 : void n.each(g(p), function(b, c) {
                                        c === d && (u !== e && (n.each(n.queue(c, f), function(a, b) {
                                            q.isFunction(b) && b(null, !0)
                                        }), n.queue(c, f, [])), h(c) && "" === f && n.each(h(c).tweensContainer, function(a, b) {
                                            b.endValue = b.currentValue
                                        }), D.push(a))
                                    })
                                })
                            }), n.each(D, function(a, b) {
                                m(b, !0)
                            }), B.promise && B.resolver(p), a();
                        default:
                            if (!n.isPlainObject(s) || q.isEmptyObject(s)) {
                                if (q.isString(s) && t.Sequences[s]) {
                                    var E = n.extend({}, u),
                                        F = E.duration,
                                        G = E.delay || 0;
                                    return E.backwards === !0 && (p = (q.isWrapped(p) ? [].slice.call(p) : p).reverse()), n.each(g(p), function(a, b) {
                                        parseFloat(E.stagger) ? E.delay = G + parseFloat(E.stagger) * a : q.isFunction(E.stagger) && (E.delay = G + E.stagger.call(b, a, x)), E.drag && (E.duration = parseFloat(F) || (/^(callout|transition)/.test(s) ? 1e3 : r), E.duration = Math.max(E.duration * (E.backwards ? 1 - a / x : (a + 1) / x), .75 * E.duration, 200)), t.Sequences[s].call(b, b, E || {}, a, x, p, B.promise ? B : e)
                                    }), a()
                                }
                                var H = "Velocity: First argument (" + s + ") was not a property map, a known action, or a registered sequence. Aborting.";
                                return B.promise ? B.rejecter(new Error(H)) : console.log(H), a()
                            }
                            C = "start"
                    }
                    var I = {
                            lastParent: null,
                            lastPosition: null,
                            lastFontSize: null,
                            lastPercentToPxWidth: null,
                            lastPercentToPxHeight: null,
                            lastEmToPx: null,
                            remToPx: null,
                            vwToPx: null,
                            vhToPx: null
                        },
                        J = [];
                    n.each(g(p), function(a, c) {
                        q.isNode(c) && b.call(c)
                    });
                    var K, E = n.extend({}, t.defaults, u);
                    if (E.loop = parseInt(E.loop), K = 2 * E.loop - 1, E.loop)
                        for (var L = 0; K > L; L++) {
                            var M = {
                                delay: E.delay
                            };
                            L === K - 1 && (M.display = E.display, M.visibility = E.visibility, M.complete = E.complete), t(p, "reverse", M)
                        }
                    return a()
                }
            };
            t = n.extend(w, t), t.animate = w;
            var x = c.requestAnimationFrame || p;
            t.State.isMobile || d.hidden === e || d.addEventListener("visibilitychange", function() {
                d.hidden ? (x = function(a) {
                    return setTimeout(function() {
                        a(!0)
                    }, 16)
                }, l()) : x = c.requestAnimationFrame || p
            });
            var y;
            return a && a.fn !== e ? y = a : c.Zepto && (y = c.Zepto), (y || c).Velocity = t, y && (y.fn.velocity = w, y.fn.velocity.defaults = t.defaults), n.each(["Down", "Up"], function(a, b) {
                t.Sequences["slide" + b] = function(a, c, d, f, g, h) {
                    var i = n.extend({}, c),
                        j = i.begin,
                        k = i.complete,
                        l = {
                            height: "",
                            marginTop: "",
                            marginBottom: "",
                            paddingTop: "",
                            paddingBottom: ""
                        },
                        m = {};
                    i.display === e && (i.display = "Down" === b ? "inline" === t.CSS.Values.getDisplayType(a) ? "inline-block" : "block" : "none"), i.begin = function(a) {
                        j && j.call(a, a), m.overflowY = a.style.overflowY, a.style.overflowY = "hidden";
                        for (var c in l) {
                            m[c] = a.style[c];
                            var d = t.CSS.getPropertyValue(a, c);
                            l[c] = "Down" === b ? [d, 0] : [0, d]
                        }
                    }, i.complete = function(a) {
                        for (var b in m) a.style[b] = m[b];
                        k && k.call(a, a), h && h.resolver(g || a)
                    }, t(a, l, i)
                }
            }), n.each(["In", "Out"], function(a, b) {
                t.Sequences["fade" + b] = function(a, c, d, f, g, h) {
                    var i = n.extend({}, c),
                        j = {
                            opacity: "In" === b ? 1 : 0
                        },
                        k = i.complete;
                    i.complete = d !== f - 1 ? i.begin = null : function() {
                        k && k.call(a, a), h && h.resolver(g || a)
                    }, i.display === e && (i.display = "In" === b ? "auto" : "none"), t(this, j, i)
                }
            }), t
        }(a || window, window, document)
    }), function(a, b) {
        "use strict";
        a.quicksearch = {
            defaults: {
                delay: 100,
                selector: null,
                stripeRows: null,
                loader: null,
                noResults: "",
                matchedResultsCount: 0,
                bind: "keyup search input",
                removeDiacritics: !1,
                minValLength: 0,
                onBefore: a.noop,
                onAfter: a.noop,
                onValTooSmall: a.noop,
                show: function() {
                    a(this).show()
                },
                hide: function() {
                    a(this).hide()
                },
                prepareQuery: function(a) {
                    return a.toLowerCase().split(" ")
                },
                testQuery: function(a, b) {
                    for (var c = 0; c < a.length; c += 1)
                        if (-1 === b.indexOf(a[c])) return !1;
                    return !0
                }
            },
            diacriticsRemovalMap: [{
                base: "A",
                letters: /[\u0041\u24B6\uFF21\u00C0\u00C1\u00C2\u1EA6\u1EA4\u1EAA\u1EA8\u00C3\u0100\u0102\u1EB0\u1EAE\u1EB4\u1EB2\u0226\u01E0\u00C4\u01DE\u1EA2\u00C5\u01FA\u01CD\u0200\u0202\u1EA0\u1EAC\u1EB6\u1E00\u0104\u023A\u2C6F]/g
            }, {
                base: "AA",
                letters: /[\uA732]/g
            }, {
                base: "AE",
                letters: /[\u00C6\u01FC\u01E2]/g
            }, {
                base: "AO",
                letters: /[\uA734]/g
            }, {
                base: "AU",
                letters: /[\uA736]/g
            }, {
                base: "AV",
                letters: /[\uA738\uA73A]/g
            }, {
                base: "AY",
                letters: /[\uA73C]/g
            }, {
                base: "B",
                letters: /[\u0042\u24B7\uFF22\u1E02\u1E04\u1E06\u0243\u0182\u0181]/g
            }, {
                base: "C",
                letters: /[\u0043\u24B8\uFF23\u0106\u0108\u010A\u010C\u00C7\u1E08\u0187\u023B\uA73E]/g
            }, {
                base: "D",
                letters: /[\u0044\u24B9\uFF24\u1E0A\u010E\u1E0C\u1E10\u1E12\u1E0E\u0110\u018B\u018A\u0189\uA779]/g
            }, {
                base: "DZ",
                letters: /[\u01F1\u01C4]/g
            }, {
                base: "Dz",
                letters: /[\u01F2\u01C5]/g
            }, {
                base: "E",
                letters: /[\u0045\u24BA\uFF25\u00C8\u00C9\u00CA\u1EC0\u1EBE\u1EC4\u1EC2\u1EBC\u0112\u1E14\u1E16\u0114\u0116\u00CB\u1EBA\u011A\u0204\u0206\u1EB8\u1EC6\u0228\u1E1C\u0118\u1E18\u1E1A\u0190\u018E]/g
            }, {
                base: "F",
                letters: /[\u0046\u24BB\uFF26\u1E1E\u0191\uA77B]/g
            }, {
                base: "G",
                letters: /[\u0047\u24BC\uFF27\u01F4\u011C\u1E20\u011E\u0120\u01E6\u0122\u01E4\u0193\uA7A0\uA77D\uA77E]/g
            }, {
                base: "H",
                letters: /[\u0048\u24BD\uFF28\u0124\u1E22\u1E26\u021E\u1E24\u1E28\u1E2A\u0126\u2C67\u2C75\uA78D]/g
            }, {
                base: "I",
                letters: /[\u0049\u24BE\uFF29\u00CC\u00CD\u00CE\u0128\u012A\u012C\u0130\u00CF\u1E2E\u1EC8\u01CF\u0208\u020A\u1ECA\u012E\u1E2C\u0197]/g
            }, {
                base: "J",
                letters: /[\u004A\u24BF\uFF2A\u0134\u0248]/g
            }, {
                base: "K",
                letters: /[\u004B\u24C0\uFF2B\u1E30\u01E8\u1E32\u0136\u1E34\u0198\u2C69\uA740\uA742\uA744\uA7A2]/g
            }, {
                base: "L",
                letters: /[\u004C\u24C1\uFF2C\u013F\u0139\u013D\u1E36\u1E38\u013B\u1E3C\u1E3A\u0141\u023D\u2C62\u2C60\uA748\uA746\uA780]/g
            }, {
                base: "LJ",
                letters: /[\u01C7]/g
            }, {
                base: "Lj",
                letters: /[\u01C8]/g
            }, {
                base: "M",
                letters: /[\u004D\u24C2\uFF2D\u1E3E\u1E40\u1E42\u2C6E\u019C]/g
            }, {
                base: "N",
                letters: /[\u004E\u24C3\uFF2E\u01F8\u0143\u00D1\u1E44\u0147\u1E46\u0145\u1E4A\u1E48\u0220\u019D\uA790\uA7A4]/g
            }, {
                base: "NJ",
                letters: /[\u01CA]/g
            }, {
                base: "Nj",
                letters: /[\u01CB]/g
            }, {
                base: "O",
                letters: /[\u004F\u24C4\uFF2F\u00D2\u00D3\u00D4\u1ED2\u1ED0\u1ED6\u1ED4\u00D5\u1E4C\u022C\u1E4E\u014C\u1E50\u1E52\u014E\u022E\u0230\u00D6\u022A\u1ECE\u0150\u01D1\u020C\u020E\u01A0\u1EDC\u1EDA\u1EE0\u1EDE\u1EE2\u1ECC\u1ED8\u01EA\u01EC\u00D8\u01FE\u0186\u019F\uA74A\uA74C]/g
            }, {
                base: "OI",
                letters: /[\u01A2]/g
            }, {
                base: "OO",
                letters: /[\uA74E]/g
            }, {
                base: "OU",
                letters: /[\u0222]/g
            }, {
                base: "P",
                letters: /[\u0050\u24C5\uFF30\u1E54\u1E56\u01A4\u2C63\uA750\uA752\uA754]/g
            }, {
                base: "Q",
                letters: /[\u0051\u24C6\uFF31\uA756\uA758\u024A]/g
            }, {
                base: "R",
                letters: /[\u0052\u24C7\uFF32\u0154\u1E58\u0158\u0210\u0212\u1E5A\u1E5C\u0156\u1E5E\u024C\u2C64\uA75A\uA7A6\uA782]/g
            }, {
                base: "S",
                letters: /[\u0053\u24C8\uFF33\u1E9E\u015A\u1E64\u015C\u1E60\u0160\u1E66\u1E62\u1E68\u0218\u015E\u2C7E\uA7A8\uA784]/g
            }, {
                base: "T",
                letters: /[\u0054\u24C9\uFF34\u1E6A\u0164\u1E6C\u021A\u0162\u1E70\u1E6E\u0166\u01AC\u01AE\u023E\uA786]/g
            }, {
                base: "TZ",
                letters: /[\uA728]/g
            }, {
                base: "U",
                letters: /[\u0055\u24CA\uFF35\u00D9\u00DA\u00DB\u0168\u1E78\u016A\u1E7A\u016C\u00DC\u01DB\u01D7\u01D5\u01D9\u1EE6\u016E\u0170\u01D3\u0214\u0216\u01AF\u1EEA\u1EE8\u1EEE\u1EEC\u1EF0\u1EE4\u1E72\u0172\u1E76\u1E74\u0244]/g
            }, {
                base: "V",
                letters: /[\u0056\u24CB\uFF36\u1E7C\u1E7E\u01B2\uA75E\u0245]/g
            }, {
                base: "VY",
                letters: /[\uA760]/g
            }, {
                base: "W",
                letters: /[\u0057\u24CC\uFF37\u1E80\u1E82\u0174\u1E86\u1E84\u1E88\u2C72]/g
            }, {
                base: "X",
                letters: /[\u0058\u24CD\uFF38\u1E8A\u1E8C]/g
            }, {
                base: "Y",
                letters: /[\u0059\u24CE\uFF39\u1EF2\u00DD\u0176\u1EF8\u0232\u1E8E\u0178\u1EF6\u1EF4\u01B3\u024E\u1EFE]/g
            }, {
                base: "Z",
                letters: /[\u005A\u24CF\uFF3A\u0179\u1E90\u017B\u017D\u1E92\u1E94\u01B5\u0224\u2C7F\u2C6B\uA762]/g
            }, {
                base: "a",
                letters: /[\u0061\u24D0\uFF41\u1E9A\u00E0\u00E1\u00E2\u1EA7\u1EA5\u1EAB\u1EA9\u00E3\u0101\u0103\u1EB1\u1EAF\u1EB5\u1EB3\u0227\u01E1\u00E4\u01DF\u1EA3\u00E5\u01FB\u01CE\u0201\u0203\u1EA1\u1EAD\u1EB7\u1E01\u0105\u2C65\u0250]/g
            }, {
                base: "aa",
                letters: /[\uA733]/g
            }, {
                base: "ae",
                letters: /[\u00E6\u01FD\u01E3]/g
            }, {
                base: "ao",
                letters: /[\uA735]/g
            }, {
                base: "au",
                letters: /[\uA737]/g
            }, {
                base: "av",
                letters: /[\uA739\uA73B]/g
            }, {
                base: "ay",
                letters: /[\uA73D]/g
            }, {
                base: "b",
                letters: /[\u0062\u24D1\uFF42\u1E03\u1E05\u1E07\u0180\u0183\u0253]/g
            }, {
                base: "c",
                letters: /[\u0063\u24D2\uFF43\u0107\u0109\u010B\u010D\u00E7\u1E09\u0188\u023C\uA73F\u2184]/g
            }, {
                base: "d",
                letters: /[\u0064\u24D3\uFF44\u1E0B\u010F\u1E0D\u1E11\u1E13\u1E0F\u0111\u018C\u0256\u0257\uA77A]/g
            }, {
                base: "dz",
                letters: /[\u01F3\u01C6]/g
            }, {
                base: "e",
                letters: /[\u0065\u24D4\uFF45\u00E8\u00E9\u00EA\u1EC1\u1EBF\u1EC5\u1EC3\u1EBD\u0113\u1E15\u1E17\u0115\u0117\u00EB\u1EBB\u011B\u0205\u0207\u1EB9\u1EC7\u0229\u1E1D\u0119\u1E19\u1E1B\u0247\u025B\u01DD]/g
            }, {
                base: "f",
                letters: /[\u0066\u24D5\uFF46\u1E1F\u0192\uA77C]/g
            }, {
                base: "g",
                letters: /[\u0067\u24D6\uFF47\u01F5\u011D\u1E21\u011F\u0121\u01E7\u0123\u01E5\u0260\uA7A1\u1D79\uA77F]/g
            }, {
                base: "h",
                letters: /[\u0068\u24D7\uFF48\u0125\u1E23\u1E27\u021F\u1E25\u1E29\u1E2B\u1E96\u0127\u2C68\u2C76\u0265]/g
            }, {
                base: "hv",
                letters: /[\u0195]/g
            }, {
                base: "i",
                letters: /[\u0069\u24D8\uFF49\u00EC\u00ED\u00EE\u0129\u012B\u012D\u00EF\u1E2F\u1EC9\u01D0\u0209\u020B\u1ECB\u012F\u1E2D\u0268\u0131]/g
            }, {
                base: "j",
                letters: /[\u006A\u24D9\uFF4A\u0135\u01F0\u0249]/g
            }, {
                base: "k",
                letters: /[\u006B\u24DA\uFF4B\u1E31\u01E9\u1E33\u0137\u1E35\u0199\u2C6A\uA741\uA743\uA745\uA7A3]/g
            }, {
                base: "l",
                letters: /[\u006C\u24DB\uFF4C\u0140\u013A\u013E\u1E37\u1E39\u013C\u1E3D\u1E3B\u017F\u0142\u019A\u026B\u2C61\uA749\uA781\uA747]/g
            }, {
                base: "lj",
                letters: /[\u01C9]/g
            }, {
                base: "m",
                letters: /[\u006D\u24DC\uFF4D\u1E3F\u1E41\u1E43\u0271\u026F]/g
            }, {
                base: "n",
                letters: /[\u006E\u24DD\uFF4E\u01F9\u0144\u00F1\u1E45\u0148\u1E47\u0146\u1E4B\u1E49\u019E\u0272\u0149\uA791\uA7A5]/g
            }, {
                base: "nj",
                letters: /[\u01CC]/g
            }, {
                base: "o",
                letters: /[\u006F\u24DE\uFF4F\u00F2\u00F3\u00F4\u1ED3\u1ED1\u1ED7\u1ED5\u00F5\u1E4D\u022D\u1E4F\u014D\u1E51\u1E53\u014F\u022F\u0231\u00F6\u022B\u1ECF\u0151\u01D2\u020D\u020F\u01A1\u1EDD\u1EDB\u1EE1\u1EDF\u1EE3\u1ECD\u1ED9\u01EB\u01ED\u00F8\u01FF\u0254\uA74B\uA74D\u0275]/g
            }, {
                base: "oi",
                letters: /[\u01A3]/g
            }, {
                base: "ou",
                letters: /[\u0223]/g
            }, {
                base: "oo",
                letters: /[\uA74F]/g
            }, {
                base: "p",
                letters: /[\u0070\u24DF\uFF50\u1E55\u1E57\u01A5\u1D7D\uA751\uA753\uA755]/g
            }, {
                base: "q",
                letters: /[\u0071\u24E0\uFF51\u024B\uA757\uA759]/g
            }, {
                base: "r",
                letters: /[\u0072\u24E1\uFF52\u0155\u1E59\u0159\u0211\u0213\u1E5B\u1E5D\u0157\u1E5F\u024D\u027D\uA75B\uA7A7\uA783]/g
            }, {
                base: "s",
                letters: /[\u0073\u24E2\uFF53\u00DF\u015B\u1E65\u015D\u1E61\u0161\u1E67\u1E63\u1E69\u0219\u015F\u023F\uA7A9\uA785\u1E9B]/g
            }, {
                base: "t",
                letters: /[\u0074\u24E3\uFF54\u1E6B\u1E97\u0165\u1E6D\u021B\u0163\u1E71\u1E6F\u0167\u01AD\u0288\u2C66\uA787]/g
            }, {
                base: "tz",
                letters: /[\uA729]/g
            }, {
                base: "u",
                letters: /[\u0075\u24E4\uFF55\u00F9\u00FA\u00FB\u0169\u1E79\u016B\u1E7B\u016D\u00FC\u01DC\u01D8\u01D6\u01DA\u1EE7\u016F\u0171\u01D4\u0215\u0217\u01B0\u1EEB\u1EE9\u1EEF\u1EED\u1EF1\u1EE5\u1E73\u0173\u1E77\u1E75\u0289]/g
            }, {
                base: "v",
                letters: /[\u0076\u24E5\uFF56\u1E7D\u1E7F\u028B\uA75F\u028C]/g
            }, {
                base: "vy",
                letters: /[\uA761]/g
            }, {
                base: "w",
                letters: /[\u0077\u24E6\uFF57\u1E81\u1E83\u0175\u1E87\u1E85\u1E98\u1E89\u2C73]/g
            }, {
                base: "x",
                letters: /[\u0078\u24E7\uFF58\u1E8B\u1E8D]/g
            }, {
                base: "y",
                letters: /[\u0079\u24E8\uFF59\u1EF3\u00FD\u0177\u1EF9\u0233\u1E8F\u00FF\u1EF7\u1E99\u1EF5\u01B4\u024F\u1EFF]/g
            }, {
                base: "z",
                letters: /[\u007A\u24E9\uFF5A\u017A\u1E91\u017C\u017E\u1E93\u1E95\u01B6\u0225\u0240\u2C6C\uA763]/g
            }]
        }, a.fn.quicksearch = function(c, d) {
            this.removeDiacritics = function(b) {
                for (var c = a.quicksearch.diacriticsRemovalMap, d = 0; d < c.length; d++) b = b.replace(c[d].letters, c[d].base);
                return b
            };
            var e, f, g, h, i = "",
                j = "",
                k = this,
                l = a.extend({}, a.quicksearch.defaults, d);
            return l.noResults = l.noResults ? a(l.noResults) : a(), l.loader = l.loader ? a(l.loader) : a(), this.go = function() {
                var a, b = 0,
                    c = 0,
                    d = 0,
                    e = !0,
                    h = 0 === i.replace(" ", "").length;
                for (l.removeDiacritics && (i = k.removeDiacritics(i)), a = l.prepareQuery(i), b = 0, c = g.length; c > b; b++) a.length > 0 && a[0].length < l.minValLength ? (l.show.apply(g[b]), e = !1, d++) : h || l.testQuery(a, f[b], g[b]) ? (l.show.apply(g[b]), e = !1, d++) : l.hide.apply(g[b]);
                return e ? this.results(!1) : (this.results(!0), this.stripe()), this.matchedResultsCount = d, this.loader(!1), l.onAfter.call(this), j = i, this
            }, this.search = function(a) {
                i = a, k.trigger()
            }, this.reset = function() {
                i = "", this.loader(!0), l.onBefore.call(this), b.clearTimeout(e), e = b.setTimeout(function() {
                    k.go()
                }, l.delay)
            }, this.currentMatchedResults = function() {
                return this.matchedResultsCount
            }, this.stripe = function() {
                if ("object" == typeof l.stripeRows && null !== l.stripeRows) {
                    var b = l.stripeRows.join(" "),
                        c = l.stripeRows.length;
                    h.not(":hidden").each(function(d) {
                        a(this).removeClass(b).addClass(l.stripeRows[d % c])
                    })
                }
                return this
            }, this.strip_html = function(b) {
                var c = b.replace(new RegExp("<[^<]+\\>", "g"), "");
                return c = a.trim(c.toLowerCase())
            }, this.results = function(a) {
                return l.noResults.length && l.noResults[a ? "hide" : "show"](), this
            }, this.loader = function(a) {
                return l.loader.length && l.loader[a ? "show" : "hide"](), this
            }, this.cache = function(b) {
                b = "undefined" == typeof b ? !0 : b, h = l.noResults ? a(c).not(l.noResults) : a(c);
                var d = "string" == typeof l.selector ? h.find(l.selector) : a(c).not(l.noResults);
                return f = d.map(function() {
                    var a = k.strip_html(this.innerHTML);
                    return l.removeDiacritics ? k.removeDiacritics(a) : a
                }), g = h.map(function() {
                    return this
                }), i = i || this.val() || "", b && this.go(), this
            }, this.trigger = function() {
                return i.length < l.minValLength && i.length > j.length || i.length < l.minValLength - 1 && i.length < j.length ? (l.onValTooSmall.call(this, i), k.go()) : (this.loader(!0), l.onBefore.call(this), b.clearTimeout(e), e = b.setTimeout(function() {
                    k.go()
                }, l.delay)), this
            }, this.cache(), this.results(!0), this.stripe(), this.loader(!1), this.each(function() {
                a(this).on(l.bind, function() {
                    i = a(this).val(), k.trigger()
                }), a(this).on(l.resetBind, function() {
                    i = "", k.reset()
                })
            })
        }
    }(jQuery, this, document), function(a, b) {
        "use strict";
        "function" == typeof define && define.amd ? define(["jquery"], b) : "object" == typeof exports ? module.exports = b(require("jquery")) : a.bootbox = b(a.jQuery)
    }(this, function a(b, c) {
        "use strict";

        function d(a) {
            var b = q[o.locale];
            return b ? b[a] : q.en[a]
        }

        function e(a, c, d) {
            a.stopPropagation(), a.preventDefault();
            var e = b.isFunction(d) && d(a) === !1;
            e || c.modal("hide")
        }

        function f(a) {
            var b, c = 0;
            for (b in a) c++;
            return c
        }

        function g(a, c) {
            var d = 0;
            b.each(a, function(a, b) {
                c(a, b, d++)
            })
        }

        function h(a) {
            var c, d;
            if ("object" != typeof a) throw new Error("Please supply an object of options");
            if (!a.message) throw new Error("Please specify a message");
            return a = b.extend({}, o, a), a.buttons || (a.buttons = {}), a.backdrop = a.backdrop ? "static" : !1, c = a.buttons, d = f(c), g(c, function(a, e, f) {
                if (b.isFunction(e) && (e = c[a] = {
                        callback: e
                    }), "object" !== b.type(e)) throw new Error("button with key " + a + " must be an object");
                e.label || (e.label = a), e.className || (e.className = 2 >= d && f === d - 1 ? "btn-primary" : "btn-default")
            }), a
        }

        function i(a, b) {
            var c = a.length,
                d = {};
            if (1 > c || c > 2) throw new Error("Invalid argument length");
            return 2 === c || "string" == typeof a[0] ? (d[b[0]] = a[0], d[b[1]] = a[1]) : d = a[0], d
        }

        function j(a, c, d) {
            return b.extend(!0, {}, a, i(c, d))
        }

        function k(a, b, c, d) {
            var e = {
                className: "bootbox-" + a,
                buttons: l.apply(null, b)
            };
            return m(j(e, d, c), b)
        }

        function l() {
            for (var a = {}, b = 0, c = arguments.length; c > b; b++) {
                var e = arguments[b],
                    f = e.toLowerCase(),
                    g = e.toUpperCase();
                a[f] = {
                    label: d(g)
                }
            }
            return a
        }

        function m(a, b) {
            var d = {};
            return g(b, function(a, b) {
                d[b] = !0
            }), g(a.buttons, function(a) {
                if (d[a] === c) throw new Error("button key " + a + " is not allowed (options are " + b.join("\n") + ")")
            }), a
        }
        var n = {
                dialog: "<div class='bootbox modal' tabindex='-1' role='dialog'><div class='modal-dialog'><div class='modal-content'><div class='modal-body'><div class='bootbox-body'></div></div></div></div></div>",
                header: "<div class='modal-header'><h4 class='modal-title'></h4></div>",
                footer: "<div class='modal-footer'></div>",
                closeButton: "<button type='button' class='bootbox-close-button close' data-dismiss='modal' aria-hidden='true'>&times;</button>",
                form: "<form class='bootbox-form'></form>",
                inputs: {
                    text: "<input class='bootbox-input bootbox-input-text form-control' autocomplete=off type=text />",
                    textarea: "<textarea class='bootbox-input bootbox-input-textarea form-control'></textarea>",
                    email: "<input class='bootbox-input bootbox-input-email form-control' autocomplete='off' type='email' />",
                    select: "<select class='bootbox-input bootbox-input-select form-control'></select>",
                    checkbox: "<div class='checkbox'><label><input class='bootbox-input bootbox-input-checkbox' type='checkbox' /></label></div>",
                    date: "<input class='bootbox-input bootbox-input-date form-control' autocomplete=off type='date' />",
                    time: "<input class='bootbox-input bootbox-input-time form-control' autocomplete=off type='time' />",
                    number: "<input class='bootbox-input bootbox-input-number form-control' autocomplete=off type='number' />",
                    password: "<input class='bootbox-input bootbox-input-password form-control' autocomplete='off' type='password' />"
                }
            },
            o = {
                locale: "en",
                backdrop: !0,
                animate: !0,
                className: null,
                closeButton: !0,
                show: !0,
                container: "body"
            },
            p = {};
        p.alert = function() {
            var a;
            if (a = k("alert", ["ok"], ["message", "callback"], arguments), a.callback && !b.isFunction(a.callback)) throw new Error("alert requires callback property to be a function when provided");
            return a.buttons.ok.callback = a.onEscape = function() {
                return b.isFunction(a.callback) ? a.callback() : !0
            }, p.dialog(a)
        }, p.confirm = function() {
            var a;
            if (a = k("confirm", ["cancel", "confirm"], ["message", "callback"], arguments), a.buttons.cancel.callback = a.onEscape = function() {
                    return a.callback(!1)
                }, a.buttons.confirm.callback = function() {
                    return a.callback(!0)
                }, !b.isFunction(a.callback)) throw new Error("confirm requires a callback");
            return p.dialog(a)
        }, p.prompt = function() {
            var a, d, e, f, h, i, k;
            f = b(n.form), d = {
                className: "bootbox-prompt",
                buttons: l("cancel", "confirm"),
                value: "",
                inputType: "text"
            }, a = m(j(d, arguments, ["title", "callback"]), ["cancel", "confirm"]), i = a.show === c ? !0 : a.show;
            var o = ["date", "time", "number"],
                q = document.createElement("input");
            if (q.setAttribute("type", a.inputType), o[a.inputType] && (a.inputType = q.type), a.message = f, a.buttons.cancel.callback = a.onEscape = function() {
                    return a.callback(null)
                }, a.buttons.confirm.callback = function() {
                    var c;
                    switch (a.inputType) {
                        case "text":
                        case "textarea":
                        case "email":
                        case "select":
                        case "date":
                        case "time":
                        case "number":
                        case "password":
                            c = h.val();
                            break;
                        case "checkbox":
                            var d = h.find("input:checked");
                            c = [], g(d, function(a, d) {
                                c.push(b(d).val())
                            })
                    }
                    return a.callback(c)
                }, a.show = !1, !a.title) throw new Error("prompt requires a title");
            if (!b.isFunction(a.callback)) throw new Error("prompt requires a callback");
            if (!n.inputs[a.inputType]) throw new Error("invalid prompt type");
            switch (h = b(n.inputs[a.inputType]), a.inputType) {
                case "text":
                case "textarea":
                case "email":
                case "date":
                case "time":
                case "number":
                case "password":
                    h.val(a.value);
                    break;
                case "select":
                    var r = {};
                    if (k = a.inputOptions || [], !k.length) throw new Error("prompt with select requires options");
                    g(k, function(a, d) {
                        var e = h;
                        if (d.value === c || d.text === c) throw new Error("given options in wrong format");
                        d.group && (r[d.group] || (r[d.group] = b("<optgroup/>").attr("label", d.group)), e = r[d.group]), e.append("<option value='" + d.value + "'>" + d.text + "</option>")
                    }), g(r, function(a, b) {
                        h.append(b)
                    }), h.val(a.value);
                    break;
                case "checkbox":
                    var s = b.isArray(a.value) ? a.value : [a.value];
                    if (k = a.inputOptions || [], !k.length) throw new Error("prompt with checkbox requires options");
                    if (!k[0].value || !k[0].text) throw new Error("given options in wrong format");
                    h = b("<div/>"), g(k, function(c, d) {
                        var e = b(n.inputs[a.inputType]);
                        e.find("input").attr("value", d.value), e.find("label").append(d.text), g(s, function(a, b) {
                            b === d.value && e.find("input").prop("checked", !0)
                        }), h.append(e)
                    })
            }
            return a.placeholder && h.attr("placeholder", a.placeholder), a.pattern && h.attr("pattern", a.pattern), f.append(h), f.on("submit", function(a) {
                a.preventDefault(), a.stopPropagation(), e.find(".btn-primary").click()
            }), e = p.dialog(a), e.off("shown.bs.modal"), e.on("shown.bs.modal", function() {
                h.focus()
            }), i === !0 && e.modal("show"), e
        }, p.dialog = function(a) {
            a = h(a);
            var c = b(n.dialog),
                d = c.find(".modal-dialog"),
                f = c.find(".modal-body"),
                i = a.buttons,
                j = "",
                k = {
                    onEscape: a.onEscape
                };
            if (g(i, function(a, b) {
                    j += "<button data-bb-handler='" + a + "' type='button' class='btn " + b.className + "'>" + b.label + "</button>", k[a] = b.callback
                }), f.find(".bootbox-body").html(a.message), a.animate === !0 && c.addClass("fade"), a.className && c.addClass(a.className), "large" === a.size && d.addClass("modal-lg"), "small" === a.size && d.addClass("modal-sm"), a.title && f.before(n.header), a.closeButton) {
                var l = b(n.closeButton);
                a.title ? c.find(".modal-header").prepend(l) : l.css("margin-top", "-10px").prependTo(f)
            }
            return a.title && c.find(".modal-title").html(a.title), j.length && (f.after(n.footer), c.find(".modal-footer").html(j)), c.on("hidden.bs.modal", function(a) {
                a.target === this && c.remove()
            }), c.on("shown.bs.modal", function() {
                c.find(".btn-primary:first").focus()
            }), c.on("escape.close.bb", function(a) {
                k.onEscape && e(a, c, k.onEscape)
            }), c.on("click", ".modal-footer button", function(a) {
                var d = b(this).data("bb-handler");
                e(a, c, k[d])
            }), c.on("click", ".bootbox-close-button", function(a) {
                e(a, c, k.onEscape)
            }), c.on("keyup", function(a) {
                27 === a.which && c.trigger("escape.close.bb")
            }), b(a.container).append(c), c.modal({
                backdrop: a.backdrop,
                keyboard: !1,
                show: !1
            }), a.show && c.modal("show"), c
        }, p.setDefaults = function() {
            var a = {};
            2 === arguments.length ? a[arguments[0]] = arguments[1] : a = arguments[0], b.extend(o, a)
        }, p.hideAll = function() {
            b(".bootbox").modal("hide")
        };
        var q = {
            br: {
                OK: "OK",
                CANCEL: "Cancelar",
                CONFIRM: "Sim"
            },
            da: {
                OK: "OK",
                CANCEL: "Annuller",
                CONFIRM: "Accepter"
            },
            de: {
                OK: "OK",
                CANCEL: "Abbrechen",
                CONFIRM: "Akzeptieren"
            },
            el: {
                OK: "Î•Î½Ï„Î¬Î¾ÎµÎ¹",
                CANCEL: "Î‘ÎºÏÏÏ‰ÏƒÎ·",
                CONFIRM: "Î•Ï€Î¹Î²ÎµÎ²Î±Î¯Ï‰ÏƒÎ·"
            },
            en: {
                OK: "OK",
                CANCEL: "Cancel",
                CONFIRM: "OK"
            },
            es: {
                OK: "OK",
                CANCEL: "Cancelar",
                CONFIRM: "Aceptar"
            },
            fi: {
                OK: "OK",
                CANCEL: "Peruuta",
                CONFIRM: "OK"
            },
            fr: {
                OK: "OK",
                CANCEL: "Annuler",
                CONFIRM: "D'accord"
            },
            he: {
                OK: "××™×©×•×¨",
                CANCEL: "×‘×™×˜×•×œ",
                CONFIRM: "××™×©×•×¨"
            },
            it: {
                OK: "OK",
                CANCEL: "Annulla",
                CONFIRM: "Conferma"
            },
            lt: {
                OK: "Gerai",
                CANCEL: "AtÅ¡aukti",
                CONFIRM: "Patvirtinti"
            },
            lv: {
                OK: "Labi",
                CANCEL: "Atcelt",
                CONFIRM: "ApstiprinÄt"
            },
            nl: {
                OK: "OK",
                CANCEL: "Annuleren",
                CONFIRM: "Accepteren"
            },
            no: {
                OK: "OK",
                CANCEL: "Avbryt",
                CONFIRM: "OK"
            },
            pl: {
                OK: "OK",
                CANCEL: "Anuluj",
                CONFIRM: "PotwierdÅº"
            },
            ru: {
                OK: "OK",
                CANCEL: "ÐžÑ‚Ð¼ÐµÐ½Ð°",
                CONFIRM: "ÐŸÑ€Ð¸Ð¼ÐµÐ½Ð¸Ñ‚ÑŒ"
            },
            sv: {
                OK: "OK",
                CANCEL: "Avbryt",
                CONFIRM: "OK"
            },
            tr: {
                OK: "Tamam",
                CANCEL: "Ä°ptal",
                CONFIRM: "Onayla"
            },
            zh_CN: {
                OK: "OK",
                CANCEL: "å–æ¶ˆ",
                CONFIRM: "ç¡®è®¤"
            },
            zh_TW: {
                OK: "OK",
                CANCEL: "å–æ¶ˆ",
                CONFIRM: "ç¢ºèª"
            }
        };
        return p.init = function(c) {
            return a(c || b)
        }, p
    }), function(a) {
        "function" == typeof define && define.amd ? define(["jquery"], a) : a(jQuery)
    }(function(a) {
        "use strict";
        var b, c, d, e, f, g, h, i, j, k, l, m, n, o, p, q, r, s, t, u, v, w, x, y, z, A, B, C, D, E, F, G, H = {},
            I = 0;
        b = function() {
                return {
                    common: {
                        type: "line",
                        lineColor: "#00f",
                        fillColor: "#cdf",
                        defaultPixelsPerValue: 3,
                        width: "auto",
                        height: "auto",
                        composite: !1,
                        tagValuesAttribute: "values",
                        tagOptionsPrefix: "spark",
                        enableTagOptions: !1,
                        enableHighlight: !0,
                        highlightLighten: 1.4,
                        tooltipSkipNull: !0,
                        tooltipPrefix: "",
                        tooltipSuffix: "",
                        disableHiddenCheck: !1,
                        numberFormatter: !1,
                        numberDigitGroupCount: 3,
                        numberDigitGroupSep: ",",
                        numberDecimalMark: ".",
                        disableTooltips: !1,
                        disableInteraction: !1
                    },
                    line: {
                        spotColor: "#f80",
                        highlightSpotColor: "#5f5",
                        highlightLineColor: "#f22",
                        spotRadius: 1.5,
                        minSpotColor: "#f80",
                        maxSpotColor: "#f80",
                        lineWidth: 1,
                        normalRangeMin: void 0,
                        normalRangeMax: void 0,
                        normalRangeColor: "#ccc",
                        drawNormalOnTop: !1,
                        chartRangeMin: void 0,
                        chartRangeMax: void 0,
                        chartRangeMinX: void 0,
                        chartRangeMaxX: void 0,
                        tooltipFormat: new d('<span style="color: {{color}}">&#9679;</span> {{prefix}}{{y}}{{suffix}}')
                    },
                    bar: {
                        barColor: "#3366cc",
                        negBarColor: "#f44",
                        stackedBarColor: ["#3366cc", "#dc3912", "#ff9900", "#109618", "#66aa00", "#dd4477", "#0099c6", "#990099"],
                        zeroColor: void 0,
                        nullColor: void 0,
                        zeroAxis: !0,
                        barWidth: 4,
                        barSpacing: 1,
                        chartRangeMax: void 0,
                        chartRangeMin: void 0,
                        chartRangeClip: !1,
                        colorMap: void 0,
                        tooltipFormat: new d('<span style="color: {{color}}">&#9679;</span> {{prefix}}{{value}}{{suffix}}')
                    },
                    tristate: {
                        barWidth: 4,
                        barSpacing: 1,
                        posBarColor: "#6f6",
                        negBarColor: "#f44",
                        zeroBarColor: "#999",
                        colorMap: {},
                        tooltipFormat: new d('<span style="color: {{color}}">&#9679;</span> {{value:map}}'),
                        tooltipValueLookups: {
                            map: {
                                "-1": "Loss",
                                0: "Draw",
                                1: "Win"
                            }
                        }
                    },
                    discrete: {
                        lineHeight: "auto",
                        thresholdColor: void 0,
                        thresholdValue: 0,
                        chartRangeMax: void 0,
                        chartRangeMin: void 0,
                        chartRangeClip: !1,
                        tooltipFormat: new d("{{prefix}}{{value}}{{suffix}}")
                    },
                    bullet: {
                        targetColor: "#f33",
                        targetWidth: 3,
                        performanceColor: "#33f",
                        rangeColors: ["#d3dafe", "#a8b6ff", "#7f94ff"],
                        base: void 0,
                        tooltipFormat: new d("{{fieldkey:fields}} - {{value}}"),
                        tooltipValueLookups: {
                            fields: {
                                r: "Range",
                                p: "Performance",
                                t: "Target"
                            }
                        }
                    },
                    pie: {
                        offset: 0,
                        sliceColors: ["#3366cc", "#dc3912", "#ff9900", "#109618", "#66aa00", "#dd4477", "#0099c6", "#990099"],
                        borderWidth: 0,
                        borderColor: "#000",
                        tooltipFormat: new d('<span style="color: {{color}}">&#9679;</span> {{value}} ({{percent.1}}%)')
                    },
                    box: {
                        raw: !1,
                        boxLineColor: "#000",
                        boxFillColor: "#cdf",
                        whiskerColor: "#000",
                        outlierLineColor: "#333",
                        outlierFillColor: "#fff",
                        medianColor: "#f00",
                        showOutliers: !0,
                        outlierIQR: 1.5,
                        spotRadius: 1.5,
                        target: void 0,
                        targetColor: "#4a2",
                        chartRangeMax: void 0,
                        chartRangeMin: void 0,
                        tooltipFormat: new d("{{field:fields}}: {{value}}"),
                        tooltipFormatFieldlistKey: "field",
                        tooltipValueLookups: {
                            fields: {
                                lq: "Lower Quartile",
                                med: "Median",
                                uq: "Upper Quartile",
                                lo: "Left Outlier",
                                ro: "Right Outlier",
                                lw: "Left Whisker",
                                rw: "Right Whisker"
                            }
                        }
                    }
                }
            }, A = '.jqstooltip { position: absolute;left: 0px;top: 0px;visibility: hidden;background: rgb(0, 0, 0) transparent;background-color: rgba(0,0,0,0.6);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";color: white;font: 10px arial, san serif;text-align: left;white-space: nowrap;padding: 5px;border: 1px solid white;z-index: 10000;}.jqsfield { color: white;font: 10px arial, san serif;text-align: left;}', c = function() {
                var b, c;
                return b = function() {
                    this.init.apply(this, arguments)
                }, arguments.length > 1 ? (arguments[0] ? (b.prototype = a.extend(new arguments[0], arguments[arguments.length - 1]), b._super = arguments[0].prototype) : b.prototype = arguments[arguments.length - 1], arguments.length > 2 && (c = Array.prototype.slice.call(arguments, 1, -1), c.unshift(b.prototype), a.extend.apply(a, c))) : b.prototype = arguments[0], b.prototype.cls = b, b
            }, a.SPFormatClass = d = c({
                fre: /\{\{([\w.]+?)(:(.+?))?\}\}/g,
                precre: /(\w+)\.(\d+)/,
                init: function(a, b) {
                    this.format = a, this.fclass = b
                },
                render: function(a, b, c) {
                    var d, e, f, g, h, i = this,
                        k = a;
                    return this.format.replace(this.fre, function() {
                        var a;
                        return e = arguments[1], f = arguments[3], d = i.precre.exec(e), d ? (h = d[2], e = d[1]) : h = !1, g = k[e], void 0 === g ? "" : f && b && b[f] ? (a = b[f], a.get ? b[f].get(g) || g : b[f][g] || g) : (j(g) && (g = c.get("numberFormatter") ? c.get("numberFormatter")(g) : o(g, h, c.get("numberDigitGroupCount"), c.get("numberDigitGroupSep"), c.get("numberDecimalMark"))), g)
                    })
                }
            }), a.spformat = function(a, b) {
                return new d(a, b)
            }, e = function(a, b, c) {
                return b > a ? b : a > c ? c : a
            }, f = function(a, b) {
                var c;
                return 2 === b ? (c = Math.floor(a.length / 2), a.length % 2 ? a[c] : (a[c - 1] + a[c]) / 2) : a.length % 2 ? (c = (a.length * b + b) / 4, c % 1 ? (a[Math.floor(c)] + a[Math.floor(c) - 1]) / 2 : a[c - 1]) : (c = (a.length * b + 2) / 4, c % 1 ? (a[Math.floor(c)] + a[Math.floor(c) - 1]) / 2 : a[c - 1])
            }, g = function(a) {
                var b;
                switch (a) {
                    case "undefined":
                        a = void 0;
                        break;
                    case "null":
                        a = null;
                        break;
                    case "true":
                        a = !0;
                        break;
                    case "false":
                        a = !1;
                        break;
                    default:
                        b = parseFloat(a), a == b && (a = b)
                }
                return a
            }, h = function(a) {
                var b, c = [];
                for (b = a.length; b--;) c[b] = g(a[b]);
                return c
            }, i = function(a, b) {
                var c, d, e = [];
                for (c = 0, d = a.length; d > c; c++) a[c] !== b && e.push(a[c]);
                return e
            }, j = function(a) {
                return !isNaN(parseFloat(a)) && isFinite(a)
            }, o = function(b, c, d, e, f) {
                var g, h;
                for (b = (c === !1 ? parseFloat(b).toString() : b.toFixed(c)).split(""), g = (g = a.inArray(".", b)) < 0 ? b.length : g, g < b.length && (b[g] = f), h = g - d; h > 0; h -= d) b.splice(h, 0, e);
                return b.join("")
            }, k = function(a, b, c) {
                var d;
                for (d = b.length; d--;)
                    if ((!c || null !== b[d]) && b[d] !== a) return !1;
                return !0
            }, l = function(a) {
                var b, c = 0;
                for (b = a.length; b--;) c += "number" == typeof a[b] ? a[b] : 0;
                return c
            }, n = function(b) {
                return a.isArray(b) ? b : [b]
            }, m = function(a) {
                var b;
                document.createStyleSheet ? document.createStyleSheet().cssText = a : (b = document.createElement("style"), b.type = "text/css", document.getElementsByTagName("head")[0].appendChild(b), b["string" == typeof document.body.style.WebkitAppearance ? "innerText" : "innerHTML"] = a)
            }, a.fn.simpledraw = function(b, c, d, e) {
                var f, g;
                if (d && (f = this.data("_jqs_vcanvas"))) return f;
                if (void 0 === b && (b = a(this).innerWidth()), void 0 === c && (c = a(this).innerHeight()), a.fn.sparkline.hasCanvas) f = new E(b, c, this, e);
                else {
                    if (!a.fn.sparkline.hasVML) return !1;
                    f = new F(b, c, this)
                }
                return g = a(this).data("_jqs_mhandler"), g && g.registerCanvas(f), f
            }, a.fn.cleardraw = function() {
                var a = this.data("_jqs_vcanvas");
                a && a.reset()
            }, a.RangeMapClass = p = c({
                init: function(a) {
                    var b, c, d = [];
                    for (b in a) a.hasOwnProperty(b) && "string" == typeof b && b.indexOf(":") > -1 && (c = b.split(":"), c[0] = 0 === c[0].length ? -1 / 0 : parseFloat(c[0]), c[1] = 0 === c[1].length ? 1 / 0 : parseFloat(c[1]), c[2] = a[b], d.push(c));
                    this.map = a, this.rangelist = d || !1
                },
                get: function(a) {
                    var b, c, d, e = this.rangelist;
                    if (void 0 !== (d = this.map[a])) return d;
                    if (e)
                        for (b = e.length; b--;)
                            if (c = e[b], c[0] <= a && c[1] >= a) return c[2];
                    return void 0
                }
            }), a.range_map = function(a) {
                return new p(a)
            }, q = c({
                init: function(b, c) {
                    var d = a(b);
                    this.$el = d, this.options = c, this.currentPageX = 0, this.currentPageY = 0, this.el = b, this.splist = [], this.tooltip = null, this.over = !1, this.displayTooltips = !c.get("disableTooltips"), this.highlightEnabled = !c.get("disableHighlight")
                },
                registerSparkline: function(a) {
                    this.splist.push(a), this.over && this.updateDisplay()
                },
                registerCanvas: function(b) {
                    var c = a(b.canvas);
                    this.canvas = b, this.$canvas = c, c.mouseenter(a.proxy(this.mouseenter, this)), c.mouseleave(a.proxy(this.mouseleave, this)), c.click(a.proxy(this.mouseclick, this))
                },
                reset: function(a) {
                    this.splist = [], this.tooltip && a && (this.tooltip.remove(), this.tooltip = void 0)
                },
                mouseclick: function(b) {
                    var c = a.Event("sparklineClick");
                    c.originalEvent = b, c.sparklines = this.splist, this.$el.trigger(c)
                },
                mouseenter: function(b) {
                    a(document.body).unbind("mousemove.jqs"), a(document.body).bind("mousemove.jqs", a.proxy(this.mousemove, this)), this.over = !0, this.currentPageX = b.pageX, this.currentPageY = b.pageY, this.currentEl = b.target, !this.tooltip && this.displayTooltips && (this.tooltip = new r(this.options), this.tooltip.updatePosition(b.pageX, b.pageY)), this.updateDisplay()
                },
                mouseleave: function() {
                    a(document.body).unbind("mousemove.jqs");
                    var b, c, d = this.splist,
                        e = d.length,
                        f = !1;
                    for (this.over = !1, this.currentEl = null, this.tooltip && (this.tooltip.remove(), this.tooltip = null), c = 0; e > c; c++) b = d[c], b.clearRegionHighlight() && (f = !0);
                    f && this.canvas.render()
                },
                mousemove: function(a) {
                    this.currentPageX = a.pageX, this.currentPageY = a.pageY, this.currentEl = a.target, this.tooltip && this.tooltip.updatePosition(a.pageX, a.pageY), this.updateDisplay()
                },
                updateDisplay: function() {
                    var b, c, d, e, f, g = this.splist,
                        h = g.length,
                        i = !1,
                        j = this.$canvas.offset(),
                        k = this.currentPageX - j.left,
                        l = this.currentPageY - j.top;
                    if (this.over) {
                        for (d = 0; h > d; d++) c = g[d], e = c.setRegionHighlight(this.currentEl, k, l), e && (i = !0);
                        if (i) {
                            if (f = a.Event("sparklineRegionChange"), f.sparklines = this.splist, this.$el.trigger(f), this.tooltip) {
                                for (b = "", d = 0; h > d; d++) c = g[d], b += c.getCurrentRegionTooltip();
                                this.tooltip.setContent(b)
                            }
                            this.disableHighlight || this.canvas.render()
                        }
                        null === e && this.mouseleave()
                    }
                }
            }), r = c({
                sizeStyle: "position: static !important;display: block !important;visibility: hidden !important;float: left !important;",
                init: function(b) {
                    var c, d = b.get("tooltipClassname", "jqstooltip"),
                        e = this.sizeStyle;
                    this.container = b.get("tooltipContainer") || document.body, this.tooltipOffsetX = b.get("tooltipOffsetX", 10), this.tooltipOffsetY = b.get("tooltipOffsetY", 12), a("#jqssizetip").remove(), a("#jqstooltip").remove(), this.sizetip = a("<div/>", {
                        id: "jqssizetip",
                        style: e,
                        "class": d
                    }), this.tooltip = a("<div/>", {
                        id: "jqstooltip",
                        "class": d
                    }).appendTo(this.container), c = this.tooltip.offset(), this.offsetLeft = c.left, this.offsetTop = c.top, this.hidden = !0, a(window).unbind("resize.jqs scroll.jqs"), a(window).bind("resize.jqs scroll.jqs", a.proxy(this.updateWindowDims, this)), this.updateWindowDims()
                },
                updateWindowDims: function() {
                    this.scrollTop = a(window).scrollTop(), this.scrollLeft = a(window).scrollLeft(), this.scrollRight = this.scrollLeft + a(window).width(), this.updatePosition()
                },
                getSize: function(a) {
                    this.sizetip.html(a).appendTo(this.container), this.width = this.sizetip.width() + 1, this.height = this.sizetip.height(), this.sizetip.remove()
                },
                setContent: function(a) {
                    return a ? (this.getSize(a), this.tooltip.html(a).css({
                        width: this.width,
                        height: this.height,
                        visibility: "visible"
                    }), void(this.hidden && (this.hidden = !1, this.updatePosition()))) : (this.tooltip.css("visibility", "hidden"), void(this.hidden = !0))
                },
                updatePosition: function(a, b) {
                    if (void 0 === a) {
                        if (void 0 === this.mousex) return;
                        a = this.mousex - this.offsetLeft, b = this.mousey - this.offsetTop
                    } else this.mousex = a -= this.offsetLeft, this.mousey = b -= this.offsetTop;
                    this.height && this.width && !this.hidden && (b -= this.height + this.tooltipOffsetY, a += this.tooltipOffsetX, b < this.scrollTop && (b = this.scrollTop), a < this.scrollLeft ? a = this.scrollLeft : a + this.width > this.scrollRight && (a = this.scrollRight - this.width), this.tooltip.css({
                        left: a,
                        top: b
                    }))
                },
                remove: function() {
                    this.tooltip.remove(), this.sizetip.remove(), this.sizetip = this.tooltip = void 0, a(window).unbind("resize.jqs scroll.jqs")
                }
            }), B = function() {
                m(A)
            }, a(B), G = [], a.fn.sparkline = function(b, c) {
                return this.each(function() {
                    var d, e, f = new a.fn.sparkline.options(this, c),
                        g = a(this);
                    if (d = function() {
                            var c, d, e, h, i, j, k;
                            return "html" === b || void 0 === b ? (k = this.getAttribute(f.get("tagValuesAttribute")), (void 0 === k || null === k) && (k = g.html()), c = k.replace(/(^\s*<!--)|(-->\s*$)|\s+/g, "").split(",")) : c = b, d = "auto" === f.get("width") ? c.length * f.get("defaultPixelsPerValue") : f.get("width"), "auto" === f.get("height") ? f.get("composite") && a.data(this, "_jqs_vcanvas") || (h = document.createElement("span"), h.innerHTML = "a", g.html(h), e = a(h).innerHeight() || a(h).height(), a(h).remove(), h = null) : e = f.get("height"), f.get("disableInteraction") ? i = !1 : (i = a.data(this, "_jqs_mhandler"), i ? f.get("composite") || i.reset() : (i = new q(this, f), a.data(this, "_jqs_mhandler", i))), f.get("composite") && !a.data(this, "_jqs_vcanvas") ? void(a.data(this, "_jqs_errnotify") || (alert("Attempted to attach a composite sparkline to an element with no existing sparkline"), a.data(this, "_jqs_errnotify", !0))) : (j = new(a.fn.sparkline[f.get("type")])(this, c, f, d, e), j.render(), void(i && i.registerSparkline(j)))
                        }, a(this).html() && !f.get("disableHiddenCheck") && a(this).is(":hidden") || a.fn.jquery < "1.3.0" && a(this).parents().is(":hidden") || !a(this).parents("body").length) {
                        if (!f.get("composite") && a.data(this, "_jqs_pending"))
                            for (e = G.length; e; e--) G[e - 1][0] == this && G.splice(e - 1, 1);
                        G.push([this, d]), a.data(this, "_jqs_pending", !0)
                    } else d.call(this)
                })
            }, a.fn.sparkline.defaults = b(), a.sparkline_display_visible = function() {
                var b, c, d, e = [];
                for (c = 0, d = G.length; d > c; c++) b = G[c][0], a(b).is(":visible") && !a(b).parents().is(":hidden") ? (G[c][1].call(b), a.data(G[c][0], "_jqs_pending", !1), e.push(c)) : a(b).closest("html").length || a.data(b, "_jqs_pending") || (a.data(G[c][0], "_jqs_pending", !1), e.push(c));
                for (c = e.length; c; c--) G.splice(e[c - 1], 1)
            }, a.fn.sparkline.options = c({
                init: function(b, c) {
                    var d, e, f, g;
                    this.userOptions = c = c || {}, this.tag = b, this.tagValCache = {}, e = a.fn.sparkline.defaults, f = e.common, this.tagOptionsPrefix = c.enableTagOptions && (c.tagOptionsPrefix || f.tagOptionsPrefix), g = this.getTagSetting("type"), d = g === H ? e[c.type || f.type] : e[g], this.mergedOptions = a.extend({}, f, d, c)
                },
                getTagSetting: function(a) {
                    var b, c, d, e, f = this.tagOptionsPrefix;
                    if (f === !1 || void 0 === f) return H;
                    if (this.tagValCache.hasOwnProperty(a)) b = this.tagValCache.key;
                    else {
                        if (b = this.tag.getAttribute(f + a), void 0 === b || null === b) b = H;
                        else if ("[" === b.substr(0, 1))
                            for (b = b.substr(1, b.length - 2).split(","), c = b.length; c--;) b[c] = g(b[c].replace(/(^\s*)|(\s*$)/g, ""));
                        else if ("{" === b.substr(0, 1))
                            for (d = b.substr(1, b.length - 2).split(","), b = {}, c = d.length; c--;) e = d[c].split(":", 2), b[e[0].replace(/(^\s*)|(\s*$)/g, "")] = g(e[1].replace(/(^\s*)|(\s*$)/g, ""));
                        else b = g(b);
                        this.tagValCache.key = b
                    }
                    return b
                },
                get: function(a, b) {
                    var c, d = this.getTagSetting(a);
                    return d !== H ? d : void 0 === (c = this.mergedOptions[a]) ? b : c
                }
            }), a.fn.sparkline._base = c({
                disabled: !1,
                init: function(b, c, d, e, f) {
                    this.el = b, this.$el = a(b), this.values = c, this.options = d, this.width = e, this.height = f, this.currentRegion = void 0
                },
                initTarget: function() {
                    var a = !this.options.get("disableInteraction");
                    (this.target = this.$el.simpledraw(this.width, this.height, this.options.get("composite"), a)) ? (this.canvasWidth = this.target.pixelWidth, this.canvasHeight = this.target.pixelHeight) : this.disabled = !0
                },
                render: function() {
                    return this.disabled ? (this.el.innerHTML = "", !1) : !0
                },
                getRegion: function() {},
                setRegionHighlight: function(a, b, c) {
                    var d, e = this.currentRegion,
                        f = !this.options.get("disableHighlight");
                    return b > this.canvasWidth || c > this.canvasHeight || 0 > b || 0 > c ? null : (d = this.getRegion(a, b, c), e !== d ? (void 0 !== e && f && this.removeHighlight(), this.currentRegion = d, void 0 !== d && f && this.renderHighlight(), !0) : !1)
                },
                clearRegionHighlight: function() {
                    return void 0 !== this.currentRegion ? (this.removeHighlight(), this.currentRegion = void 0, !0) : !1
                },
                renderHighlight: function() {
                    this.changeHighlight(!0)
                },
                removeHighlight: function() {
                    this.changeHighlight(!1)
                },
                changeHighlight: function() {},
                getCurrentRegionTooltip: function() {
                    var b, c, e, f, g, h, i, j, k, l, m, n, o, p, q = this.options,
                        r = "",
                        s = [];
                    if (void 0 === this.currentRegion) return "";
                    if (b = this.getCurrentRegionFields(), m = q.get("tooltipFormatter")) return m(this, q, b);
                    if (q.get("tooltipChartTitle") && (r += '<div class="jqs jqstitle">' + q.get("tooltipChartTitle") + "</div>\n"), c = this.options.get("tooltipFormat"), !c) return "";
                    if (a.isArray(c) || (c = [c]), a.isArray(b) || (b = [b]), i = this.options.get("tooltipFormatFieldlist"), j = this.options.get("tooltipFormatFieldlistKey"), i && j) {
                        for (k = [], h = b.length; h--;) l = b[h][j], -1 != (p = a.inArray(l, i)) && (k[p] = b[h]);
                        b = k
                    }
                    for (e = c.length, o = b.length, h = 0; e > h; h++)
                        for (n = c[h], "string" == typeof n && (n = new d(n)), f = n.fclass || "jqsfield", p = 0; o > p; p++) b[p].isNull && q.get("tooltipSkipNull") || (a.extend(b[p], {
                            prefix: q.get("tooltipPrefix"),
                            suffix: q.get("tooltipSuffix")
                        }), g = n.render(b[p], q.get("tooltipValueLookups"), q), s.push('<div class="' + f + '">' + g + "</div>"));
                    return s.length ? r + s.join("\n") : ""
                },
                getCurrentRegionFields: function() {},
                calcHighlightColor: function(a, b) {
                    var c, d, f, g, h = b.get("highlightColor"),
                        i = b.get("highlightLighten");
                    if (h) return h;
                    if (i && (c = /^#([0-9a-f])([0-9a-f])([0-9a-f])$/i.exec(a) || /^#([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})$/i.exec(a))) {
                        for (f = [], d = 4 === a.length ? 16 : 1, g = 0; 3 > g; g++) f[g] = e(Math.round(parseInt(c[g + 1], 16) * d * i), 0, 255);
                        return "rgb(" + f.join(",") + ")"
                    }
                    return a
                }
            }), s = {
                changeHighlight: function(b) {
                    var c, d = this.currentRegion,
                        e = this.target,
                        f = this.regionShapes[d];
                    f && (c = this.renderRegion(d, b), a.isArray(c) || a.isArray(f) ? (e.replaceWithShapes(f, c), this.regionShapes[d] = a.map(c, function(a) {
                        return a.id
                    })) : (e.replaceWithShape(f, c), this.regionShapes[d] = c.id))
                },
                render: function() {
                    var b, c, d, e, f = this.values,
                        g = this.target,
                        h = this.regionShapes;
                    if (this.cls._super.render.call(this)) {
                        for (d = f.length; d--;)
                            if (b = this.renderRegion(d))
                                if (a.isArray(b)) {
                                    for (c = [], e = b.length; e--;) b[e].append(), c.push(b[e].id);
                                    h[d] = c
                                } else b.append(), h[d] = b.id;
                        else h[d] = null;
                        g.render()
                    }
                }
            }, a.fn.sparkline.line = t = c(a.fn.sparkline._base, {
                type: "line",
                init: function(a, b, c, d, e) {
                    t._super.init.call(this, a, b, c, d, e), this.vertices = [], this.regionMap = [], this.xvalues = [], this.yvalues = [], this.yminmax = [], this.hightlightSpotId = null, this.lastShapeId = null, this.initTarget()
                },
                getRegion: function(a, b) {
                    var c, d = this.regionMap;
                    for (c = d.length; c--;)
                        if (null !== d[c] && b >= d[c][0] && b <= d[c][1]) return d[c][2];
                    return void 0
                },
                getCurrentRegionFields: function() {
                    var a = this.currentRegion;
                    return {
                        isNull: null === this.yvalues[a],
                        x: this.xvalues[a],
                        y: this.yvalues[a],
                        color: this.options.get("lineColor"),
                        fillColor: this.options.get("fillColor"),
                        offset: a
                    }
                },
                renderHighlight: function() {
                    var a, b, c = this.currentRegion,
                        d = this.target,
                        e = this.vertices[c],
                        f = this.options,
                        g = f.get("spotRadius"),
                        h = f.get("highlightSpotColor"),
                        i = f.get("highlightLineColor");
                    e && (g && h && (a = d.drawCircle(e[0], e[1], g, void 0, h), this.highlightSpotId = a.id, d.insertAfterShape(this.lastShapeId, a)), i && (b = d.drawLine(e[0], this.canvasTop, e[0], this.canvasTop + this.canvasHeight, i), this.highlightLineId = b.id, d.insertAfterShape(this.lastShapeId, b)))
                },
                removeHighlight: function() {
                    var a = this.target;
                    this.highlightSpotId && (a.removeShapeId(this.highlightSpotId), this.highlightSpotId = null), this.highlightLineId && (a.removeShapeId(this.highlightLineId), this.highlightLineId = null)
                },
                scanValues: function() {
                    var a, b, c, d, e, f = this.values,
                        g = f.length,
                        h = this.xvalues,
                        i = this.yvalues,
                        j = this.yminmax;
                    for (a = 0; g > a; a++) b = f[a], c = "string" == typeof f[a], d = "object" == typeof f[a] && f[a] instanceof Array, e = c && f[a].split(":"), c && 2 === e.length ? (h.push(Number(e[0])), i.push(Number(e[1])), j.push(Number(e[1]))) : d ? (h.push(b[0]), i.push(b[1]), j.push(b[1])) : (h.push(a), null === f[a] || "null" === f[a] ? i.push(null) : (i.push(Number(b)), j.push(Number(b))));
                    this.options.get("xvalues") && (h = this.options.get("xvalues")), this.maxy = this.maxyorg = Math.max.apply(Math, j), this.miny = this.minyorg = Math.min.apply(Math, j), this.maxx = Math.max.apply(Math, h), this.minx = Math.min.apply(Math, h), this.xvalues = h, this.yvalues = i, this.yminmax = j
                },
                processRangeOptions: function() {
                    var a = this.options,
                        b = a.get("normalRangeMin"),
                        c = a.get("normalRangeMax");
                    void 0 !== b && (b < this.miny && (this.miny = b), c > this.maxy && (this.maxy = c)), void 0 !== a.get("chartRangeMin") && (a.get("chartRangeClip") || a.get("chartRangeMin") < this.miny) && (this.miny = a.get("chartRangeMin")), void 0 !== a.get("chartRangeMax") && (a.get("chartRangeClip") || a.get("chartRangeMax") > this.maxy) && (this.maxy = a.get("chartRangeMax")), void 0 !== a.get("chartRangeMinX") && (a.get("chartRangeClipX") || a.get("chartRangeMinX") < this.minx) && (this.minx = a.get("chartRangeMinX")), void 0 !== a.get("chartRangeMaxX") && (a.get("chartRangeClipX") || a.get("chartRangeMaxX") > this.maxx) && (this.maxx = a.get("chartRangeMaxX"))
                },
                drawNormalRange: function(a, b, c, d, e) {
                    var f = this.options.get("normalRangeMin"),
                        g = this.options.get("normalRangeMax"),
                        h = b + Math.round(c - c * ((g - this.miny) / e)),
                        i = Math.round(c * (g - f) / e);
                    this.target.drawRect(a, h, d, i, void 0, this.options.get("normalRangeColor")).append()
                },
                render: function() {
                    var b, c, d, e, f, g, h, i, j, k, l, m, n, o, q, r, s, u, v, w, x, y, z, A, B, C = this.options,
                        D = this.target,
                        E = this.canvasWidth,
                        F = this.canvasHeight,
                        G = this.vertices,
                        H = C.get("spotRadius"),
                        I = this.regionMap;
                    if (t._super.render.call(this) && (this.scanValues(), this.processRangeOptions(), z = this.xvalues, A = this.yvalues, this.yminmax.length && !(this.yvalues.length < 2))) {
                        for (e = f = 0, b = this.maxx - this.minx === 0 ? 1 : this.maxx - this.minx, c = this.maxy - this.miny === 0 ? 1 : this.maxy - this.miny, d = this.yvalues.length - 1, H && (4 * H > E || 4 * H > F) && (H = 0), H && (x = C.get("highlightSpotColor") && !C.get("disableInteraction"), (x || C.get("minSpotColor") || C.get("spotColor") && A[d] === this.miny) && (F -= Math.ceil(H)), (x || C.get("maxSpotColor") || C.get("spotColor") && A[d] === this.maxy) && (F -= Math.ceil(H), e += Math.ceil(H)), (x || (C.get("minSpotColor") || C.get("maxSpotColor")) && (A[0] === this.miny || A[0] === this.maxy)) && (f += Math.ceil(H), E -= Math.ceil(H)), (x || C.get("spotColor") || C.get("minSpotColor") || C.get("maxSpotColor") && (A[d] === this.miny || A[d] === this.maxy)) && (E -= Math.ceil(H))), F--, void 0 === C.get("normalRangeMin") || C.get("drawNormalOnTop") || this.drawNormalRange(f, e, F, E, c), h = [], i = [h], o = q = null, r = A.length, B = 0; r > B; B++) j = z[B], l = z[B + 1], k = A[B], m = f + Math.round((j - this.minx) * (E / b)), n = r - 1 > B ? f + Math.round((l - this.minx) * (E / b)) : E, q = m + (n - m) / 2, I[B] = [o || 0, q, B], o = q, null === k ? B && (null !== A[B - 1] && (h = [], i.push(h)), G.push(null)) : (k < this.miny && (k = this.miny), k > this.maxy && (k = this.maxy), h.length || h.push([m, e + F]), g = [m, e + Math.round(F - F * ((k - this.miny) / c))], h.push(g), G.push(g));
                        for (s = [], u = [], v = i.length, B = 0; v > B; B++) h = i[B], h.length && (C.get("fillColor") && (h.push([h[h.length - 1][0], e + F]), u.push(h.slice(0)), h.pop()), h.length > 2 && (h[0] = [h[0][0], h[1][1]]), s.push(h));
                        for (v = u.length, B = 0; v > B; B++) D.drawShape(u[B], C.get("fillColor"), C.get("fillColor")).append();
                        for (void 0 !== C.get("normalRangeMin") && C.get("drawNormalOnTop") && this.drawNormalRange(f, e, F, E, c), v = s.length, B = 0; v > B; B++) D.drawShape(s[B], C.get("lineColor"), void 0, C.get("lineWidth")).append();
                        if (H && C.get("valueSpots"))
                            for (w = C.get("valueSpots"), void 0 === w.get && (w = new p(w)), B = 0; r > B; B++) y = w.get(A[B]), y && D.drawCircle(f + Math.round((z[B] - this.minx) * (E / b)), e + Math.round(F - F * ((A[B] - this.miny) / c)), H, void 0, y).append();
                        H && C.get("spotColor") && null !== A[d] && D.drawCircle(f + Math.round((z[z.length - 1] - this.minx) * (E / b)), e + Math.round(F - F * ((A[d] - this.miny) / c)), H, void 0, C.get("spotColor")).append(), this.maxy !== this.minyorg && (H && C.get("minSpotColor") && (j = z[a.inArray(this.minyorg, A)], D.drawCircle(f + Math.round((j - this.minx) * (E / b)), e + Math.round(F - F * ((this.minyorg - this.miny) / c)), H, void 0, C.get("minSpotColor")).append()), H && C.get("maxSpotColor") && (j = z[a.inArray(this.maxyorg, A)], D.drawCircle(f + Math.round((j - this.minx) * (E / b)), e + Math.round(F - F * ((this.maxyorg - this.miny) / c)), H, void 0, C.get("maxSpotColor")).append())), this.lastShapeId = D.getLastShapeId(), this.canvasTop = e, D.render()
                    }
                }
            }), a.fn.sparkline.bar = u = c(a.fn.sparkline._base, s, {
                type: "bar",
                init: function(b, c, d, f, j) {
                    var k, l, m, n, o, q, r, s, t, v, w, x, y, z, A, B, C, D, E, F, G, H, I = parseInt(d.get("barWidth"), 10),
                        J = parseInt(d.get("barSpacing"), 10),
                        K = d.get("chartRangeMin"),
                        L = d.get("chartRangeMax"),
                        M = d.get("chartRangeClip"),
                        N = 1 / 0,
                        O = -1 / 0;
                    for (u._super.init.call(this, b, c, d, f, j), q = 0, r = c.length; r > q; q++) F = c[q], k = "string" == typeof F && F.indexOf(":") > -1, (k || a.isArray(F)) && (A = !0, k && (F = c[q] = h(F.split(":"))), F = i(F, null), l = Math.min.apply(Math, F), m = Math.max.apply(Math, F), N > l && (N = l), m > O && (O = m));
                    this.stacked = A, this.regionShapes = {}, this.barWidth = I, this.barSpacing = J, this.totalBarWidth = I + J, this.width = f = c.length * I + (c.length - 1) * J, this.initTarget(), M && (y = void 0 === K ? -1 / 0 : K, z = void 0 === L ? 1 / 0 : L), o = [], n = A ? [] : o;
                    var P = [],
                        Q = [];
                    for (q = 0, r = c.length; r > q; q++)
                        if (A)
                            for (B = c[q], c[q] = E = [], P[q] = 0, n[q] = Q[q] = 0, C = 0, D = B.length; D > C; C++) F = E[C] = M ? e(B[C], y, z) : B[C], null !== F && (F > 0 && (P[q] += F), 0 > N && O > 0 ? 0 > F ? Q[q] += Math.abs(F) : n[q] += F : n[q] += Math.abs(F - (0 > F ? O : N)), o.push(F));
                        else F = M ? e(c[q], y, z) : c[q], F = c[q] = g(F), null !== F && o.push(F);
                    this.max = x = Math.max.apply(Math, o), this.min = w = Math.min.apply(Math, o), this.stackMax = O = A ? Math.max.apply(Math, P) : x, this.stackMin = N = A ? Math.min.apply(Math, o) : w, void 0 !== d.get("chartRangeMin") && (d.get("chartRangeClip") || d.get("chartRangeMin") < w) && (w = d.get("chartRangeMin")), void 0 !== d.get("chartRangeMax") && (d.get("chartRangeClip") || d.get("chartRangeMax") > x) && (x = d.get("chartRangeMax")), this.zeroAxis = t = d.get("zeroAxis", !0), v = 0 >= w && x >= 0 && t ? 0 : 0 == t ? w : w > 0 ? w : x, this.xaxisOffset = v, s = A ? Math.max.apply(Math, n) + Math.max.apply(Math, Q) : x - w, this.canvasHeightEf = t && 0 > w ? this.canvasHeight - 2 : this.canvasHeight - 1, v > w ? (H = A && x >= 0 ? O : x, G = (H - v) / s * this.canvasHeight, G !== Math.ceil(G) && (this.canvasHeightEf -= 2, G = Math.ceil(G))) : G = this.canvasHeight, this.yoffset = G, a.isArray(d.get("colorMap")) ? (this.colorMapByIndex = d.get("colorMap"), this.colorMapByValue = null) : (this.colorMapByIndex = null, this.colorMapByValue = d.get("colorMap"), this.colorMapByValue && void 0 === this.colorMapByValue.get && (this.colorMapByValue = new p(this.colorMapByValue))), this.range = s
                },
                getRegion: function(a, b) {
                    var c = Math.floor(b / this.totalBarWidth);
                    return 0 > c || c >= this.values.length ? void 0 : c
                },
                getCurrentRegionFields: function() {
                    var a, b, c = this.currentRegion,
                        d = n(this.values[c]),
                        e = [];
                    for (b = d.length; b--;) a = d[b], e.push({
                        isNull: null === a,
                        value: a,
                        color: this.calcColor(b, a, c),
                        offset: c
                    });
                    return e
                },
                calcColor: function(b, c, d) {
                    var e, f, g = this.colorMapByIndex,
                        h = this.colorMapByValue,
                        i = this.options;
                    return e = i.get(this.stacked ? "stackedBarColor" : 0 > c ? "negBarColor" : "barColor"), 0 === c && void 0 !== i.get("zeroColor") && (e = i.get("zeroColor")), h && (f = h.get(c)) ? e = f : g && g.length > d && (e = g[d]), a.isArray(e) ? e[b % e.length] : e
                },
                renderRegion: function(b, c) {
                    var d, e, f, g, h, i, j, l, m, n, o = this.values[b],
                        p = this.options,
                        q = this.xaxisOffset,
                        r = [],
                        s = this.range,
                        t = this.stacked,
                        u = this.target,
                        v = b * this.totalBarWidth,
                        w = this.canvasHeightEf,
                        x = this.yoffset;
                    if (o = a.isArray(o) ? o : [o], j = o.length, l = o[0], g = k(null, o), n = k(q, o, !0), g) return p.get("nullColor") ? (f = c ? p.get("nullColor") : this.calcHighlightColor(p.get("nullColor"), p), d = x > 0 ? x - 1 : x, u.drawRect(v, d, this.barWidth - 1, 0, f, f)) : void 0;
                    for (h = x, i = 0; j > i; i++) {
                        if (l = o[i], t && l === q) {
                            if (!n || m) continue;
                            m = !0
                        }
                        e = s > 0 ? Math.floor(w * (Math.abs(l - q) / s)) + 1 : 1, q > l || l === q && 0 === x ? (d = h, h += e) : (d = x - e, x -= e), f = this.calcColor(i, l, b), c && (f = this.calcHighlightColor(f, p)), r.push(u.drawRect(v, d, this.barWidth - 1, e - 1, f, f))
                    }
                    return 1 === r.length ? r[0] : r
                }
            }), a.fn.sparkline.tristate = v = c(a.fn.sparkline._base, s, {
                type: "tristate",
                init: function(b, c, d, e, f) {
                    var g = parseInt(d.get("barWidth"), 10),
                        h = parseInt(d.get("barSpacing"), 10);
                    v._super.init.call(this, b, c, d, e, f), this.regionShapes = {}, this.barWidth = g, this.barSpacing = h, this.totalBarWidth = g + h, this.values = a.map(c, Number), this.width = e = c.length * g + (c.length - 1) * h, a.isArray(d.get("colorMap")) ? (this.colorMapByIndex = d.get("colorMap"), this.colorMapByValue = null) : (this.colorMapByIndex = null, this.colorMapByValue = d.get("colorMap"), this.colorMapByValue && void 0 === this.colorMapByValue.get && (this.colorMapByValue = new p(this.colorMapByValue))), this.initTarget()
                },
                getRegion: function(a, b) {
                    return Math.floor(b / this.totalBarWidth)
                },
                getCurrentRegionFields: function() {
                    var a = this.currentRegion;
                    return {
                        isNull: void 0 === this.values[a],
                        value: this.values[a],
                        color: this.calcColor(this.values[a], a),
                        offset: a
                    }
                },
                calcColor: function(a, b) {
                    var c, d, e = this.values,
                        f = this.options,
                        g = this.colorMapByIndex,
                        h = this.colorMapByValue;
                    return c = h && (d = h.get(a)) ? d : g && g.length > b ? g[b] : f.get(e[b] < 0 ? "negBarColor" : e[b] > 0 ? "posBarColor" : "zeroBarColor")
                },
                renderRegion: function(a, b) {
                    var c, d, e, f, g, h, i = this.values,
                        j = this.options,
                        k = this.target;
                    return c = k.pixelHeight, e = Math.round(c / 2), f = a * this.totalBarWidth, i[a] < 0 ? (g = e, d = e - 1) : i[a] > 0 ? (g = 0, d = e - 1) : (g = e - 1, d = 2), h = this.calcColor(i[a], a), null !== h ? (b && (h = this.calcHighlightColor(h, j)), k.drawRect(f, g, this.barWidth - 1, d - 1, h, h)) : void 0
                }
            }), a.fn.sparkline.discrete = w = c(a.fn.sparkline._base, s, {
                type: "discrete",
                init: function(b, c, d, e, f) {
                    w._super.init.call(this, b, c, d, e, f), this.regionShapes = {}, this.values = c = a.map(c, Number), this.min = Math.min.apply(Math, c), this.max = Math.max.apply(Math, c), this.range = this.max - this.min, this.width = e = "auto" === d.get("width") ? 2 * c.length : this.width, this.interval = Math.floor(e / c.length), this.itemWidth = e / c.length, void 0 !== d.get("chartRangeMin") && (d.get("chartRangeClip") || d.get("chartRangeMin") < this.min) && (this.min = d.get("chartRangeMin")), void 0 !== d.get("chartRangeMax") && (d.get("chartRangeClip") || d.get("chartRangeMax") > this.max) && (this.max = d.get("chartRangeMax")), this.initTarget(), this.target && (this.lineHeight = "auto" === d.get("lineHeight") ? Math.round(.3 * this.canvasHeight) : d.get("lineHeight"))
                },
                getRegion: function(a, b) {
                    return Math.floor(b / this.itemWidth)
                },
                getCurrentRegionFields: function() {
                    var a = this.currentRegion;
                    return {
                        isNull: void 0 === this.values[a],
                        value: this.values[a],
                        offset: a
                    }
                },
                renderRegion: function(a, b) {
                    var c, d, f, g, h = this.values,
                        i = this.options,
                        j = this.min,
                        k = this.max,
                        l = this.range,
                        m = this.interval,
                        n = this.target,
                        o = this.canvasHeight,
                        p = this.lineHeight,
                        q = o - p;
                    return d = e(h[a], j, k), g = a * m, c = Math.round(q - q * ((d - j) / l)), f = i.get(i.get("thresholdColor") && d < i.get("thresholdValue") ? "thresholdColor" : "lineColor"), b && (f = this.calcHighlightColor(f, i)), n.drawLine(g, c, g, c + p, f)
                }
            }), a.fn.sparkline.bullet = x = c(a.fn.sparkline._base, {
                type: "bullet",
                init: function(a, b, c, d, e) {
                    var f, g, i;
                    x._super.init.call(this, a, b, c, d, e), this.values = b = h(b), i = b.slice(), i[0] = null === i[0] ? i[2] : i[0], i[1] = null === b[1] ? i[2] : i[1], f = Math.min.apply(Math, b), g = Math.max.apply(Math, b), f = void 0 === c.get("base") ? 0 > f ? f : 0 : c.get("base"), this.min = f, this.max = g, this.range = g - f, this.shapes = {}, this.valueShapes = {}, this.regiondata = {}, this.width = d = "auto" === c.get("width") ? "4.0em" : d, this.target = this.$el.simpledraw(d, e, c.get("composite")), b.length || (this.disabled = !0), this.initTarget()
                },
                getRegion: function(a, b, c) {
                    var d = this.target.getShapeAt(a, b, c);
                    return void 0 !== d && void 0 !== this.shapes[d] ? this.shapes[d] : void 0
                },
                getCurrentRegionFields: function() {
                    var a = this.currentRegion;
                    return {
                        fieldkey: a.substr(0, 1),
                        value: this.values[a.substr(1)],
                        region: a
                    }
                },
                changeHighlight: function(a) {
                    var b, c = this.currentRegion,
                        d = this.valueShapes[c];
                    switch (delete this.shapes[d], c.substr(0, 1)) {
                        case "r":
                            b = this.renderRange(c.substr(1), a);
                            break;
                        case "p":
                            b = this.renderPerformance(a);
                            break;
                        case "t":
                            b = this.renderTarget(a)
                    }
                    this.valueShapes[c] = b.id, this.shapes[b.id] = c, this.target.replaceWithShape(d, b)
                },
                renderRange: function(a, b) {
                    var c = this.values[a],
                        d = Math.round(this.canvasWidth * ((c - this.min) / this.range)),
                        e = this.options.get("rangeColors")[a - 2];
                    return b && (e = this.calcHighlightColor(e, this.options)), this.target.drawRect(0, 0, d - 1, this.canvasHeight - 1, e, e)
                },
                renderPerformance: function(a) {
                    var b = this.values[1],
                        c = Math.round(this.canvasWidth * ((b - this.min) / this.range)),
                        d = this.options.get("performanceColor");
                    return a && (d = this.calcHighlightColor(d, this.options)), this.target.drawRect(0, Math.round(.3 * this.canvasHeight), c - 1, Math.round(.4 * this.canvasHeight) - 1, d, d)
                },
                renderTarget: function(a) {
                    var b = this.values[0],
                        c = Math.round(this.canvasWidth * ((b - this.min) / this.range) - this.options.get("targetWidth") / 2),
                        d = Math.round(.1 * this.canvasHeight),
                        e = this.canvasHeight - 2 * d,
                        f = this.options.get("targetColor");
                    return a && (f = this.calcHighlightColor(f, this.options)), this.target.drawRect(c, d, this.options.get("targetWidth") - 1, e - 1, f, f)
                },
                render: function() {
                    var a, b, c = this.values.length,
                        d = this.target;
                    if (x._super.render.call(this)) {
                        for (a = 2; c > a; a++) b = this.renderRange(a).append(), this.shapes[b.id] = "r" + a, this.valueShapes["r" + a] = b.id;
                        null !== this.values[1] && (b = this.renderPerformance().append(), this.shapes[b.id] = "p1", this.valueShapes.p1 = b.id), null !== this.values[0] && (b = this.renderTarget().append(), this.shapes[b.id] = "t0", this.valueShapes.t0 = b.id), d.render()
                    }
                }
            }), a.fn.sparkline.pie = y = c(a.fn.sparkline._base, {
                type: "pie",
                init: function(b, c, d, e, f) {
                    var g, h = 0;
                    if (y._super.init.call(this, b, c, d, e, f), this.shapes = {}, this.valueShapes = {}, this.values = c = a.map(c, Number), "auto" === d.get("width") && (this.width = this.height), c.length > 0)
                        for (g = c.length; g--;) h += c[g];
                    this.total = h, this.initTarget(), this.radius = Math.floor(Math.min(this.canvasWidth, this.canvasHeight) / 2)
                },
                getRegion: function(a, b, c) {
                    var d = this.target.getShapeAt(a, b, c);
                    return void 0 !== d && void 0 !== this.shapes[d] ? this.shapes[d] : void 0
                },
                getCurrentRegionFields: function() {
                    var a = this.currentRegion;
                    return {
                        isNull: void 0 === this.values[a],
                        value: this.values[a],
                        percent: this.values[a] / this.total * 100,
                        color: this.options.get("sliceColors")[a % this.options.get("sliceColors").length],
                        offset: a
                    }
                },
                changeHighlight: function(a) {
                    var b = this.currentRegion,
                        c = this.renderSlice(b, a),
                        d = this.valueShapes[b];
                    delete this.shapes[d], this.target.replaceWithShape(d, c), this.valueShapes[b] = c.id, this.shapes[c.id] = b
                },
                renderSlice: function(a, b) {
                    var c, d, e, f, g, h = this.target,
                        i = this.options,
                        j = this.radius,
                        k = i.get("borderWidth"),
                        l = i.get("offset"),
                        m = 2 * Math.PI,
                        n = this.values,
                        o = this.total,
                        p = l ? 2 * Math.PI * (l / 360) : 0;
                    for (f = n.length, e = 0; f > e; e++) {
                        if (c = p, d = p, o > 0 && (d = p + m * (n[e] / o)), a === e) return g = i.get("sliceColors")[e % i.get("sliceColors").length], b && (g = this.calcHighlightColor(g, i)), h.drawPieSlice(j, j, j - k, c, d, void 0, g);
                        p = d
                    }
                },
                render: function() {
                    var a, b, c = this.target,
                        d = this.values,
                        e = this.options,
                        f = this.radius,
                        g = e.get("borderWidth");
                    if (y._super.render.call(this)) {
                        for (g && c.drawCircle(f, f, Math.floor(f - g / 2), e.get("borderColor"), void 0, g).append(), b = d.length; b--;) d[b] && (a = this.renderSlice(b).append(), this.valueShapes[b] = a.id, this.shapes[a.id] = b);
                        c.render()
                    }
                }
            }), a.fn.sparkline.box = z = c(a.fn.sparkline._base, {
                type: "box",
                init: function(b, c, d, e, f) {
                    z._super.init.call(this, b, c, d, e, f), this.values = a.map(c, Number), this.width = "auto" === d.get("width") ? "4.0em" : e, this.initTarget(), this.values.length || (this.disabled = 1)
                },
                getRegion: function() {
                    return 1
                },
                getCurrentRegionFields: function() {
                    var a = [{
                        field: "lq",
                        value: this.quartiles[0]
                    }, {
                        field: "med",
                        value: this.quartiles[1]
                    }, {
                        field: "uq",
                        value: this.quartiles[2]
                    }];
                    return void 0 !== this.loutlier && a.push({
                        field: "lo",
                        value: this.loutlier
                    }), void 0 !== this.routlier && a.push({
                        field: "ro",
                        value: this.routlier
                    }), void 0 !== this.lwhisker && a.push({
                        field: "lw",
                        value: this.lwhisker
                    }), void 0 !== this.rwhisker && a.push({
                        field: "rw",
                        value: this.rwhisker
                    }), a
                },
                render: function() {
                    var a, b, c, d, e, g, h, i, j, k, l, m = this.target,
                        n = this.values,
                        o = n.length,
                        p = this.options,
                        q = this.canvasWidth,
                        r = this.canvasHeight,
                        s = void 0 === p.get("chartRangeMin") ? Math.min.apply(Math, n) : p.get("chartRangeMin"),
                        t = void 0 === p.get("chartRangeMax") ? Math.max.apply(Math, n) : p.get("chartRangeMax"),
                        u = 0;
                    if (z._super.render.call(this)) {
                        if (p.get("raw")) p.get("showOutliers") && n.length > 5 ? (b = n[0], a = n[1], d = n[2], e = n[3], g = n[4], h = n[5], i = n[6]) : (a = n[0], d = n[1], e = n[2], g = n[3], h = n[4]);
                        else if (n.sort(function(a, b) {
                                return a - b
                            }), d = f(n, 1), e = f(n, 2), g = f(n, 3), c = g - d, p.get("showOutliers")) {
                            for (a = h = void 0, j = 0; o > j; j++) void 0 === a && n[j] > d - c * p.get("outlierIQR") && (a = n[j]), n[j] < g + c * p.get("outlierIQR") && (h = n[j]);
                            b = n[0], i = n[o - 1]
                        } else a = n[0], h = n[o - 1];
                        this.quartiles = [d, e, g], this.lwhisker = a, this.rwhisker = h, this.loutlier = b, this.routlier = i, l = q / (t - s + 1), p.get("showOutliers") && (u = Math.ceil(p.get("spotRadius")), q -= 2 * Math.ceil(p.get("spotRadius")), l = q / (t - s + 1), a > b && m.drawCircle((b - s) * l + u, r / 2, p.get("spotRadius"), p.get("outlierLineColor"), p.get("outlierFillColor")).append(), i > h && m.drawCircle((i - s) * l + u, r / 2, p.get("spotRadius"), p.get("outlierLineColor"), p.get("outlierFillColor")).append()), m.drawRect(Math.round((d - s) * l + u), Math.round(.1 * r), Math.round((g - d) * l), Math.round(.8 * r), p.get("boxLineColor"), p.get("boxFillColor")).append(), m.drawLine(Math.round((a - s) * l + u), Math.round(r / 2), Math.round((d - s) * l + u), Math.round(r / 2), p.get("lineColor")).append(), m.drawLine(Math.round((a - s) * l + u), Math.round(r / 4), Math.round((a - s) * l + u), Math.round(r - r / 4), p.get("whiskerColor")).append(), m.drawLine(Math.round((h - s) * l + u), Math.round(r / 2), Math.round((g - s) * l + u), Math.round(r / 2), p.get("lineColor")).append(), m.drawLine(Math.round((h - s) * l + u), Math.round(r / 4), Math.round((h - s) * l + u), Math.round(r - r / 4), p.get("whiskerColor")).append(), m.drawLine(Math.round((e - s) * l + u), Math.round(.1 * r), Math.round((e - s) * l + u), Math.round(.9 * r), p.get("medianColor")).append(), p.get("target") && (k = Math.ceil(p.get("spotRadius")), m.drawLine(Math.round((p.get("target") - s) * l + u), Math.round(r / 2 - k), Math.round((p.get("target") - s) * l + u), Math.round(r / 2 + k), p.get("targetColor")).append(), m.drawLine(Math.round((p.get("target") - s) * l + u - k), Math.round(r / 2), Math.round((p.get("target") - s) * l + u + k), Math.round(r / 2), p.get("targetColor")).append()), m.render()
                    }
                }
            }),
            function() {
                document.namespaces && !document.namespaces.v ? (a.fn.sparkline.hasVML = !0, document.namespaces.add("v", "urn:schemas-microsoft-com:vml", "#default#VML")) : a.fn.sparkline.hasVML = !1;
                var b = document.createElement("canvas");
                a.fn.sparkline.hasCanvas = !(!b.getContext || !b.getContext("2d"))
            }(), C = c({
                init: function(a, b, c, d) {
                    this.target = a, this.id = b, this.type = c, this.args = d
                },
                append: function() {
                    return this.target.appendShape(this), this
                }
            }), D = c({
                _pxregex: /(\d+)(px)?\s*$/i,
                init: function(b, c, d) {
                    b && (this.width = b, this.height = c, this.target = d, this.lastShapeId = null, d[0] && (d = d[0]), a.data(d, "_jqs_vcanvas", this))
                },
                drawLine: function(a, b, c, d, e, f) {
                    return this.drawShape([
                        [a, b],
                        [c, d]
                    ], e, f)
                },
                drawShape: function(a, b, c, d) {
                    return this._genShape("Shape", [a, b, c, d])
                },
                drawCircle: function(a, b, c, d, e, f) {
                    return this._genShape("Circle", [a, b, c, d, e, f])
                },
                drawPieSlice: function(a, b, c, d, e, f, g) {
                    return this._genShape("PieSlice", [a, b, c, d, e, f, g])
                },
                drawRect: function(a, b, c, d, e, f) {
                    return this._genShape("Rect", [a, b, c, d, e, f])
                },
                getElement: function() {
                    return this.canvas
                },
                getLastShapeId: function() {
                    return this.lastShapeId
                },
                reset: function() {
                    alert("reset not implemented")
                },
                _insert: function(b, c) {
                    a(c).html(b)
                },
                _calculatePixelDims: function(b, c, d) {
                    var e;
                    e = this._pxregex.exec(c), this.pixelHeight = e ? e[1] : a(d).height(), e = this._pxregex.exec(b), this.pixelWidth = e ? e[1] : a(d).width()
                },
                _genShape: function(a, b) {
                    var c = I++;
                    return b.unshift(c), new C(this, c, a, b)
                },
                appendShape: function() {
                    alert("appendShape not implemented")
                },
                replaceWithShape: function() {
                    alert("replaceWithShape not implemented")
                },
                insertAfterShape: function() {
                    alert("insertAfterShape not implemented")
                },
                removeShapeId: function() {
                    alert("removeShapeId not implemented")
                },
                getShapeAt: function() {
                    alert("getShapeAt not implemented")
                },
                render: function() {
                    alert("render not implemented")
                }
            }), E = c(D, {
                init: function(b, c, d, e) {
                    E._super.init.call(this, b, c, d), this.canvas = document.createElement("canvas"), d[0] && (d = d[0]), a.data(d, "_jqs_vcanvas", this), a(this.canvas).css({
                        display: "inline-block",
                        width: b,
                        height: c,
                        verticalAlign: "top"
                    }), this._insert(this.canvas, d), this._calculatePixelDims(b, c, this.canvas), this.canvas.width = this.pixelWidth, this.canvas.height = this.pixelHeight, this.interact = e, this.shapes = {}, this.shapeseq = [], this.currentTargetShapeId = void 0, a(this.canvas).css({
                        width: this.pixelWidth,
                        height: this.pixelHeight
                    })
                },
                _getContext: function(a, b, c) {
                    var d = this.canvas.getContext("2d");
                    return void 0 !== a && (d.strokeStyle = a), d.lineWidth = void 0 === c ? 1 : c, void 0 !== b && (d.fillStyle = b), d
                },
                reset: function() {
                    var a = this._getContext();
                    a.clearRect(0, 0, this.pixelWidth, this.pixelHeight), this.shapes = {}, this.shapeseq = [], this.currentTargetShapeId = void 0
                },
                _drawShape: function(a, b, c, d, e) {
                    var f, g, h = this._getContext(c, d, e);
                    for (h.beginPath(), h.moveTo(b[0][0] + .5, b[0][1] + .5), f = 1, g = b.length; g > f; f++) h.lineTo(b[f][0] + .5, b[f][1] + .5);
                    void 0 !== c && h.stroke(), void 0 !== d && h.fill(), void 0 !== this.targetX && void 0 !== this.targetY && h.isPointInPath(this.targetX, this.targetY) && (this.currentTargetShapeId = a)
                },
                _drawCircle: function(a, b, c, d, e, f, g) {
                    var h = this._getContext(e, f, g);
                    h.beginPath(), h.arc(b, c, d, 0, 2 * Math.PI, !1), void 0 !== this.targetX && void 0 !== this.targetY && h.isPointInPath(this.targetX, this.targetY) && (this.currentTargetShapeId = a), void 0 !== e && h.stroke(), void 0 !== f && h.fill()
                },
                _drawPieSlice: function(a, b, c, d, e, f, g, h) {
                    var i = this._getContext(g, h);
                    i.beginPath(), i.moveTo(b, c), i.arc(b, c, d, e, f, !1), i.lineTo(b, c), i.closePath(), void 0 !== g && i.stroke(), h && i.fill(), void 0 !== this.targetX && void 0 !== this.targetY && i.isPointInPath(this.targetX, this.targetY) && (this.currentTargetShapeId = a)
                },
                _drawRect: function(a, b, c, d, e, f, g) {
                    return this._drawShape(a, [
                        [b, c],
                        [b + d, c],
                        [b + d, c + e],
                        [b, c + e],
                        [b, c]
                    ], f, g)
                },
                appendShape: function(a) {
                    return this.shapes[a.id] = a, this.shapeseq.push(a.id), this.lastShapeId = a.id, a.id
                },
                replaceWithShape: function(a, b) {
                    var c, d = this.shapeseq;
                    for (this.shapes[b.id] = b, c = d.length; c--;) d[c] == a && (d[c] = b.id);
                    delete this.shapes[a]
                },
                replaceWithShapes: function(a, b) {
                    var c, d, e, f = this.shapeseq,
                        g = {};
                    for (d = a.length; d--;) g[a[d]] = !0;
                    for (d = f.length; d--;) c = f[d], g[c] && (f.splice(d, 1), delete this.shapes[c], e = d);
                    for (d = b.length; d--;) f.splice(e, 0, b[d].id), this.shapes[b[d].id] = b[d]
                },
                insertAfterShape: function(a, b) {
                    var c, d = this.shapeseq;
                    for (c = d.length; c--;)
                        if (d[c] === a) return d.splice(c + 1, 0, b.id), void(this.shapes[b.id] = b)
                },
                removeShapeId: function(a) {
                    var b, c = this.shapeseq;
                    for (b = c.length; b--;)
                        if (c[b] === a) {
                            c.splice(b, 1);
                            break
                        }
                    delete this.shapes[a]
                },
                getShapeAt: function(a, b, c) {
                    return this.targetX = b, this.targetY = c, this.render(), this.currentTargetShapeId
                },
                render: function() {
                    var a, b, c, d = this.shapeseq,
                        e = this.shapes,
                        f = d.length,
                        g = this._getContext();
                    for (g.clearRect(0, 0, this.pixelWidth, this.pixelHeight), c = 0; f > c; c++) a = d[c], b = e[a], this["_draw" + b.type].apply(this, b.args);
                    this.interact || (this.shapes = {}, this.shapeseq = [])
                }
            }), F = c(D, {
                init: function(b, c, d) {
                    var e;
                    F._super.init.call(this, b, c, d), d[0] && (d = d[0]), a.data(d, "_jqs_vcanvas", this), this.canvas = document.createElement("span"), a(this.canvas).css({
                        display: "inline-block",
                        position: "relative",
                        overflow: "hidden",
                        width: b,
                        height: c,
                        margin: "0px",
                        padding: "0px",
                        verticalAlign: "top"
                    }), this._insert(this.canvas, d), this._calculatePixelDims(b, c, this.canvas), this.canvas.width = this.pixelWidth, this.canvas.height = this.pixelHeight, e = '<v:group coordorigin="0 0" coordsize="' + this.pixelWidth + " " + this.pixelHeight + '" style="position:absolute;top:0;left:0;width:' + this.pixelWidth + "px;height=" + this.pixelHeight + 'px;"></v:group>', this.canvas.insertAdjacentHTML("beforeEnd", e), this.group = a(this.canvas).children()[0], this.rendered = !1, this.prerender = ""
                },
                _drawShape: function(a, b, c, d, e) {
                    var f, g, h, i, j, k, l, m = [];
                    for (l = 0, k = b.length; k > l; l++) m[l] = "" + b[l][0] + "," + b[l][1];
                    return f = m.splice(0, 1), e = void 0 === e ? 1 : e, g = void 0 === c ? ' stroked="false" ' : ' strokeWeight="' + e + 'px" strokeColor="' + c + '" ', h = void 0 === d ? ' filled="false"' : ' fillColor="' + d + '" filled="true" ', i = m[0] === m[m.length - 1] ? "x " : "", j = '<v:shape coordorigin="0 0" coordsize="' + this.pixelWidth + " " + this.pixelHeight + '"  id="jqsshape' + a + '" ' + g + h + ' style="position:absolute;left:0px;top:0px;height:' + this.pixelHeight + "px;width:" + this.pixelWidth + 'px;padding:0px;margin:0px;"  path="m ' + f + " l " + m.join(", ") + " " + i + 'e"> </v:shape>'
                },
                _drawCircle: function(a, b, c, d, e, f, g) {
                    var h, i, j;
                    return b -= d, c -= d, h = void 0 === e ? ' stroked="false" ' : ' strokeWeight="' + g + 'px" strokeColor="' + e + '" ', i = void 0 === f ? ' filled="false"' : ' fillColor="' + f + '" filled="true" ', j = '<v:oval  id="jqsshape' + a + '" ' + h + i + ' style="position:absolute;top:' + c + "px; left:" + b + "px; width:" + 2 * d + "px; height:" + 2 * d + 'px"></v:oval>'
                },
                _drawPieSlice: function(a, b, c, d, e, f, g, h) {
                    var i, j, k, l, m, n, o, p;
                    if (e === f) return "";
                    if (f - e === 2 * Math.PI && (e = 0, f = 2 * Math.PI), j = b + Math.round(Math.cos(e) * d), k = c + Math.round(Math.sin(e) * d), l = b + Math.round(Math.cos(f) * d), m = c + Math.round(Math.sin(f) * d), j === l && k === m) {
                        if (f - e < Math.PI) return "";
                        j = l = b + d, k = m = c
                    }
                    return j === l && k === m && f - e < Math.PI ? "" : (i = [b - d, c - d, b + d, c + d, j, k, l, m], n = void 0 === g ? ' stroked="false" ' : ' strokeWeight="1px" strokeColor="' + g + '" ', o = void 0 === h ? ' filled="false"' : ' fillColor="' + h + '" filled="true" ', p = '<v:shape coordorigin="0 0" coordsize="' + this.pixelWidth + " " + this.pixelHeight + '"  id="jqsshape' + a + '" ' + n + o + ' style="position:absolute;left:0px;top:0px;height:' + this.pixelHeight + "px;width:" + this.pixelWidth + 'px;padding:0px;margin:0px;"  path="m ' + b + "," + c + " wa " + i.join(", ") + ' x e"> </v:shape>')
                },
                _drawRect: function(a, b, c, d, e, f, g) {
                    return this._drawShape(a, [
                        [b, c],
                        [b, c + e],
                        [b + d, c + e],
                        [b + d, c],
                        [b, c]
                    ], f, g)
                },
                reset: function() {
                    this.group.innerHTML = ""
                },
                appendShape: function(a) {
                    var b = this["_draw" + a.type].apply(this, a.args);
                    return this.rendered ? this.group.insertAdjacentHTML("beforeEnd", b) : this.prerender += b, this.lastShapeId = a.id, a.id
                },
                replaceWithShape: function(b, c) {
                    var d = a("#jqsshape" + b),
                        e = this["_draw" + c.type].apply(this, c.args);
                    d[0].outerHTML = e
                },
                replaceWithShapes: function(b, c) {
                    var d, e = a("#jqsshape" + b[0]),
                        f = "",
                        g = c.length;
                    for (d = 0; g > d; d++) f += this["_draw" + c[d].type].apply(this, c[d].args);
                    for (e[0].outerHTML = f, d = 1; d < b.length; d++) a("#jqsshape" + b[d]).remove()
                },
                insertAfterShape: function(b, c) {
                    var d = a("#jqsshape" + b),
                        e = this["_draw" + c.type].apply(this, c.args);
                    d[0].insertAdjacentHTML("afterEnd", e)
                },
                removeShapeId: function(b) {
                    var c = a("#jqsshape" + b);
                    this.group.removeChild(c[0])
                },
                getShapeAt: function(a) {
                    var b = a.id.substr(8);
                    return b
                },
                render: function() {
                    this.rendered || (this.group.innerHTML = this.prerender, this.rendered = !0)
                }
            })
    }), function(a) {
        a.color = {}, a.color.make = function(b, c, d, e) {
            var f = {};
            return f.r = b || 0, f.g = c || 0, f.b = d || 0, f.a = null != e ? e : 1, f.add = function(a, b) {
                for (var c = 0; c < a.length; ++c) f[a.charAt(c)] += b;
                return f.normalize()
            }, f.scale = function(a, b) {
                for (var c = 0; c < a.length; ++c) f[a.charAt(c)] *= b;
                return f.normalize()
            }, f.toString = function() {
                return f.a >= 1 ? "rgb(" + [f.r, f.g, f.b].join(",") + ")" : "rgba(" + [f.r, f.g, f.b, f.a].join(",") + ")"
            }, f.normalize = function() {
                function a(a, b, c) {
                    return a > b ? a : b > c ? c : b
                }
                return f.r = a(0, parseInt(f.r), 255), f.g = a(0, parseInt(f.g), 255), f.b = a(0, parseInt(f.b), 255), f.a = a(0, f.a, 1), f
            }, f.clone = function() {
                return a.color.make(f.r, f.b, f.g, f.a)
            }, f.normalize()
        }, a.color.extract = function(b, c) {
            var d;
            do {
                if (d = b.css(c).toLowerCase(), "" != d && "transparent" != d) break;
                b = b.parent()
            } while (b.length && !a.nodeName(b.get(0), "body"));
            return "rgba(0, 0, 0, 0)" == d && (d = "transparent"), a.color.parse(d)
        }, a.color.parse = function(c) {
            var d, e = a.color.make;
            if (d = /rgb\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*\)/.exec(c)) return e(parseInt(d[1], 10), parseInt(d[2], 10), parseInt(d[3], 10));
            if (d = /rgba\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]+(?:\.[0-9]+)?)\s*\)/.exec(c)) return e(parseInt(d[1], 10), parseInt(d[2], 10), parseInt(d[3], 10), parseFloat(d[4]));
            if (d = /rgb\(\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*\)/.exec(c)) return e(2.55 * parseFloat(d[1]), 2.55 * parseFloat(d[2]), 2.55 * parseFloat(d[3]));
            if (d = /rgba\(\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\s*\)/.exec(c)) return e(2.55 * parseFloat(d[1]), 2.55 * parseFloat(d[2]), 2.55 * parseFloat(d[3]), parseFloat(d[4]));
            if (d = /#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})/.exec(c)) return e(parseInt(d[1], 16), parseInt(d[2], 16), parseInt(d[3], 16));
            if (d = /#([a-fA-F0-9])([a-fA-F0-9])([a-fA-F0-9])/.exec(c)) return e(parseInt(d[1] + d[1], 16), parseInt(d[2] + d[2], 16), parseInt(d[3] + d[3], 16));
            var f = a.trim(c).toLowerCase();
            return "transparent" == f ? e(255, 255, 255, 0) : (d = b[f] || [0, 0, 0], e(d[0], d[1], d[2]))
        };
        var b = {
            aqua: [0, 255, 255],
            azure: [240, 255, 255],
            beige: [245, 245, 220],
            black: [0, 0, 0],
            blue: [0, 0, 255],
            brown: [165, 42, 42],
            cyan: [0, 255, 255],
            darkblue: [0, 0, 139],
            darkcyan: [0, 139, 139],
            darkgrey: [169, 169, 169],
            darkgreen: [0, 100, 0],
            darkkhaki: [189, 183, 107],
            darkmagenta: [139, 0, 139],
            darkolivegreen: [85, 107, 47],
            darkorange: [255, 140, 0],
            darkorchid: [153, 50, 204],
            darkred: [139, 0, 0],
            darksalmon: [233, 150, 122],
            darkviolet: [148, 0, 211],
            fuchsia: [255, 0, 255],
            gold: [255, 215, 0],
            green: [0, 128, 0],
            indigo: [75, 0, 130],
            khaki: [240, 230, 140],
            lightblue: [173, 216, 230],
            lightcyan: [224, 255, 255],
            lightgreen: [144, 238, 144],
            lightgrey: [211, 211, 211],
            lightpink: [255, 182, 193],
            lightyellow: [255, 255, 224],
            lime: [0, 255, 0],
            magenta: [255, 0, 255],
            maroon: [128, 0, 0],
            navy: [0, 0, 128],
            olive: [128, 128, 0],
            orange: [255, 165, 0],
            pink: [255, 192, 203],
            purple: [128, 0, 128],
            violet: [128, 0, 128],
            red: [255, 0, 0],
            silver: [192, 192, 192],
            white: [255, 255, 255],
            yellow: [255, 255, 0]
        }
    }(jQuery), function(a) {
        function b(b, c) {
            var d = c.children("." + b)[0];
            if (null == d && (d = document.createElement("canvas"), d.className = b, a(d).css({
                    direction: "ltr",
                    position: "absolute",
                    left: 0,
                    top: 0
                }).appendTo(c), !d.getContext)) {
                if (!window.G_vmlCanvasManager) throw new Error("Canvas is not available. If you're using IE with a fall-back such as Excanvas, then there's either a mistake in your conditional include, or the page has no DOCTYPE and is rendering in Quirks Mode.");
                d = window.G_vmlCanvasManager.initElement(d)
            }
            this.element = d;
            var e = this.context = d.getContext("2d"),
                f = window.devicePixelRatio || 1,
                g = e.webkitBackingStorePixelRatio || e.mozBackingStorePixelRatio || e.msBackingStorePixelRatio || e.oBackingStorePixelRatio || e.backingStorePixelRatio || 1;
            this.pixelRatio = f / g, this.resize(c.width(), c.height()), this.textContainer = null, this.text = {}, this._textCache = {}
        }

        function c(c, e, f, g) {
            function h(a, b) {
                b = [qb].concat(b);
                for (var c = 0; c < a.length; ++c) a[c].apply(this, b)
            }

            function i() {
                for (var c = {
                        Canvas: b
                    }, d = 0; d < g.length; ++d) {
                    var e = g[d];
                    e.init(qb, c), e.options && a.extend(!0, eb, e.options)
                }
            }

            function j(b) {
                a.extend(!0, eb, b), b && b.colors && (eb.colors = b.colors), null == eb.xaxis.color && (eb.xaxis.color = a.color.parse(eb.grid.color).scale("a", .22).toString()), null == eb.yaxis.color && (eb.yaxis.color = a.color.parse(eb.grid.color).scale("a", .22).toString()), null == eb.xaxis.tickColor && (eb.xaxis.tickColor = eb.grid.tickColor || eb.xaxis.color), null == eb.yaxis.tickColor && (eb.yaxis.tickColor = eb.grid.tickColor || eb.yaxis.color), null == eb.grid.borderColor && (eb.grid.borderColor = eb.grid.color), null == eb.grid.tickColor && (eb.grid.tickColor = a.color.parse(eb.grid.color).scale("a", .22).toString());
                var d, e, f, g = c.css("font-size"),
                    i = g ? +g.replace("px", "") : 13,
                    j = {
                        style: c.css("font-style"),
                        size: Math.round(.8 * i),
                        variant: c.css("font-variant"),
                        weight: c.css("font-weight"),
                        family: c.css("font-family")
                    };
                for (f = eb.xaxes.length || 1, d = 0; f > d; ++d) e = eb.xaxes[d], e && !e.tickColor && (e.tickColor = e.color), e = a.extend(!0, {}, eb.xaxis, e), eb.xaxes[d] = e, e.font && (e.font = a.extend({}, j, e.font), e.font.color || (e.font.color = e.color), e.font.lineHeight || (e.font.lineHeight = Math.round(1.15 * e.font.size)));
                for (f = eb.yaxes.length || 1, d = 0; f > d; ++d) e = eb.yaxes[d], e && !e.tickColor && (e.tickColor = e.color), e = a.extend(!0, {}, eb.yaxis, e), eb.yaxes[d] = e, e.font && (e.font = a.extend({}, j, e.font), e.font.color || (e.font.color = e.color), e.font.lineHeight || (e.font.lineHeight = Math.round(1.15 * e.font.size)));
                for (eb.xaxis.noTicks && null == eb.xaxis.ticks && (eb.xaxis.ticks = eb.xaxis.noTicks), eb.yaxis.noTicks && null == eb.yaxis.ticks && (eb.yaxis.ticks = eb.yaxis.noTicks), eb.x2axis && (eb.xaxes[1] = a.extend(!0, {}, eb.xaxis, eb.x2axis), eb.xaxes[1].position = "top", null == eb.x2axis.min && (eb.xaxes[1].min = null), null == eb.x2axis.max && (eb.xaxes[1].max = null)), eb.y2axis && (eb.yaxes[1] = a.extend(!0, {}, eb.yaxis, eb.y2axis), eb.yaxes[1].position = "right", null == eb.y2axis.min && (eb.yaxes[1].min = null), null == eb.y2axis.max && (eb.yaxes[1].max = null)), eb.grid.coloredAreas && (eb.grid.markings = eb.grid.coloredAreas), eb.grid.coloredAreasColor && (eb.grid.markingsColor = eb.grid.coloredAreasColor), eb.lines && a.extend(!0, eb.series.lines, eb.lines), eb.points && a.extend(!0, eb.series.points, eb.points), eb.bars && a.extend(!0, eb.series.bars, eb.bars), null != eb.shadowSize && (eb.series.shadowSize = eb.shadowSize), null != eb.highlightColor && (eb.series.highlightColor = eb.highlightColor), d = 0; d < eb.xaxes.length; ++d) q(kb, d + 1).options = eb.xaxes[d];
                for (d = 0; d < eb.yaxes.length; ++d) q(lb, d + 1).options = eb.yaxes[d];
                for (var k in pb) eb.hooks[k] && eb.hooks[k].length && (pb[k] = pb[k].concat(eb.hooks[k]));
                h(pb.processOptions, [eb])
            }

            function k(a) {
                db = l(a), r(), s()
            }

            function l(b) {
                for (var c = [], d = 0; d < b.length; ++d) {
                    var e = a.extend(!0, {}, eb.series);
                    null != b[d].data ? (e.data = b[d].data, delete b[d].data, a.extend(!0, e, b[d]), b[d].data = e.data) : e.data = b[d], c.push(e)
                }
                return c
            }

            function m(a, b) {
                var c = a[b + "axis"];
                return "object" == typeof c && (c = c.n), "number" != typeof c && (c = 1), c
            }

            function n() {
                return a.grep(kb.concat(lb), function(a) {
                    return a
                })
            }

            function o(a) {
                var b, c, d = {};
                for (b = 0; b < kb.length; ++b) c = kb[b], c && c.used && (d["x" + c.n] = c.c2p(a.left));
                for (b = 0; b < lb.length; ++b) c = lb[b], c && c.used && (d["y" + c.n] = c.c2p(a.top));
                return void 0 !== d.x1 && (d.x = d.x1), void 0 !== d.y1 && (d.y = d.y1), d
            }

            function p(a) {
                var b, c, d, e = {};
                for (b = 0; b < kb.length; ++b)
                    if (c = kb[b], c && c.used && (d = "x" + c.n, null == a[d] && 1 == c.n && (d = "x"), null != a[d])) {
                        e.left = c.p2c(a[d]);
                        break
                    }
                for (b = 0; b < lb.length; ++b)
                    if (c = lb[b], c && c.used && (d = "y" + c.n, null == a[d] && 1 == c.n && (d = "y"), null != a[d])) {
                        e.top = c.p2c(a[d]);
                        break
                    }
                return e
            }

            function q(b, c) {
                return b[c - 1] || (b[c - 1] = {
                    n: c,
                    direction: b == kb ? "x" : "y",
                    options: a.extend(!0, {}, b == kb ? eb.xaxis : eb.yaxis)
                }), b[c - 1]
            }

            function r() {
                var b, c = db.length,
                    d = -1;
                for (b = 0; b < db.length; ++b) {
                    var e = db[b].color;
                    null != e && (c--, "number" == typeof e && e > d && (d = e))
                }
                d >= c && (c = d + 1);
                var f, g = [],
                    h = eb.colors,
                    i = h.length,
                    j = 0;
                for (b = 0; c > b; b++) f = a.color.parse(h[b % i] || "#666"), b % i == 0 && b && (j = j >= 0 ? .5 > j ? -j - .2 : 0 : -j), g[b] = f.scale("rgb", 1 + j);
                var k, l = 0;
                for (b = 0; b < db.length; ++b) {
                    if (k = db[b], null == k.color ? (k.color = g[l].toString(), ++l) : "number" == typeof k.color && (k.color = g[k.color].toString()), null == k.lines.show) {
                        var n, o = !0;
                        for (n in k)
                            if (k[n] && k[n].show) {
                                o = !1;
                                break
                            }
                        o && (k.lines.show = !0)
                    }
                    null == k.lines.zero && (k.lines.zero = !!k.lines.fill), k.xaxis = q(kb, m(k, "x")), k.yaxis = q(lb, m(k, "y"))
                }
            }

            function s() {
                function b(a, b, c) {
                    b < a.datamin && b != -s && (a.datamin = b), c > a.datamax && c != s && (a.datamax = c)
                }
                var c, d, e, f, g, i, j, k, l, m, o, p, q = Number.POSITIVE_INFINITY,
                    r = Number.NEGATIVE_INFINITY,
                    s = Number.MAX_VALUE;
                for (a.each(n(), function(a, b) {
                        b.datamin = q, b.datamax = r, b.used = !1
                    }), c = 0; c < db.length; ++c) g = db[c], g.datapoints = {
                    points: []
                }, h(pb.processRawData, [g, g.data, g.datapoints]);
                for (c = 0; c < db.length; ++c) {
                    if (g = db[c], o = g.data, p = g.datapoints.format, !p) {
                        if (p = [], p.push({
                                x: !0,
                                number: !0,
                                required: !0
                            }), p.push({
                                y: !0,
                                number: !0,
                                required: !0
                            }), g.bars.show || g.lines.show && g.lines.fill) {
                            var t = !!(g.bars.show && g.bars.zero || g.lines.show && g.lines.zero);
                            p.push({
                                y: !0,
                                number: !0,
                                required: !1,
                                defaultValue: 0,
                                autoscale: t
                            }), g.bars.horizontal && (delete p[p.length - 1].y, p[p.length - 1].x = !0)
                        }
                        g.datapoints.format = p
                    }
                    if (null == g.datapoints.pointsize) {
                        g.datapoints.pointsize = p.length, j = g.datapoints.pointsize, i = g.datapoints.points;
                        var u = g.lines.show && g.lines.steps;
                        for (g.xaxis.used = g.yaxis.used = !0, d = e = 0; d < o.length; ++d, e += j) {
                            m = o[d];
                            var v = null == m;
                            if (!v)
                                for (f = 0; j > f; ++f) k = m[f], l = p[f], l && (l.number && null != k && (k = +k, isNaN(k) ? k = null : 1 / 0 == k ? k = s : k == -1 / 0 && (k = -s)), null == k && (l.required && (v = !0), null != l.defaultValue && (k = l.defaultValue))), i[e + f] = k;
                            if (v)
                                for (f = 0; j > f; ++f) k = i[e + f], null != k && (l = p[f], l.autoscale !== !1 && (l.x && b(g.xaxis, k, k), l.y && b(g.yaxis, k, k))), i[e + f] = null;
                            else if (u && e > 0 && null != i[e - j] && i[e - j] != i[e] && i[e - j + 1] != i[e + 1]) {
                                for (f = 0; j > f; ++f) i[e + j + f] = i[e + f];
                                i[e + 1] = i[e - j + 1], e += j
                            }
                        }
                    }
                }
                for (c = 0; c < db.length; ++c) g = db[c], h(pb.processDatapoints, [g, g.datapoints]);
                for (c = 0; c < db.length; ++c) {
                    g = db[c], i = g.datapoints.points, j = g.datapoints.pointsize, p = g.datapoints.format;
                    var w = q,
                        x = q,
                        y = r,
                        z = r;
                    for (d = 0; d < i.length; d += j)
                        if (null != i[d])
                            for (f = 0; j > f; ++f) k = i[d + f], l = p[f], l && l.autoscale !== !1 && k != s && k != -s && (l.x && (w > k && (w = k), k > y && (y = k)), l.y && (x > k && (x = k), k > z && (z = k)));
                    if (g.bars.show) {
                        var A;
                        switch (g.bars.align) {
                            case "left":
                                A = 0;
                                break;
                            case "right":
                                A = -g.bars.barWidth;
                                break;
                            default:
                                A = -g.bars.barWidth / 2
                        }
                        g.bars.horizontal ? (x += A, z += A + g.bars.barWidth) : (w += A, y += A + g.bars.barWidth)
                    }
                    b(g.xaxis, w, y), b(g.yaxis, x, z)
                }
                a.each(n(), function(a, b) {
                    b.datamin == q && (b.datamin = null), b.datamax == r && (b.datamax = null)
                })
            }

            function t() {
                c.css("padding", 0).children().filter(function() {
                    return !a(this).hasClass("flot-overlay") && !a(this).hasClass("flot-base")
                }).remove(), "static" == c.css("position") && c.css("position", "relative"), fb = new b("flot-base", c), gb = new b("flot-overlay", c), ib = fb.context, jb = gb.context, hb = a(gb.element).unbind();
                var d = c.data("plot");
                d && (d.shutdown(), gb.clear()), c.data("plot", qb)
            }

            function u() {
                eb.grid.hoverable && (hb.mousemove(T), hb.bind("mouseleave", U)), eb.grid.clickable && hb.click(V), h(pb.bindEvents, [hb])
            }

            function v() {
                sb && clearTimeout(sb), hb.unbind("mousemove", T), hb.unbind("mouseleave", U), hb.unbind("click", V), h(pb.shutdown, [hb])
            }

            function w(a) {
                function b(a) {
                    return a
                }
                var c, d, e = a.options.transform || b,
                    f = a.options.inverseTransform;
                "x" == a.direction ? (c = a.scale = nb / Math.abs(e(a.max) - e(a.min)), d = Math.min(e(a.max), e(a.min))) : (c = a.scale = ob / Math.abs(e(a.max) - e(a.min)), c = -c, d = Math.max(e(a.max), e(a.min))), a.p2c = e == b ? function(a) {
                    return (a - d) * c
                } : function(a) {
                    return (e(a) - d) * c
                }, a.c2p = f ? function(a) {
                    return f(d + a / c)
                } : function(a) {
                    return d + a / c
                }
            }

            function x(a) {
                for (var b = a.options, c = a.ticks || [], d = b.labelWidth || 0, e = b.labelHeight || 0, f = d || ("x" == a.direction ? Math.floor(fb.width / (c.length || 1)) : null), g = a.direction + "Axis " + a.direction + a.n + "Axis", h = "flot-" + a.direction + "-axis flot-" + a.direction + a.n + "-axis " + g, i = b.font || "flot-tick-label tickLabel", j = 0; j < c.length; ++j) {
                    var k = c[j];
                    if (k.label) {
                        var l = fb.getTextInfo(h, k.label, i, null, f);
                        d = Math.max(d, l.width), e = Math.max(e, l.height)
                    }
                }
                a.labelWidth = b.labelWidth || d, a.labelHeight = b.labelHeight || e
            }

            function y(b) {
                var c = b.labelWidth,
                    d = b.labelHeight,
                    e = b.options.position,
                    f = "x" === b.direction,
                    g = b.options.tickLength,
                    h = eb.grid.axisMargin,
                    i = eb.grid.labelMargin,
                    j = !0,
                    k = !0,
                    l = !0,
                    m = !1;
                a.each(f ? kb : lb, function(a, c) {
                    c && (c.show || c.reserveSpace) && (c === b ? m = !0 : c.options.position === e && (m ? k = !1 : j = !1), m || (l = !1))
                }), k && (h = 0), null == g && (g = l ? "full" : 5), isNaN(+g) || (i += +g), f ? (d += i, "bottom" == e ? (mb.bottom += d + h, b.box = {
                    top: fb.height - mb.bottom,
                    height: d
                }) : (b.box = {
                    top: mb.top + h,
                    height: d
                }, mb.top += d + h)) : (c += i, "left" == e ? (b.box = {
                    left: mb.left + h,
                    width: c
                }, mb.left += c + h) : (mb.right += c + h, b.box = {
                    left: fb.width - mb.right,
                    width: c
                })), b.position = e, b.tickLength = g, b.box.padding = i, b.innermost = j
            }

            function z(a) {
                "x" == a.direction ? (a.box.left = mb.left - a.labelWidth / 2, a.box.width = fb.width - mb.left - mb.right + a.labelWidth) : (a.box.top = mb.top - a.labelHeight / 2, a.box.height = fb.height - mb.bottom - mb.top + a.labelHeight)
            }

            function A() {
                var b, c = eb.grid.minBorderMargin;
                if (null == c)
                    for (c = 0, b = 0; b < db.length; ++b) c = Math.max(c, 2 * (db[b].points.radius + db[b].points.lineWidth / 2));
                var d = {
                    left: c,
                    right: c,
                    top: c,
                    bottom: c
                };
                a.each(n(), function(a, b) {
                    b.reserveSpace && b.ticks && b.ticks.length && ("x" === b.direction ? (d.left = Math.max(d.left, b.labelWidth / 2), d.right = Math.max(d.right, b.labelWidth / 2)) : (d.bottom = Math.max(d.bottom, b.labelHeight / 2), d.top = Math.max(d.top, b.labelHeight / 2)))
                }), mb.left = Math.ceil(Math.max(d.left, mb.left)), mb.right = Math.ceil(Math.max(d.right, mb.right)), mb.top = Math.ceil(Math.max(d.top, mb.top)), mb.bottom = Math.ceil(Math.max(d.bottom, mb.bottom))
            }

            function B() {
                var b, c = n(),
                    d = eb.grid.show;
                for (var e in mb) {
                    var f = eb.grid.margin || 0;
                    mb[e] = "number" == typeof f ? f : f[e] || 0
                }
                h(pb.processOffset, [mb]);
                for (var e in mb) mb[e] += "object" == typeof eb.grid.borderWidth ? d ? eb.grid.borderWidth[e] : 0 : d ? eb.grid.borderWidth : 0;
                if (a.each(c, function(a, b) {
                        var c = b.options;
                        b.show = null == c.show ? b.used : c.show, b.reserveSpace = null == c.reserveSpace ? b.show : c.reserveSpace, C(b)
                    }), d) {
                    var g = a.grep(c, function(a) {
                        return a.show || a.reserveSpace
                    });
                    for (a.each(g, function(a, b) {
                            D(b), E(b), F(b, b.ticks), x(b)
                        }), b = g.length - 1; b >= 0; --b) y(g[b]);
                    A(), a.each(g, function(a, b) {
                        z(b)
                    })
                }
                nb = fb.width - mb.left - mb.right, ob = fb.height - mb.bottom - mb.top, a.each(c, function(a, b) {
                    w(b)
                }), d && K(), R()
            }

            function C(a) {
                var b = a.options,
                    c = +(null != b.min ? b.min : a.datamin),
                    d = +(null != b.max ? b.max : a.datamax),
                    e = d - c;
                if (0 == e) {
                    var f = 0 == d ? 1 : .01;
                    null == b.min && (c -= f), (null == b.max || null != b.min) && (d += f)
                } else {
                    var g = b.autoscaleMargin;
                    null != g && (null == b.min && (c -= e * g, 0 > c && null != a.datamin && a.datamin >= 0 && (c = 0)), null == b.max && (d += e * g, d > 0 && null != a.datamax && a.datamax <= 0 && (d = 0)))
                }
                a.min = c, a.max = d
            }

            function D(b) {
                var c, e = b.options;
                c = "number" == typeof e.ticks && e.ticks > 0 ? e.ticks : .3 * Math.sqrt("x" == b.direction ? fb.width : fb.height);
                var f = (b.max - b.min) / c,
                    g = -Math.floor(Math.log(f) / Math.LN10),
                    h = e.tickDecimals;
                null != h && g > h && (g = h);
                var i, j = Math.pow(10, -g),
                    k = f / j;
                if (1.5 > k ? i = 1 : 3 > k ? (i = 2, k > 2.25 && (null == h || h >= g + 1) && (i = 2.5, ++g)) : i = 7.5 > k ? 5 : 10, i *= j, null != e.minTickSize && i < e.minTickSize && (i = e.minTickSize), b.delta = f, b.tickDecimals = Math.max(0, null != h ? h : g), b.tickSize = e.tickSize || i, "time" == e.mode && !b.tickGenerator) throw new Error("Time mode requires the flot.time plugin.");
                if (b.tickGenerator || (b.tickGenerator = function(a) {
                        var b, c = [],
                            e = d(a.min, a.tickSize),
                            f = 0,
                            g = Number.NaN;
                        do b = g, g = e + f * a.tickSize, c.push(g), ++f; while (g < a.max && g != b);
                        return c
                    }, b.tickFormatter = function(a, b) {
                        var c = b.tickDecimals ? Math.pow(10, b.tickDecimals) : 1,
                            d = "" + Math.round(a * c) / c;
                        if (null != b.tickDecimals) {
                            var e = d.indexOf("."),
                                f = -1 == e ? 0 : d.length - e - 1;
                            if (f < b.tickDecimals) return (f ? d : d + ".") + ("" + c).substr(1, b.tickDecimals - f)
                        }
                        return d
                    }), a.isFunction(e.tickFormatter) && (b.tickFormatter = function(a, b) {
                        return "" + e.tickFormatter(a, b)
                    }), null != e.alignTicksWithAxis) {
                    var l = ("x" == b.direction ? kb : lb)[e.alignTicksWithAxis - 1];
                    if (l && l.used && l != b) {
                        var m = b.tickGenerator(b);
                        if (m.length > 0 && (null == e.min && (b.min = Math.min(b.min, m[0])), null == e.max && m.length > 1 && (b.max = Math.max(b.max, m[m.length - 1]))), b.tickGenerator = function(a) {
                                var b, c, d = [];
                                for (c = 0; c < l.ticks.length; ++c) b = (l.ticks[c].v - l.min) / (l.max - l.min), b = a.min + b * (a.max - a.min), d.push(b);
                                return d
                            }, !b.mode && null == e.tickDecimals) {
                            var n = Math.max(0, -Math.floor(Math.log(b.delta) / Math.LN10) + 1),
                                o = b.tickGenerator(b);
                            o.length > 1 && /\..*0$/.test((o[1] - o[0]).toFixed(n)) || (b.tickDecimals = n)
                        }
                    }
                }
            }

            function E(b) {
                var c = b.options.ticks,
                    d = [];
                null == c || "number" == typeof c && c > 0 ? d = b.tickGenerator(b) : c && (d = a.isFunction(c) ? c(b) : c);
                var e, f;
                for (b.ticks = [], e = 0; e < d.length; ++e) {
                    var g = null,
                        h = d[e];
                    "object" == typeof h ? (f = +h[0], h.length > 1 && (g = h[1])) : f = +h, null == g && (g = b.tickFormatter(f, b)), isNaN(f) || b.ticks.push({
                        v: f,
                        label: g
                    })
                }
            }

            function F(a, b) {
                a.options.autoscaleMargin && b.length > 0 && (null == a.options.min && (a.min = Math.min(a.min, b[0].v)), null == a.options.max && b.length > 1 && (a.max = Math.max(a.max, b[b.length - 1].v)))
            }

            function G() {
                fb.clear(), h(pb.drawBackground, [ib]);
                var a = eb.grid;
                a.show && a.backgroundColor && I(), a.show && !a.aboveData && J();
                for (var b = 0; b < db.length; ++b) h(pb.drawSeries, [ib, db[b]]), L(db[b]);
                h(pb.draw, [ib]), a.show && a.aboveData && J(), fb.render(), X()
            }

            function H(a, b) {
                for (var c, d, e, f, g = n(), h = 0; h < g.length; ++h)
                    if (c = g[h], c.direction == b && (f = b + c.n + "axis", a[f] || 1 != c.n || (f = b + "axis"), a[f])) {
                        d = a[f].from, e = a[f].to;
                        break
                    }
                if (a[f] || (c = "x" == b ? kb[0] : lb[0], d = a[b + "1"], e = a[b + "2"]), null != d && null != e && d > e) {
                    var i = d;
                    d = e, e = i
                }
                return {
                    from: d,
                    to: e,
                    axis: c
                }
            }

            function I() {
                ib.save(), ib.translate(mb.left, mb.top), ib.fillStyle = cb(eb.grid.backgroundColor, ob, 0, "rgba(255, 255, 255, 0)"), ib.fillRect(0, 0, nb, ob), ib.restore()
            }

            function J() {
                var b, c, d, e;
                ib.save(), ib.translate(mb.left, mb.top);
                var f = eb.grid.markings;
                if (f)
                    for (a.isFunction(f) && (c = qb.getAxes(), c.xmin = c.xaxis.min, c.xmax = c.xaxis.max, c.ymin = c.yaxis.min, c.ymax = c.yaxis.max, f = f(c)), b = 0; b < f.length; ++b) {
                        var g = f[b],
                            h = H(g, "x"),
                            i = H(g, "y");
                        if (null == h.from && (h.from = h.axis.min), null == h.to && (h.to = h.axis.max), null == i.from && (i.from = i.axis.min), null == i.to && (i.to = i.axis.max), !(h.to < h.axis.min || h.from > h.axis.max || i.to < i.axis.min || i.from > i.axis.max)) {
                            h.from = Math.max(h.from, h.axis.min), h.to = Math.min(h.to, h.axis.max), i.from = Math.max(i.from, i.axis.min), i.to = Math.min(i.to, i.axis.max);
                            var j = h.from === h.to,
                                k = i.from === i.to;
                            if (!j || !k)
                                if (h.from = Math.floor(h.axis.p2c(h.from)), h.to = Math.floor(h.axis.p2c(h.to)), i.from = Math.floor(i.axis.p2c(i.from)), i.to = Math.floor(i.axis.p2c(i.to)), j || k) {
                                    var l = g.lineWidth || eb.grid.markingsLineWidth,
                                        m = l % 2 ? .5 : 0;
                                    ib.beginPath(), ib.strokeStyle = g.color || eb.grid.markingsColor, ib.lineWidth = l, j ? (ib.moveTo(h.to + m, i.from), ib.lineTo(h.to + m, i.to)) : (ib.moveTo(h.from, i.to + m), ib.lineTo(h.to, i.to + m)), ib.stroke()
                                } else ib.fillStyle = g.color || eb.grid.markingsColor, ib.fillRect(h.from, i.to, h.to - h.from, i.from - i.to)
                        }
                    }
                c = n(), d = eb.grid.borderWidth;
                for (var o = 0; o < c.length; ++o) {
                    var p, q, r, s, t = c[o],
                        u = t.box,
                        v = t.tickLength;
                    if (t.show && 0 != t.ticks.length) {
                        for (ib.lineWidth = 1, "x" == t.direction ? (p = 0, q = "full" == v ? "top" == t.position ? 0 : ob : u.top - mb.top + ("top" == t.position ? u.height : 0)) : (q = 0, p = "full" == v ? "left" == t.position ? 0 : nb : u.left - mb.left + ("left" == t.position ? u.width : 0)), t.innermost || (ib.strokeStyle = t.options.color, ib.beginPath(), r = s = 0, "x" == t.direction ? r = nb + 1 : s = ob + 1, 1 == ib.lineWidth && ("x" == t.direction ? q = Math.floor(q) + .5 : p = Math.floor(p) + .5), ib.moveTo(p, q), ib.lineTo(p + r, q + s), ib.stroke()), ib.strokeStyle = t.options.tickColor, ib.beginPath(), b = 0; b < t.ticks.length; ++b) {
                            var w = t.ticks[b].v;
                            r = s = 0, isNaN(w) || w < t.min || w > t.max || "full" == v && ("object" == typeof d && d[t.position] > 0 || d > 0) && (w == t.min || w == t.max) || ("x" == t.direction ? (p = t.p2c(w), s = "full" == v ? -ob : v, "top" == t.position && (s = -s)) : (q = t.p2c(w), r = "full" == v ? -nb : v, "left" == t.position && (r = -r)), 1 == ib.lineWidth && ("x" == t.direction ? p = Math.floor(p) + .5 : q = Math.floor(q) + .5), ib.moveTo(p, q), ib.lineTo(p + r, q + s))
                        }
                        ib.stroke()
                    }
                }
                d && (e = eb.grid.borderColor, "object" == typeof d || "object" == typeof e ? ("object" != typeof d && (d = {
                    top: d,
                    right: d,
                    bottom: d,
                    left: d
                }), "object" != typeof e && (e = {
                    top: e,
                    right: e,
                    bottom: e,
                    left: e
                }), d.top > 0 && (ib.strokeStyle = e.top, ib.lineWidth = d.top, ib.beginPath(), ib.moveTo(0 - d.left, 0 - d.top / 2), ib.lineTo(nb, 0 - d.top / 2), ib.stroke()), d.right > 0 && (ib.strokeStyle = e.right, ib.lineWidth = d.right, ib.beginPath(), ib.moveTo(nb + d.right / 2, 0 - d.top), ib.lineTo(nb + d.right / 2, ob), ib.stroke()), d.bottom > 0 && (ib.strokeStyle = e.bottom, ib.lineWidth = d.bottom, ib.beginPath(), ib.moveTo(nb + d.right, ob + d.bottom / 2), ib.lineTo(0, ob + d.bottom / 2), ib.stroke()), d.left > 0 && (ib.strokeStyle = e.left, ib.lineWidth = d.left, ib.beginPath(), ib.moveTo(0 - d.left / 2, ob + d.bottom), ib.lineTo(0 - d.left / 2, 0), ib.stroke())) : (ib.lineWidth = d, ib.strokeStyle = eb.grid.borderColor, ib.strokeRect(-d / 2, -d / 2, nb + d, ob + d))), ib.restore()
            }

            function K() {
                a.each(n(), function(a, b) {
                    var c, d, e, f, g, h = b.box,
                        i = b.direction + "Axis " + b.direction + b.n + "Axis",
                        j = "flot-" + b.direction + "-axis flot-" + b.direction + b.n + "-axis " + i,
                        k = b.options.font || "flot-tick-label tickLabel";
                    if (fb.removeText(j), b.show && 0 != b.ticks.length)
                        for (var l = 0; l < b.ticks.length; ++l) c = b.ticks[l], !c.label || c.v < b.min || c.v > b.max || ("x" == b.direction ? (f = "center", d = mb.left + b.p2c(c.v), "bottom" == b.position ? e = h.top + h.padding : (e = h.top + h.height - h.padding, g = "bottom")) : (g = "middle", e = mb.top + b.p2c(c.v), "left" == b.position ? (d = h.left + h.width - h.padding, f = "right") : d = h.left + h.padding), fb.addText(j, d, e, c.label, k, null, null, f, g))
                })
            }

            function L(a) {
                a.lines.show && M(a), a.bars.show && P(a), a.points.show && N(a)
            }

            function M(a) {
                function b(a, b, c, d, e) {
                    var f = a.points,
                        g = a.pointsize,
                        h = null,
                        i = null;
                    ib.beginPath();
                    for (var j = g; j < f.length; j += g) {
                        var k = f[j - g],
                            l = f[j - g + 1],
                            m = f[j],
                            n = f[j + 1];
                        if (null != k && null != m) {
                            if (n >= l && l < e.min) {
                                if (n < e.min) continue;
                                k = (e.min - l) / (n - l) * (m - k) + k, l = e.min
                            } else if (l >= n && n < e.min) {
                                if (l < e.min) continue;
                                m = (e.min - l) / (n - l) * (m - k) + k, n = e.min
                            }
                            if (l >= n && l > e.max) {
                                if (n > e.max) continue;
                                k = (e.max - l) / (n - l) * (m - k) + k, l = e.max
                            } else if (n >= l && n > e.max) {
                                if (l > e.max) continue;
                                m = (e.max - l) / (n - l) * (m - k) + k, n = e.max
                            }
                            if (m >= k && k < d.min) {
                                if (m < d.min) continue;
                                l = (d.min - k) / (m - k) * (n - l) + l, k = d.min
                            } else if (k >= m && m < d.min) {
                                if (k < d.min) continue;
                                n = (d.min - k) / (m - k) * (n - l) + l, m = d.min
                            }
                            if (k >= m && k > d.max) {
                                if (m > d.max) continue;
                                l = (d.max - k) / (m - k) * (n - l) + l, k = d.max
                            } else if (m >= k && m > d.max) {
                                if (k > d.max) continue;
                                n = (d.max - k) / (m - k) * (n - l) + l, m = d.max
                            }(k != h || l != i) && ib.moveTo(d.p2c(k) + b, e.p2c(l) + c), h = m, i = n, ib.lineTo(d.p2c(m) + b, e.p2c(n) + c)
                        }
                    }
                    ib.stroke()
                }

                function c(a, b, c) {
                    for (var d = a.points, e = a.pointsize, f = Math.min(Math.max(0, c.min), c.max), g = 0, h = !1, i = 1, j = 0, k = 0;;) {
                        if (e > 0 && g > d.length + e) break;
                        g += e;
                        var l = d[g - e],
                            m = d[g - e + i],
                            n = d[g],
                            o = d[g + i];
                        if (h) {
                            if (e > 0 && null != l && null == n) {
                                k = g, e = -e, i = 2;
                                continue
                            }
                            if (0 > e && g == j + e) {
                                ib.fill(), h = !1, e = -e, i = 1, g = j = k + e;
                                continue
                            }
                        }
                        if (null != l && null != n) {
                            if (n >= l && l < b.min) {
                                if (n < b.min) continue;
                                m = (b.min - l) / (n - l) * (o - m) + m, l = b.min
                            } else if (l >= n && n < b.min) {
                                if (l < b.min) continue;
                                o = (b.min - l) / (n - l) * (o - m) + m, n = b.min
                            }
                            if (l >= n && l > b.max) {
                                if (n > b.max) continue;
                                m = (b.max - l) / (n - l) * (o - m) + m, l = b.max
                            } else if (n >= l && n > b.max) {
                                if (l > b.max) continue;
                                o = (b.max - l) / (n - l) * (o - m) + m, n = b.max
                            }
                            if (h || (ib.beginPath(), ib.moveTo(b.p2c(l), c.p2c(f)), h = !0), m >= c.max && o >= c.max) ib.lineTo(b.p2c(l), c.p2c(c.max)), ib.lineTo(b.p2c(n), c.p2c(c.max));
                            else if (m <= c.min && o <= c.min) ib.lineTo(b.p2c(l), c.p2c(c.min)), ib.lineTo(b.p2c(n), c.p2c(c.min));
                            else {
                                var p = l,
                                    q = n;
                                o >= m && m < c.min && o >= c.min ? (l = (c.min - m) / (o - m) * (n - l) + l, m = c.min) : m >= o && o < c.min && m >= c.min && (n = (c.min - m) / (o - m) * (n - l) + l, o = c.min), m >= o && m > c.max && o <= c.max ? (l = (c.max - m) / (o - m) * (n - l) + l, m = c.max) : o >= m && o > c.max && m <= c.max && (n = (c.max - m) / (o - m) * (n - l) + l, o = c.max), l != p && ib.lineTo(b.p2c(p), c.p2c(m)), ib.lineTo(b.p2c(l), c.p2c(m)), ib.lineTo(b.p2c(n), c.p2c(o)), n != q && (ib.lineTo(b.p2c(n), c.p2c(o)), ib.lineTo(b.p2c(q), c.p2c(o)))
                            }
                        }
                    }
                }
                ib.save(), ib.translate(mb.left, mb.top), ib.lineJoin = "round";
                var d = a.lines.lineWidth,
                    e = a.shadowSize;
                if (d > 0 && e > 0) {
                    ib.lineWidth = e, ib.strokeStyle = "rgba(0,0,0,0.1)";
                    var f = Math.PI / 18;
                    b(a.datapoints, Math.sin(f) * (d / 2 + e / 2), Math.cos(f) * (d / 2 + e / 2), a.xaxis, a.yaxis), ib.lineWidth = e / 2, b(a.datapoints, Math.sin(f) * (d / 2 + e / 4), Math.cos(f) * (d / 2 + e / 4), a.xaxis, a.yaxis)
                }
                ib.lineWidth = d, ib.strokeStyle = a.color;
                var g = Q(a.lines, a.color, 0, ob);
                g && (ib.fillStyle = g, c(a.datapoints, a.xaxis, a.yaxis)), d > 0 && b(a.datapoints, 0, 0, a.xaxis, a.yaxis), ib.restore()
            }

            function N(a) {
                function b(a, b, c, d, e, f, g, h) {
                    for (var i = a.points, j = a.pointsize, k = 0; k < i.length; k += j) {
                        var l = i[k],
                            m = i[k + 1];
                        null == l || l < f.min || l > f.max || m < g.min || m > g.max || (ib.beginPath(), l = f.p2c(l), m = g.p2c(m) + d, "circle" == h ? ib.arc(l, m, b, 0, e ? Math.PI : 2 * Math.PI, !1) : h(ib, l, m, b, e), ib.closePath(), c && (ib.fillStyle = c, ib.fill()), ib.stroke())
                    }
                }
                ib.save(), ib.translate(mb.left, mb.top);
                var c = a.points.lineWidth,
                    d = a.shadowSize,
                    e = a.points.radius,
                    f = a.points.symbol;
                if (0 == c && (c = 1e-4), c > 0 && d > 0) {
                    var g = d / 2;
                    ib.lineWidth = g, ib.strokeStyle = "rgba(0,0,0,0.1)", b(a.datapoints, e, null, g + g / 2, !0, a.xaxis, a.yaxis, f), ib.strokeStyle = "rgba(0,0,0,0.2)", b(a.datapoints, e, null, g / 2, !0, a.xaxis, a.yaxis, f)
                }
                ib.lineWidth = c, ib.strokeStyle = a.points.borderColor || a.color, b(a.datapoints, e, Q(a.points, a.color), 0, !1, a.xaxis, a.yaxis, f), ib.restore()
            }

            function O(a, b, c, d, e, f, g, h, i, j, k) {
                var l, m, n, o, p, q, r, s, t;
                j ? (s = q = r = !0, p = !1, l = c, m = a, o = b + d, n = b + e, l > m && (t = m, m = l, l = t, p = !0, q = !1)) : (p = q = r = !0, s = !1, l = a + d, m = a + e, n = c, o = b, n > o && (t = o, o = n, n = t, s = !0, r = !1)), m < g.min || l > g.max || o < h.min || n > h.max || (l < g.min && (l = g.min, p = !1), m > g.max && (m = g.max, q = !1), n < h.min && (n = h.min, s = !1), o > h.max && (o = h.max, r = !1), l = g.p2c(l), n = h.p2c(n), m = g.p2c(m), o = h.p2c(o), f && (i.fillStyle = f(n, o), i.fillRect(l, o, m - l, n - o)), k > 0 && (p || q || r || s) && (i.beginPath(), i.moveTo(l, n), p ? i.lineTo(l, o) : i.moveTo(l, o), r ? i.lineTo(m, o) : i.moveTo(m, o), q ? i.lineTo(m, n) : i.moveTo(m, n), s ? i.lineTo(l, n) : i.moveTo(l, n), i.stroke()))
            }

            function P(a) {
                function b(b, c, d, e, f, g) {
                    for (var h = b.points, i = b.pointsize, j = 0; j < h.length; j += i) null != h[j] && O(h[j], h[j + 1], h[j + 2], c, d, e, f, g, ib, a.bars.horizontal, a.bars.lineWidth)
                }
                ib.save(), ib.translate(mb.left, mb.top), ib.lineWidth = a.bars.lineWidth, ib.strokeStyle = a.color;
                var c;
                switch (a.bars.align) {
                    case "left":
                        c = 0;
                        break;
                    case "right":
                        c = -a.bars.barWidth;
                        break;
                    default:
                        c = -a.bars.barWidth / 2
                }
                var d = a.bars.fill ? function(b, c) {
                    return Q(a.bars, a.color, b, c)
                } : null;
                b(a.datapoints, c, c + a.bars.barWidth, d, a.xaxis, a.yaxis), ib.restore()
            }

            function Q(b, c, d, e) {
                var f = b.fill;
                if (!f) return null;
                if (b.fillColor) return cb(b.fillColor, d, e, c);
                var g = a.color.parse(c);
                return g.a = "number" == typeof f ? f : .4, g.normalize(), g.toString()
            }

            function R() {
                if (null != eb.legend.container ? a(eb.legend.container).html("") : c.find(".legend").remove(), eb.legend.show) {
                    for (var b, d, e = [], f = [], g = !1, h = eb.legend.labelFormatter, i = 0; i < db.length; ++i) b = db[i], b.label && (d = h ? h(b.label, b) : b.label, d && f.push({
                        label: d,
                        color: b.color
                    }));
                    if (eb.legend.sorted)
                        if (a.isFunction(eb.legend.sorted)) f.sort(eb.legend.sorted);
                        else if ("reverse" == eb.legend.sorted) f.reverse();
                    else {
                        var j = "descending" != eb.legend.sorted;
                        f.sort(function(a, b) {
                            return a.label == b.label ? 0 : a.label < b.label != j ? 1 : -1
                        })
                    }
                    for (var i = 0; i < f.length; ++i) {
                        var k = f[i];
                        if (i % eb.legend.noColumns == 0 && (g && e.push("</tr>"), e.push("<tr>"), g = !0), eb.legend.height) var l = eb.legend.height;
                        else var l = 5;
                        e.push('<td class="legendColorBox"><div style="border:1px solid ' + eb.legend.labelBoxBorderColor + ';padding:1px"><div style="width:' + eb.legend.width + "px;height:0;border:" + l + "px solid " + k.color + ';overflow:hidden"></div></div></td><td class="legendLabel">' + k.label + "</td>")
                    }
                    if (g && e.push("</tr>"), 0 != e.length) {
                        var m = '<table style="font-size:smaller;color:' + eb.grid.color + '">' + e.join("") + "</table>";
                        if (null != eb.legend.container) a(eb.legend.container).html(m);
                        else {
                            var n = "",
                                o = eb.legend.position,
                                p = eb.legend.margin;
                            null == p[0] && (p = [p, p]), "n" == o.charAt(0) ? n += "top:" + (p[1] + mb.top) + "px;" : "s" == o.charAt(0) && (n += "bottom:" + (p[1] + mb.bottom) + "px;"), "e" == o.charAt(1) ? n += "right:" + (p[0] + mb.right) + "px;" : "w" == o.charAt(1) && (n += "left:" + (p[0] + mb.left) + "px;");
                            var q = a('<div class="legend">' + m.replace('style="', 'style="position:absolute;' + n + ";") + "</div>").appendTo(c);
                            if (0 != eb.legend.backgroundOpacity) {
                                var r = eb.legend.backgroundColor;
                                null == r && (r = eb.grid.backgroundColor, r = r && "string" == typeof r ? a.color.parse(r) : a.color.extract(q, "background-color"), r.a = 1, r = r.toString());
                                var s = q.children();
                                a('<div style="position:absolute;width:' + s.width() + "px;height:" + s.height() + "px;" + n + "background-color:" + r + ';"> </div>').prependTo(q).css("opacity", eb.legend.backgroundOpacity)
                            }
                        }
                    }
                }
            }

            function S(a, b, c) {
                var d, e, f, g = eb.grid.mouseActiveRadius,
                    h = g * g + 1,
                    i = null;
                for (d = db.length - 1; d >= 0; --d)
                    if (c(db[d])) {
                        var j = db[d],
                            k = j.xaxis,
                            l = j.yaxis,
                            m = j.datapoints.points,
                            n = k.c2p(a),
                            o = l.c2p(b),
                            p = g / k.scale,
                            q = g / l.scale;
                        if (f = j.datapoints.pointsize, k.options.inverseTransform && (p = Number.MAX_VALUE), l.options.inverseTransform && (q = Number.MAX_VALUE), j.lines.show || j.points.show)
                            for (e = 0; e < m.length; e += f) {
                                var r = m[e],
                                    s = m[e + 1];
                                if (null != r && !(r - n > p || -p > r - n || s - o > q || -q > s - o)) {
                                    var t = Math.abs(k.p2c(r) - a),
                                        u = Math.abs(l.p2c(s) - b),
                                        v = t * t + u * u;
                                    h > v && (h = v, i = [d, e / f])
                                }
                            }
                        if (j.bars.show && !i) {
                            var w, x;
                            switch (j.bars.align) {
                                case "left":
                                    w = 0;
                                    break;
                                case "right":
                                    w = -j.bars.barWidth;
                                    break;
                                default:
                                    w = -j.bars.barWidth / 2
                            }
                            for (x = w + j.bars.barWidth, e = 0; e < m.length; e += f) {
                                var r = m[e],
                                    s = m[e + 1],
                                    y = m[e + 2];
                                null != r && (db[d].bars.horizontal ? n <= Math.max(y, r) && n >= Math.min(y, r) && o >= s + w && s + x >= o : n >= r + w && r + x >= n && o >= Math.min(y, s) && o <= Math.max(y, s)) && (i = [d, e / f])
                            }
                        }
                    }
                return i ? (d = i[0], e = i[1], f = db[d].datapoints.pointsize, {
                    datapoint: db[d].datapoints.points.slice(e * f, (e + 1) * f),
                    dataIndex: e,
                    series: db[d],
                    seriesIndex: d
                }) : null
            }

            function T(a) {
                eb.grid.hoverable && W("plothover", a, function(a) {
                    return 0 != a.hoverable
                })
            }

            function U(a) {
                eb.grid.hoverable && W("plothover", a, function() {
                    return !1
                })
            }

            function V(a) {
                W("plotclick", a, function(a) {
                    return 0 != a.clickable
                })
            }

            function W(a, b, d) {
                var e = hb.offset(),
                    f = b.pageX - e.left - mb.left,
                    g = b.pageY - e.top - mb.top,
                    h = o({
                        left: f,
                        top: g
                    });
                h.pageX = b.pageX, h.pageY = b.pageY;
                var i = S(f, g, d);
                if (i && (i.pageX = parseInt(i.series.xaxis.p2c(i.datapoint[0]) + e.left + mb.left, 10), i.pageY = parseInt(i.series.yaxis.p2c(i.datapoint[1]) + e.top + mb.top, 10)), eb.grid.autoHighlight) {
                    for (var j = 0; j < rb.length; ++j) {
                        var k = rb[j];
                        k.auto != a || i && k.series == i.series && k.point[0] == i.datapoint[0] && k.point[1] == i.datapoint[1] || $(k.series, k.point)
                    }
                    i && Z(i.series, i.datapoint, a)
                }
                c.trigger(a, [h, i])
            }

            function X() {
                var a = eb.interaction.redrawOverlayInterval;
                return -1 == a ? void Y() : void(sb || (sb = setTimeout(Y, a)))
            }

            function Y() {
                sb = null, jb.save(), gb.clear(), jb.translate(mb.left, mb.top);
                var a, b;
                for (a = 0; a < rb.length; ++a) b = rb[a], b.series.bars.show ? bb(b.series, b.point) : ab(b.series, b.point);
                jb.restore(), h(pb.drawOverlay, [jb])
            }

            function Z(a, b, c) {
                if ("number" == typeof a && (a = db[a]), "number" == typeof b) {
                    var d = a.datapoints.pointsize;
                    b = a.datapoints.points.slice(d * b, d * (b + 1))
                }
                var e = _(a, b); - 1 == e ? (rb.push({
                    series: a,
                    point: b,
                    auto: c
                }), X()) : c || (rb[e].auto = !1)
            }

            function $(a, b) {
                if (null == a && null == b) return rb = [], void X();
                if ("number" == typeof a && (a = db[a]), "number" == typeof b) {
                    var c = a.datapoints.pointsize;
                    b = a.datapoints.points.slice(c * b, c * (b + 1))
                }
                var d = _(a, b); - 1 != d && (rb.splice(d, 1), X())
            }

            function _(a, b) {
                for (var c = 0; c < rb.length; ++c) {
                    var d = rb[c];
                    if (d.series == a && d.point[0] == b[0] && d.point[1] == b[1]) return c
                }
                return -1
            }

            function ab(b, c) {
                var d = c[0],
                    e = c[1],
                    f = b.xaxis,
                    g = b.yaxis,
                    h = "string" == typeof b.highlightColor ? b.highlightColor : a.color.parse(b.color).scale("a", .5).toString();
                if (!(d < f.min || d > f.max || e < g.min || e > g.max)) {
                    var i = b.points.radius + b.points.lineWidth / 2;
                    jb.lineWidth = i, jb.strokeStyle = h;
                    var j = 1.5 * i;
                    d = f.p2c(d), e = g.p2c(e), jb.beginPath(), "circle" == b.points.symbol ? jb.arc(d, e, j, 0, 2 * Math.PI, !1) : b.points.symbol(jb, d, e, j, !1), jb.closePath(), jb.stroke()
                }
            }

            function bb(b, c) {
                var d, e = "string" == typeof b.highlightColor ? b.highlightColor : a.color.parse(b.color).scale("a", .5).toString(),
                    f = e;
                switch (b.bars.align) {
                    case "left":
                        d = 0;
                        break;
                    case "right":
                        d = -b.bars.barWidth;
                        break;
                    default:
                        d = -b.bars.barWidth / 2
                }
                jb.lineWidth = b.bars.lineWidth, jb.strokeStyle = e, O(c[0], c[1], c[2] || 0, d, d + b.bars.barWidth, function() {
                    return f
                }, b.xaxis, b.yaxis, jb, b.bars.horizontal, b.bars.lineWidth)
            }

            function cb(b, c, d, e) {
                if ("string" == typeof b) return b;
                for (var f = ib.createLinearGradient(0, d, 0, c), g = 0, h = b.colors.length; h > g; ++g) {
                    var i = b.colors[g];
                    if ("string" != typeof i) {
                        var j = a.color.parse(e);
                        null != i.brightness && (j = j.scale("rgb", i.brightness)), null != i.opacity && (j.a *= i.opacity), i = j.toString()
                    }
                    f.addColorStop(g / (h - 1), i)
                }
                return f
            }
            var db = [],
                eb = {
                    colors: ["#edc240", "#afd8f8", "#cb4b4b", "#4da74d", "#9440ed"],
                    legend: {
                        show: !0,
                        noColumns: 1,
                        labelFormatter: null,
                        labelBoxBorderColor: "#ccc",
                        container: null,
                        position: "ne",
                        margin: 5,
                        backgroundColor: null,
                        backgroundOpacity: .85,
                        sorted: null
                    },
                    xaxis: {
                        show: null,
                        position: "bottom",
                        mode: null,
                        font: null,
                        color: null,
                        tickColor: null,
                        transform: null,
                        inverseTransform: null,
                        min: null,
                        max: null,
                        autoscaleMargin: null,
                        ticks: null,
                        tickFormatter: null,
                        labelWidth: null,
                        labelHeight: null,
                        reserveSpace: null,
                        tickLength: null,
                        alignTicksWithAxis: null,
                        tickDecimals: null,
                        tickSize: null,
                        minTickSize: null
                    },
                    yaxis: {
                        autoscaleMargin: .02,
                        position: "left"
                    },
                    xaxes: [],
                    yaxes: [],
                    series: {
                        points: {
                            show: !1,
                            radius: 3,
                            lineWidth: 2,
                            fill: !0,
                            fillColor: "#ffffff",
                            symbol: "circle"
                        },
                        lines: {
                            lineWidth: 2,
                            fill: !1,
                            fillColor: null,
                            steps: !1
                        },
                        bars: {
                            show: !1,
                            lineWidth: 2,
                            barWidth: 1,
                            fill: !0,
                            fillColor: null,
                            align: "left",
                            horizontal: !1,
                            zero: !0
                        },
                        shadowSize: 3,
                        highlightColor: null
                    },
                    grid: {
                        show: !0,
                        aboveData: !1,
                        color: "#545454",
                        backgroundColor: null,
                        borderColor: null,
                        tickColor: null,
                        margin: 0,
                        labelMargin: 5,
                        axisMargin: 8,
                        borderWidth: 2,
                        minBorderMargin: null,
                        markings: null,
                        markingsColor: "#f4f4f4",
                        markingsLineWidth: 2,
                        clickable: !1,
                        hoverable: !1,
                        autoHighlight: !0,
                        mouseActiveRadius: 10
                    },
                    interaction: {
                        redrawOverlayInterval: 1e3 / 60
                    },
                    hooks: {}
                },
                fb = null,
                gb = null,
                hb = null,
                ib = null,
                jb = null,
                kb = [],
                lb = [],
                mb = {
                    left: 0,
                    right: 0,
                    top: 0,
                    bottom: 0
                },
                nb = 0,
                ob = 0,
                pb = {
                    processOptions: [],
                    processRawData: [],
                    processDatapoints: [],
                    processOffset: [],
                    drawBackground: [],
                    drawSeries: [],
                    draw: [],
                    bindEvents: [],
                    drawOverlay: [],
                    shutdown: []
                },
                qb = this;
            qb.setData = k, qb.setupGrid = B, qb.draw = G, qb.getPlaceholder = function() {
                return c
            }, qb.getCanvas = function() {
                return fb.element
            }, qb.getPlotOffset = function() {
                return mb
            }, qb.width = function() {
                return nb
            }, qb.height = function() {
                return ob
            }, qb.offset = function() {
                var a = hb.offset();
                return a.left += mb.left, a.top += mb.top, a
            }, qb.getData = function() {
                return db
            }, qb.getAxes = function() {
                var b = {};
                return a.each(kb.concat(lb), function(a, c) {
                    c && (b[c.direction + (1 != c.n ? c.n : "") + "axis"] = c)
                }), b
            }, qb.getXAxes = function() {
                return kb
            }, qb.getYAxes = function() {
                return lb
            }, qb.c2p = o, qb.p2c = p, qb.getOptions = function() {
                return eb
            }, qb.highlight = Z, qb.unhighlight = $, qb.triggerRedrawOverlay = X, qb.pointOffset = function(a) {
                return {
                    left: parseInt(kb[m(a, "x") - 1].p2c(+a.x) + mb.left, 10),
                    top: parseInt(lb[m(a, "y") - 1].p2c(+a.y) + mb.top, 10)
                }
            }, qb.shutdown = v, qb.destroy = function() {
                v(), c.removeData("plot").empty(), db = [], eb = null, fb = null, gb = null, hb = null, ib = null, jb = null, kb = [], lb = [], pb = null, rb = [], qb = null
            }, qb.resize = function() {
                var a = c.width(),
                    b = c.height();
                fb.resize(a, b), gb.resize(a, b)
            }, qb.hooks = pb, i(qb), j(f), t(), k(e), B(), G(), u();
            var rb = [],
                sb = null
        }

        function d(a, b) {
            return b * Math.floor(a / b)
        }
        var e = Object.prototype.hasOwnProperty;
        a.fn.detach || (a.fn.detach = function() {
            return this.each(function() {
                this.parentNode && this.parentNode.removeChild(this)
            })
        }), b.prototype.resize = function(a, b) {
            if (0 >= a || 0 >= b) throw new Error("Invalid dimensions for plot, width = " + a + ", height = " + b);
            var c = this.element,
                d = this.context,
                e = this.pixelRatio;
            this.width != a && (c.width = a * e, c.style.width = a + "px", this.width = a), this.height != b && (c.height = b * e, c.style.height = b + "px", this.height = b), d.restore(), d.save(), d.scale(e, e)
        }, b.prototype.clear = function() {
            this.context.clearRect(0, 0, this.width, this.height)
        }, b.prototype.render = function() {
            var a = this._textCache;
            for (var b in a)
                if (e.call(a, b)) {
                    var c = this.getTextLayer(b),
                        d = a[b];
                    c.hide();
                    for (var f in d)
                        if (e.call(d, f)) {
                            var g = d[f];
                            for (var h in g)
                                if (e.call(g, h)) {
                                    for (var i, j = g[h].positions, k = 0; i = j[k]; k++) i.active ? i.rendered || (c.append(i.element), i.rendered = !0) : (j.splice(k--, 1), i.rendered && i.element.detach());
                                    0 == j.length && delete g[h]
                                }
                        }
                    c.show()
                }
        }, b.prototype.getTextLayer = function(b) {
            var c = this.text[b];
            return null == c && (null == this.textContainer && (this.textContainer = a("<div class='flot-text'></div>").css({
                position: "absolute",
                top: 0,
                left: 0,
                bottom: 0,
                right: 0,
                "font-size": "smaller",
                color: "#545454"
            }).insertAfter(this.element)), c = this.text[b] = a("<div></div>").addClass(b).css({
                position: "absolute",
                top: 0,
                left: 0,
                bottom: 0,
                right: 0
            }).appendTo(this.textContainer)), c
        }, b.prototype.getTextInfo = function(b, c, d, e, f) {
            var g, h, i, j;
            if (c = "" + c, g = "object" == typeof d ? d.style + " " + d.variant + " " + d.weight + " " + d.size + "px/" + d.lineHeight + "px " + d.family : d, h = this._textCache[b], null == h && (h = this._textCache[b] = {}), i = h[g], null == i && (i = h[g] = {}), j = i[c], null == j) {
                var k = a("<div></div>").html(c).css({
                    position: "absolute",
                    "max-width": f,
                    top: -9999
                }).appendTo(this.getTextLayer(b));
                "object" == typeof d ? k.css({
                    font: g,
                    color: d.color
                }) : "string" == typeof d && k.addClass(d), j = i[c] = {
                    width: k.outerWidth(!0),
                    height: k.outerHeight(!0),
                    element: k,
                    positions: []
                }, k.detach()
            }
            return j
        }, b.prototype.addText = function(a, b, c, d, e, f, g, h, i) {
            var j = this.getTextInfo(a, d, e, f, g),
                k = j.positions;
            "center" == h ? b -= j.width / 2 : "right" == h && (b -= j.width), "middle" == i ? c -= j.height / 2 : "bottom" == i && (c -= j.height);
            for (var l, m = 0; l = k[m]; m++)
                if (l.x == b && l.y == c) return void(l.active = !0);
            l = {
                active: !0,
                rendered: !1,
                element: k.length ? j.element.clone() : j.element,
                x: b,
                y: c
            }, k.push(l), l.element.css({
                top: Math.round(c),
                left: Math.round(b),
                "text-align": h
            })
        }, b.prototype.removeText = function(a, b, c, d, f, g) {
            if (null == d) {
                var h = this._textCache[a];
                if (null != h)
                    for (var i in h)
                        if (e.call(h, i)) {
                            var j = h[i];
                            for (var k in j)
                                if (e.call(j, k))
                                    for (var l, m = j[k].positions, n = 0; l = m[n]; n++) l.active = !1
                        }
            } else
                for (var l, m = this.getTextInfo(a, d, f, g).positions, n = 0; l = m[n]; n++) l.x == b && l.y == c && (l.active = !1)
        }, a.plot = function(b, d, e) {
            var f = new c(a(b), d, e, a.plot.plugins);
            return f
        }, a.plot.version = "0.8.3", a.plot.plugins = [], a.fn.plot = function(b, c) {
            return this.each(function() {
                a.plot(this, b, c)
            })
        }
    }(jQuery), function(a) {
        function b(b) {
            function e(b) {
                x || (x = !0, r = b.getCanvas(), s = a(r).parent(), t = b.getOptions(), b.setData(f(b.getData())))
            }

            function f(b) {
                for (var c = 0, d = 0, e = 0, f = t.series.pie.combine.color, g = [], h = 0; h < b.length; ++h) {
                    var i = b[h].data;
                    a.isArray(i) && 1 == i.length && (i = i[0]), a.isArray(i) ? i[1] = !isNaN(parseFloat(i[1])) && isFinite(i[1]) ? +i[1] : 0 : i = !isNaN(parseFloat(i)) && isFinite(i) ? [1, +i] : [1, 0], b[h].data = [i]
                }
                for (var h = 0; h < b.length; ++h) c += b[h].data[0][1];
                for (var h = 0; h < b.length; ++h) {
                    var i = b[h].data[0][1];
                    i / c <= t.series.pie.combine.threshold && (d += i, e++, f || (f = b[h].color))
                }
                for (var h = 0; h < b.length; ++h) {
                    var i = b[h].data[0][1];
                    (2 > e || i / c > t.series.pie.combine.threshold) && g.push(a.extend(b[h], {
                        data: [
                            [1, i]
                        ],
                        color: b[h].color,
                        label: b[h].label,
                        angle: i * Math.PI * 2 / c,
                        percent: i / (c / 100)
                    }))
                }
                return e > 1 && g.push({
                    data: [
                        [1, d]
                    ],
                    color: f,
                    label: t.series.pie.combine.label,
                    angle: d * Math.PI * 2 / c,
                    percent: d / (c / 100)
                }), g
            }

            function g(b, e) {
                function f() {
                    y.clearRect(0, 0, j, k), s.children().filter(".pieLabel, .pieLabelBackground").remove()
                }

                function g() {
                    var a = t.series.pie.shadow.left,
                        b = t.series.pie.shadow.top,
                        c = 10,
                        d = t.series.pie.shadow.alpha,
                        e = t.series.pie.radius > 1 ? t.series.pie.radius : u * t.series.pie.radius;
                    if (!(e >= j / 2 - a || e * t.series.pie.tilt >= k / 2 - b || c >= e)) {
                        y.save(), y.translate(a, b), y.globalAlpha = d, y.fillStyle = "#000", y.translate(v, w), y.scale(1, t.series.pie.tilt);
                        for (var f = 1; c >= f; f++) y.beginPath(), y.arc(0, 0, e, 0, 2 * Math.PI, !1), y.fill(), e -= f;
                        y.restore()
                    }
                }

                function i() {
                    function b(a, b, c) {
                        0 >= a || isNaN(a) || (c ? y.fillStyle = b : (y.strokeStyle = b, y.lineJoin = "round"), y.beginPath(), Math.abs(a - 2 * Math.PI) > 1e-9 && y.moveTo(0, 0), y.arc(0, 0, e, f, f + a / 2, !1), y.arc(0, 0, e, f + a / 2, f + a, !1), y.closePath(), f += a, c ? y.fill() : y.stroke())
                    }

                    function c() {
                        function b(b, c, d) {
                            if (0 == b.data[0][1]) return !0;
                            var f, g = t.legend.labelFormatter,
                                h = t.series.pie.label.formatter;
                            f = g ? g(b.label, b) : b.label, h && (f = h(f, b));
                            var i = (c + b.angle + c) / 2,
                                l = v + Math.round(Math.cos(i) * e),
                                m = w + Math.round(Math.sin(i) * e) * t.series.pie.tilt,
                                n = "<span class='pieLabel' id='pieLabel" + d + "' style='position:absolute;top:" + m + "px;left:" + l + "px;'>" + f + "</span>";
                            s.append(n);
                            var o = s.children("#pieLabel" + d),
                                p = m - o.height() / 2,
                                q = l - o.width() / 2;
                            if (o.css("top", p), o.css("left", q), 0 - p > 0 || 0 - q > 0 || k - (p + o.height()) < 0 || j - (q + o.width()) < 0) return !1;
                            if (0 != t.series.pie.label.background.opacity) {
                                var r = t.series.pie.label.background.color;
                                null == r && (r = b.color);
                                var u = "top:" + p + "px;left:" + q + "px;";
                                a("<div class='pieLabelBackground' style='position:absolute;width:" + o.width() + "px;height:" + o.height() + "px;" + u + "background-color:" + r + ";'></div>").css("opacity", t.series.pie.label.background.opacity).insertBefore(o)
                            }
                            return !0
                        }
                        for (var c = d, e = t.series.pie.label.radius > 1 ? t.series.pie.label.radius : u * t.series.pie.label.radius, f = 0; f < m.length; ++f) {
                            if (m[f].percent >= 100 * t.series.pie.label.threshold && !b(m[f], c, f)) return !1;
                            c += m[f].angle
                        }
                        return !0
                    }
                    var d = Math.PI * t.series.pie.startAngle,
                        e = t.series.pie.radius > 1 ? t.series.pie.radius : u * t.series.pie.radius;
                    y.save(), y.translate(v, w), y.scale(1, t.series.pie.tilt), y.save();
                    for (var f = d, g = 0; g < m.length; ++g) m[g].startAngle = f, b(m[g].angle, m[g].color, !0);
                    if (y.restore(), t.series.pie.stroke.width > 0) {
                        y.save(), y.lineWidth = t.series.pie.stroke.width, f = d;
                        for (var g = 0; g < m.length; ++g) b(m[g].angle, t.series.pie.stroke.color, !1);
                        y.restore()
                    }
                    return h(y), y.restore(), t.series.pie.label.show ? c() : !0
                }
                if (s) {
                    var j = b.getPlaceholder().width(),
                        k = b.getPlaceholder().height(),
                        l = s.children().filter(".legend").children().width() || 0;
                    y = e, x = !1, u = Math.min(j, k / t.series.pie.tilt) / 2, w = k / 2 + t.series.pie.offset.top, v = j / 2, "auto" == t.series.pie.offset.left ? (t.legend.position.match("w") ? v += l / 2 : v -= l / 2, u > v ? v = u : v > j - u && (v = j - u)) : v += t.series.pie.offset.left;
                    var m = b.getData(),
                        n = 0;
                    do n > 0 && (u *= d), n += 1, f(), t.series.pie.tilt <= .8 && g(); while (!i() && c > n);
                    n >= c && (f(), s.prepend("<div class='error'>Could not draw pie with labels contained inside canvas</div>")), b.setSeries && b.insertLegend && (b.setSeries(m), b.insertLegend())
                }
            }

            function h(a) {
                if (t.series.pie.innerRadius > 0) {
                    a.save();
                    var b = t.series.pie.innerRadius > 1 ? t.series.pie.innerRadius : u * t.series.pie.innerRadius;
                    a.globalCompositeOperation = "destination-out", a.beginPath(), a.fillStyle = t.series.pie.stroke.color, a.arc(0, 0, b, 0, 2 * Math.PI, !1), a.fill(), a.closePath(), a.restore(), a.save(), a.beginPath(), a.strokeStyle = t.series.pie.stroke.color, a.arc(0, 0, b, 0, 2 * Math.PI, !1), a.stroke(), a.closePath(), a.restore()
                }
            }

            function i(a, b) {
                for (var c = !1, d = -1, e = a.length, f = e - 1; ++d < e; f = d)(a[d][1] <= b[1] && b[1] < a[f][1] || a[f][1] <= b[1] && b[1] < a[d][1]) && b[0] < (a[f][0] - a[d][0]) * (b[1] - a[d][1]) / (a[f][1] - a[d][1]) + a[d][0] && (c = !c);
                return c
            }

            function j(a, c) {
                for (var d, e, f = b.getData(), g = b.getOptions(), h = g.series.pie.radius > 1 ? g.series.pie.radius : u * g.series.pie.radius, j = 0; j < f.length; ++j) {
                    var k = f[j];
                    if (k.pie.show) {
                        if (y.save(), y.beginPath(), y.moveTo(0, 0), y.arc(0, 0, h, k.startAngle, k.startAngle + k.angle / 2, !1), y.arc(0, 0, h, k.startAngle + k.angle / 2, k.startAngle + k.angle, !1), y.closePath(), d = a - v, e = c - w, y.isPointInPath) {
                            if (y.isPointInPath(a - v, c - w)) return y.restore(), {
                                datapoint: [k.percent, k.data],
                                dataIndex: 0,
                                series: k,
                                seriesIndex: j
                            }
                        } else {
                            var l = h * Math.cos(k.startAngle),
                                m = h * Math.sin(k.startAngle),
                                n = h * Math.cos(k.startAngle + k.angle / 4),
                                o = h * Math.sin(k.startAngle + k.angle / 4),
                                p = h * Math.cos(k.startAngle + k.angle / 2),
                                q = h * Math.sin(k.startAngle + k.angle / 2),
                                r = h * Math.cos(k.startAngle + k.angle / 1.5),
                                s = h * Math.sin(k.startAngle + k.angle / 1.5),
                                t = h * Math.cos(k.startAngle + k.angle),
                                x = h * Math.sin(k.startAngle + k.angle),
                                z = [
                                    [0, 0],
                                    [l, m],
                                    [n, o],
                                    [p, q],
                                    [r, s],
                                    [t, x]
                                ],
                                A = [d, e];
                            if (i(z, A)) return y.restore(), {
                                datapoint: [k.percent, k.data],
                                dataIndex: 0,
                                series: k,
                                seriesIndex: j
                            }
                        }
                        y.restore()
                    }
                }
                return null
            }

            function k(a) {
                m("plothover", a)
            }

            function l(a) {
                m("plotclick", a)
            }

            function m(a, c) {
                var d = b.offset(),
                    e = parseInt(c.pageX - d.left),
                    f = parseInt(c.pageY - d.top),
                    g = j(e, f);
                if (t.grid.autoHighlight)
                    for (var h = 0; h < z.length; ++h) {
                        var i = z[h];
                        i.auto != a || g && i.series == g.series || o(i.series)
                    }
                g && n(g.series, a);
                var k = {
                    pageX: c.pageX,
                    pageY: c.pageY
                };
                s.trigger(a, [k, g])
            }

            function n(a, c) {
                var d = p(a); - 1 == d ? (z.push({
                    series: a,
                    auto: c
                }), b.triggerRedrawOverlay()) : c || (z[d].auto = !1)
            }

            function o(a) {
                null == a && (z = [], b.triggerRedrawOverlay());
                var c = p(a); - 1 != c && (z.splice(c, 1), b.triggerRedrawOverlay())
            }

            function p(a) {
                for (var b = 0; b < z.length; ++b) {
                    var c = z[b];
                    if (c.series == a) return b
                }
                return -1
            }

            function q(a, b) {
                function c(a) {
                    a.angle <= 0 || isNaN(a.angle) || (b.fillStyle = "rgba(255, 255, 255, " + d.series.pie.highlight.opacity + ")", b.beginPath(), Math.abs(a.angle - 2 * Math.PI) > 1e-9 && b.moveTo(0, 0), b.arc(0, 0, e, a.startAngle, a.startAngle + a.angle / 2, !1), b.arc(0, 0, e, a.startAngle + a.angle / 2, a.startAngle + a.angle, !1), b.closePath(), b.fill())
                }
                var d = a.getOptions(),
                    e = d.series.pie.radius > 1 ? d.series.pie.radius : u * d.series.pie.radius;
                b.save(), b.translate(v, w), b.scale(1, d.series.pie.tilt);
                for (var f = 0; f < z.length; ++f) c(z[f].series);
                h(b), b.restore()
            }
            var r = null,
                s = null,
                t = null,
                u = null,
                v = null,
                w = null,
                x = !1,
                y = null,
                z = [];
            b.hooks.processOptions.push(function(a, b) {
                b.series.pie.show && (b.grid.show = !1, "auto" == b.series.pie.label.show && (b.series.pie.label.show = b.legend.show ? !1 : !0), "auto" == b.series.pie.radius && (b.series.pie.radius = b.series.pie.label.show ? .75 : 1), b.series.pie.tilt > 1 ? b.series.pie.tilt = 1 : b.series.pie.tilt < 0 && (b.series.pie.tilt = 0))
            }), b.hooks.bindEvents.push(function(a, b) {
                var c = a.getOptions();
                c.series.pie.show && (c.grid.hoverable && b.unbind("mousemove").mousemove(k), c.grid.clickable && b.unbind("click").click(l))
            }), b.hooks.processDatapoints.push(function(a, b, c, d) {
                var f = a.getOptions();
                f.series.pie.show && e(a, b, c, d)
            }), b.hooks.drawOverlay.push(function(a, b) {
                var c = a.getOptions();
                c.series.pie.show && q(a, b)
            }), b.hooks.draw.push(function(a, b) {
                var c = a.getOptions();
                c.series.pie.show && g(a, b)
            })
        }
        var c = 10,
            d = .95,
            e = {
                series: {
                    pie: {
                        show: !1,
                        radius: "auto",
                        innerRadius: 0,
                        startAngle: 1.5,
                        tilt: 1,
                        shadow: {
                            left: 5,
                            top: 15,
                            alpha: .02
                        },
                        offset: {
                            top: 0,
                            left: "auto"
                        },
                        stroke: {
                            color: "#fff",
                            width: 1
                        },
                        label: {
                            show: "auto",
                            formatter: function(a, b) {
                                return "<div style='font-size:x-small;text-align:center;padding:2px;color:" + b.color + ";'>" + a + "<br/>" + Math.round(b.percent) + "%</div>"
                            },
                            radius: 1,
                            background: {
                                color: null,
                                opacity: 0
                            },
                            threshold: 0
                        },
                        combine: {
                            threshold: -1,
                            color: null,
                            label: "Other"
                        },
                        highlight: {
                            opacity: .5
                        }
                    }
                }
            };
        a.plot.plugins.push({
            init: b,
            options: e,
            name: "pie",
            version: "1.1"
        })
    }(jQuery), function(a, b, c) {
        "$:nomunge";

        function d(c) {
            h === !0 && (h = c || 1);
            for (var i = f.length - 1; i >= 0; i--) {
                var m = a(f[i]);
                if (m[0] == b || m.is(":visible")) {
                    var n = m.width(),
                        o = m.height(),
                        p = m.data(k);
                    !p || n === p.w && o === p.h || (m.trigger(j, [p.w = n, p.h = o]), h = c || !0)
                } else p = m.data(k), p.w = 0, p.h = 0
            }
            null !== e && (h && (null == c || 1e3 > c - h) ? e = b.requestAnimationFrame(d) : (e = setTimeout(d, g[l]), h = !1))
        }
        var e, f = [],
            g = a.resize = a.extend(a.resize, {}),
            h = !1,
            i = "setTimeout",
            j = "resize",
            k = j + "-special-event",
            l = "pendingDelay",
            m = "activeDelay",
            n = "throttleWindow";
        g[l] = 200, g[m] = 20, g[n] = !0, a.event.special[j] = {
            setup: function() {
                if (!g[n] && this[i]) return !1;
                var b = a(this);
                f.push(this), b.data(k, {
                    w: b.width(),
                    h: b.height()
                }), 1 === f.length && (e = c, d())
            },
            teardown: function() {
                if (!g[n] && this[i]) return !1;
                for (var b = a(this), c = f.length - 1; c >= 0; c--)
                    if (f[c] == this) {
                        f.splice(c, 1);
                        break
                    }
                b.removeData(k), f.length || (h ? cancelAnimationFrame(e) : clearTimeout(e), e = null)
            },
            add: function(b) {
                function d(b, d, f) {
                    var g = a(this),
                        h = g.data(k) || {};
                    h.w = d !== c ? d : g.width(), h.h = f !== c ? f : g.height(), e.apply(this, arguments)
                }
                if (!g[n] && this[i]) return !1;
                var e;
                return a.isFunction(b) ? (e = b, d) : (e = b.handler, void(b.handler = d))
            }
        }, b.requestAnimationFrame || (b.requestAnimationFrame = function() {
            return b.webkitRequestAnimationFrame || b.mozRequestAnimationFrame || b.oRequestAnimationFrame || b.msRequestAnimationFrame || function(a) {
                return b.setTimeout(function() {
                    a((new Date).getTime())
                }, g[m])
            }
        }()), b.cancelAnimationFrame || (b.cancelAnimationFrame = function() {
            return b.webkitCancelRequestAnimationFrame || b.mozCancelRequestAnimationFrame || b.oCancelRequestAnimationFrame || b.msCancelRequestAnimationFrame || clearTimeout
        }())
    }(jQuery, this), function(a) {
        function b(a) {
            function b() {
                var b = a.getPlaceholder();
                0 != b.width() && 0 != b.height() && (a.resize(), a.setupGrid(), a.draw())
            }

            function c(a) {
                a.getPlaceholder().resize(b)
            }

            function d(a) {
                a.getPlaceholder().unbind("resize", b)
            }
            a.hooks.bindEvents.push(c), a.hooks.shutdown.push(d)
        }
        var c = {};
        a.plot.plugins.push({
            init: b,
            options: c,
            name: "resize",
            version: "1.0"
        })
    }(jQuery), function(a) {
        function b(a, b) {
            return b * Math.floor(a / b)
        }

        function c(a, b, c, d) {
            if ("function" == typeof a.strftime) return a.strftime(b);
            var e = function(a, b) {
                    return a = "" + a, b = "" + (null == b ? "0" : b), 1 == a.length ? b + a : a
                },
                f = [],
                g = !1,
                h = a.getHours(),
                i = 12 > h;
            null == c && (c = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]), null == d && (d = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"]);
            var j;
            j = h > 12 ? h - 12 : 0 == h ? 12 : h;
            for (var k = 0; k < b.length; ++k) {
                var l = b.charAt(k);
                if (g) {
                    switch (l) {
                        case "a":
                            l = "" + d[a.getDay()];
                            break;
                        case "b":
                            l = "" + c[a.getMonth()];
                            break;
                        case "d":
                            l = e(a.getDate());
                            break;
                        case "e":
                            l = e(a.getDate(), " ");
                            break;
                        case "h":
                        case "H":
                            l = e(h);
                            break;
                        case "I":
                            l = e(j);
                            break;
                        case "l":
                            l = e(j, " ");
                            break;
                        case "m":
                            l = e(a.getMonth() + 1);
                            break;
                        case "M":
                            l = e(a.getMinutes());
                            break;
                        case "q":
                            l = "" + (Math.floor(a.getMonth() / 3) + 1);
                            break;
                        case "S":
                            l = e(a.getSeconds());
                            break;
                        case "y":
                            l = e(a.getFullYear() % 100);
                            break;
                        case "Y":
                            l = "" + a.getFullYear();
                            break;
                        case "p":
                            l = i ? "am" : "pm";
                            break;
                        case "P":
                            l = i ? "AM" : "PM";
                            break;
                        case "w":
                            l = "" + a.getDay()
                    }
                    f.push(l), g = !1
                } else "%" == l ? g = !0 : f.push(l)
            }
            return f.join("")
        }

        function d(a) {
            function b(a, b, c, d) {
                a[b] = function() {
                    return c[d].apply(c, arguments)
                }
            }
            var c = {
                date: a
            };
            void 0 != a.strftime && b(c, "strftime", a, "strftime"), b(c, "getTime", a, "getTime"), b(c, "setTime", a, "setTime");
            for (var d = ["Date", "Day", "FullYear", "Hours", "Milliseconds", "Minutes", "Month", "Seconds"], e = 0; e < d.length; e++) b(c, "get" + d[e], a, "getUTC" + d[e]), b(c, "set" + d[e], a, "setUTC" + d[e]);
            return c
        }

        function e(a, b) {
            if ("browser" == b.timezone) return new Date(a);
            if (b.timezone && "utc" != b.timezone) {
                if ("undefined" != typeof timezoneJS && "undefined" != typeof timezoneJS.Date) {
                    var c = new timezoneJS.Date;
                    return c.setTimezone(b.timezone), c.setTime(a), c
                }
                return d(new Date(a))
            }
            return d(new Date(a))
        }

        function f(d) {
            d.hooks.processOptions.push(function(d) {
                a.each(d.getAxes(), function(a, d) {
                    var f = d.options;
                    "time" == f.mode && (d.tickGenerator = function(a) {
                        var c = [],
                            d = e(a.min, f),
                            g = 0,
                            i = f.tickSize && "quarter" === f.tickSize[1] || f.minTickSize && "quarter" === f.minTickSize[1] ? k : j;
                        null != f.minTickSize && (g = "number" == typeof f.tickSize ? f.tickSize : f.minTickSize[0] * h[f.minTickSize[1]]);
                        for (var l = 0; l < i.length - 1 && !(a.delta < (i[l][0] * h[i[l][1]] + i[l + 1][0] * h[i[l + 1][1]]) / 2 && i[l][0] * h[i[l][1]] >= g); ++l);
                        var m = i[l][0],
                            n = i[l][1];
                        if ("year" == n) {
                            if (null != f.minTickSize && "year" == f.minTickSize[1]) m = Math.floor(f.minTickSize[0]);
                            else {
                                var o = Math.pow(10, Math.floor(Math.log(a.delta / h.year) / Math.LN10)),
                                    p = a.delta / h.year / o;
                                m = 1.5 > p ? 1 : 3 > p ? 2 : 7.5 > p ? 5 : 10, m *= o
                            }
                            1 > m && (m = 1)
                        }
                        a.tickSize = f.tickSize || [m, n];
                        var q = a.tickSize[0];
                        n = a.tickSize[1];
                        var r = q * h[n];
                        "second" == n ? d.setSeconds(b(d.getSeconds(), q)) : "minute" == n ? d.setMinutes(b(d.getMinutes(), q)) : "hour" == n ? d.setHours(b(d.getHours(), q)) : "month" == n ? d.setMonth(b(d.getMonth(), q)) : "quarter" == n ? d.setMonth(3 * b(d.getMonth() / 3, q)) : "year" == n && d.setFullYear(b(d.getFullYear(), q)), d.setMilliseconds(0), r >= h.minute && d.setSeconds(0), r >= h.hour && d.setMinutes(0), r >= h.day && d.setHours(0), r >= 4 * h.day && d.setDate(1), r >= 2 * h.month && d.setMonth(b(d.getMonth(), 3)), r >= 2 * h.quarter && d.setMonth(b(d.getMonth(), 6)), r >= h.year && d.setMonth(0);
                        var s, t = 0,
                            u = Number.NaN;
                        do
                            if (s = u, u = d.getTime(), c.push(u), "month" == n || "quarter" == n)
                                if (1 > q) {
                                    d.setDate(1);
                                    var v = d.getTime();
                                    d.setMonth(d.getMonth() + ("quarter" == n ? 3 : 1));
                                    var w = d.getTime();
                                    d.setTime(u + t * h.hour + (w - v) * q), t = d.getHours(), d.setHours(0)
                                } else d.setMonth(d.getMonth() + q * ("quarter" == n ? 3 : 1));
                        else "year" == n ? d.setFullYear(d.getFullYear() + q) : d.setTime(u + r); while (u < a.max && u != s);
                        return c
                    }, d.tickFormatter = function(a, b) {
                        var d = e(a, b.options);
                        if (null != f.timeformat) return c(d, f.timeformat, f.monthNames, f.dayNames);
                        var g, i = b.options.tickSize && "quarter" == b.options.tickSize[1] || b.options.minTickSize && "quarter" == b.options.minTickSize[1],
                            j = b.tickSize[0] * h[b.tickSize[1]],
                            k = b.max - b.min,
                            l = f.twelveHourClock ? " %p" : "",
                            m = f.twelveHourClock ? "%I" : "%H";
                        g = j < h.minute ? m + ":%M:%S" + l : j < h.day ? k < 2 * h.day ? m + ":%M" + l : "%b %d " + m + ":%M" + l : j < h.month ? "%b %d" : i && j < h.quarter || !i && j < h.year ? k < h.year ? "%b" : "%b %Y" : i && j < h.year ? k < h.year ? "Q%q" : "Q%q %Y" : "%Y";
                        var n = c(d, g, f.monthNames, f.dayNames);
                        return n
                    })
                })
            })
        }
        var g = {
                xaxis: {
                    timezone: null,
                    timeformat: null,
                    twelveHourClock: !1,
                    monthNames: null
                }
            },
            h = {
                second: 1e3,
                minute: 6e4,
                hour: 36e5,
                day: 864e5,
                month: 2592e6,
                quarter: 7776e6,
                year: 525949.2 * 60 * 1e3
            },
            i = [
                [1, "second"],
                [2, "second"],
                [5, "second"],
                [10, "second"],
                [30, "second"],
                [1, "minute"],
                [2, "minute"],
                [5, "minute"],
                [10, "minute"],
                [30, "minute"],
                [1, "hour"],
                [2, "hour"],
                [4, "hour"],
                [8, "hour"],
                [12, "hour"],
                [1, "day"],
                [2, "day"],
                [3, "day"],
                [.25, "month"],
                [.5, "month"],
                [1, "month"],
                [2, "month"]
            ],
            j = i.concat([
                [3, "month"],
                [6, "month"],
                [1, "year"]
            ]),
            k = i.concat([
                [1, "quarter"],
                [2, "quarter"],
                [1, "year"]
            ]);
        a.plot.plugins.push({
            init: f,
            options: g,
            name: "time",
            version: "1.0"
        }), a.plot.formatDate = c, a.plot.dateGenerator = e
    }(jQuery), function(a) {
        "use strict";

        function b(b) {
            function d(b) {
                y = b.getOptions();
                var c = y.series.grow.valueIndex;
                if (y.series.grow.active === !0) {
                    var d = !1,
                        e = 0;
                    if (y.series.grow.reanimate && u === j.PLOTTED_LAST_FRAME) {
                        q = !1, u = j.NOT_PLOTTED_YET, s = 0, x = b.getData();
                        var f = Math.min(x.length, v.length);
                        for (e = 0; f > e; e++) x[e].dataOld = v[e];
                        d = !0, r = !0
                    }
                    if (!q) {
                        for (d || (x = b.getData()), u = j.NOT_PLOTTED_YET, s = 0 | +new Date, v = [], e = 0; e < x.length; e++) {
                            var g = x[e];
                            if (g.dataOrg = a.extend(!0, [], g.data), v.push(g.dataOrg), !d)
                                for (var h = 0; h < g.data.length; h++) g.data[h][c] = null === g.dataOrg[h][c] ? null : 0
                        }
                        b.setData(x), q = !0
                    }
                }
            }

            function g(a) {
                r === !0 && h(a)
            }

            function h(a) {
                y = a.getOptions(), y.series.grow.active === !0 && (i(a.getData(), y), s = 0 | +new Date, p = e(m)), r = !1
            }

            function i(a, b) {
                for (var c = b.series.grow.duration, d = 0, e = a.length; e > d; d++) {
                    var f = a[d].grow.duration;
                    f > c && (c = f)
                }
                b.series.grow.duration = c
            }

            function l(a) {
                c("resize") && a.getPlaceholder().resize(n)
            }

            function m() {
                t = +new Date - s | 0;
                for (var a = 0, b = x.length; b > a; a++)
                    for (var c = x[a], d = c.dataOld && c.dataOld.length > 0, f = 0, g = c.grow.growings.length; g > f; f++) {
                        var h, i = c.grow.growings[f];
                        d && "reinit" !== i.reanimate ? ("function" == typeof i.reanimate && (h = i.reanimate), h = "continue" === i.reanimate ? k.reanimate : k.none) : h = "function" == typeof i.stepMode ? i.stepMode : k[i.stepMode] || k.none, h(c, t, i, u)
                    }
                w.setData(x), w.draw(), u === j.NOT_PLOTTED_YET && (u = j.PLOTTED_SOME_FRAMES), t < y.series.grow.duration ? p = e(m) : (u = j.PLOTTED_LAST_FRAME, p = null, w.getPlaceholder().trigger("growFinished"))
            }

            function n() {
                if (p) {
                    for (var c = 0; c < x.length; c++) {
                        var d = x[c];
                        d.data = a.extend(!0, [], d.dataOrg)
                    }
                    b.setData(x), b.setupGrid()
                }
            }

            function o(a) {
                a.getPlaceholder().unbind("resize", n), p && (f(p), p = null)
            }
            var p, q = !1,
                r = !0,
                s = 0,
                t = 0,
                u = j.NOT_PLOTTED_YET,
                v = [],
                w = b,
                x = null,
                y = null;
            b.hooks.drawSeries.push(d), b.hooks.draw.push(g), b.hooks.bindEvents.push(l), b.hooks.shutdown.push(o)
        }

        function c(b) {
            for (var c = a.plot.plugins, d = 0, e = c.length; e > d; d++) {
                var f = c[d];
                if (f.name === b) return !0
            }
            return !1
        }

        function d() {
            for (var a = window.requestAnimationFrame, b = window.cancelAnimationFrame, c = +new Date, d = ["ms", "moz", "webkit", "o"], g = 0; g < d.length && !a; ++g) a = window[d[g] + "RequestAnimationFrame"], b = window[d[g] + "CancelAnimationFrame"] || window[d[g] + "CancelRequestAnimationFrame"];
            a || (a = function(a) {
                var b = +new Date,
                    d = Math.max(0, 16 - (b - c)),
                    e = window.setTimeout(function() {
                        a(b + d)
                    }, d);
                return c = b + d, e
            }), b || (b = function(a) {
                clearTimeout(a)
            }), e = a, f = b
        }
        var e, f, g = "growraf",
            h = "0.4.5",
            i = {
                series: {
                    grow: {
                        active: !1,
                        duration: 1e3,
                        valueIndex: 1,
                        reanimate: !0,
                        growings: [{
                            valueIndex: 1,
                            stepMode: "linear",
                            stepDirection: "up",
                            reanimate: "continue"
                        }]
                    }
                }
            },
            j = {
                NOT_PLOTTED_YET: 0,
                PLOTTED_SOME_FRAMES: 1,
                PLOTTED_LAST_FRAME: 2
            },
            k = {
                none: function(a, b, c, d) {
                    if (d === j.NOT_PLOTTED_YET)
                        for (var e = 0, f = a.data.length; f > e; e++) a.data[e][c.valueIndex] = a.dataOrg[e][c.valueIndex]
                },
                linear: function(a, b, c) {
                    for (var d = Math.min(b, a.grow.duration), e = 0, f = a.data.length; f > e; e++) {
                        var g = a.dataOrg[e][c.valueIndex];
                        null !== g ? "up" === c.stepDirection ? a.data[e][c.valueIndex] = g / a.grow.duration * d : "down" === c.stepDirection && (a.data[e][c.valueIndex] = g + (a.yaxis.max - g) / a.grow.duration * (a.grow.duration - d)) : a.data[e][c.valueIndex] = null
                    }
                },
                maximum: function(a, b, c) {
                    for (var d = Math.min(b, a.grow.duration), e = 0, f = a.data.length; f > e; e++) {
                        var g = a.dataOrg[e][c.valueIndex];
                        null !== g ? "up" === c.stepDirection ? a.data[e][c.valueIndex] = g >= 0 ? Math.min(g, a.yaxis.max / a.grow.duration * d) : Math.max(g, a.yaxis.min / a.grow.duration * d) : "down" === c.stepDirection && (a.data[e][c.valueIndex] = g >= 0 ? Math.max(g, a.yaxis.max / a.grow.duration * (a.grow.duration - d)) : Math.min(g, a.yaxis.min / a.grow.duration * (a.grow.duration - d))) : a.data[e][c.valueIndex] = null
                    }
                },
                delay: function(a, b, c) {
                    if (b >= a.grow.duration)
                        for (var d = 0, e = a.data.length; e > d; d++) a.data[d][c.valueIndex] = a.dataOrg[d][c.valueIndex]
                },
                reanimate: function(a, b, c) {
                    for (var d = Math.min(b, a.grow.duration), e = 0, f = a.data.length; f > e; e++) {
                        var g = a.dataOrg[e][c.valueIndex];
                        if (null === g) a.data[e][c.valueIndex] = null;
                        else if (a.dataOld) {
                            var h = a.dataOld[e][c.valueIndex];
                            a.data[e][c.valueIndex] = h + (g - h) / a.grow.duration * d
                        }
                    }
                }
            };
        d(), a.plot.plugins.push({
            init: b,
            options: i,
            name: g,
            version: h
        })
    }(jQuery), function(a) {
        function b(a, b, c, d) {
            var e = "categories" == b.xaxis.options.mode,
                f = "categories" == b.yaxis.options.mode;
            if (e || f) {
                var g = d.format;
                if (!g) {
                    var h = b;
                    if (g = [], g.push({
                            x: !0,
                            number: !0,
                            required: !0
                        }), g.push({
                            y: !0,
                            number: !0,
                            required: !0
                        }), h.bars.show || h.lines.show && h.lines.fill) {
                        var i = !!(h.bars.show && h.bars.zero || h.lines.show && h.lines.zero);
                        g.push({
                            y: !0,
                            number: !0,
                            required: !1,
                            defaultValue: 0,
                            autoscale: i
                        }), h.bars.horizontal && (delete g[g.length - 1].y, g[g.length - 1].x = !0)
                    }
                    d.format = g
                }
                for (var j = 0; j < g.length; ++j) g[j].x && e && (g[j].number = !1), g[j].y && f && (g[j].number = !1)
            }
        }

        function c(a) {
            var b = -1;
            for (var c in a) a[c] > b && (b = a[c]);
            return b + 1
        }

        function d(a) {
            var b = [];
            for (var c in a.categories) {
                var d = a.categories[c];
                d >= a.min && d <= a.max && b.push([d, c])
            }
            return b.sort(function(a, b) {
                return a[0] - b[0]
            }), b
        }

        function e(b, c, e) {
            if ("categories" == b[c].options.mode) {
                if (!b[c].categories) {
                    var g = {},
                        h = b[c].options.categories || {};
                    if (a.isArray(h))
                        for (var i = 0; i < h.length; ++i) g[h[i]] = i;
                    else
                        for (var j in h) g[j] = h[j];
                    b[c].categories = g
                }
                b[c].options.ticks || (b[c].options.ticks = d), f(e, c, b[c].categories)
            }
        }

        function f(a, b, d) {
            for (var e = a.points, f = a.pointsize, g = a.format, h = b.charAt(0), i = c(d), j = 0; j < e.length; j += f)
                if (null != e[j])
                    for (var k = 0; f > k; ++k) {
                        var l = e[j + k];
                        null != l && g[k][h] && (l in d || (d[l] = i, ++i), e[j + k] = d[l])
                    }
        }

        function g(a, b, c) {
            e(b, "xaxis", c), e(b, "yaxis", c)
        }

        function h(a) {
            a.hooks.processRawData.push(b), a.hooks.processDatapoints.push(g)
        }
        var i = {
            xaxis: {
                categories: null
            },
            yaxis: {
                categories: null
            }
        };
        a.plot.plugins.push({
            init: h,
            options: i,
            name: "categories",
            version: "1.0"
        })
    }(jQuery), function(a) {
        function b(a) {
            function b(a, b) {
                for (var c = null, d = 0; d < b.length && a != b[d]; ++d) b[d].stack == a.stack && (c = b[d]);
                return c
            }

            function c(a, c, d) {
                if (null != c.stack && c.stack !== !1) {
                    var e = b(c, a.getData());
                    if (e) {
                        for (var f, g, h, i, j, k, l, m, n = d.pointsize, o = d.points, p = e.datapoints.pointsize, q = e.datapoints.points, r = [], s = c.lines.show, t = c.bars.horizontal, u = n > 2 && (t ? d.format[2].x : d.format[2].y), v = s && c.lines.steps, w = !0, x = t ? 1 : 0, y = t ? 0 : 1, z = 0, A = 0;;) {
                            if (z >= o.length) break;
                            if (l = r.length, null == o[z]) {
                                for (m = 0; n > m; ++m) r.push(o[z + m]);
                                z += n
                            } else if (A >= q.length) {
                                if (!s)
                                    for (m = 0; n > m; ++m) r.push(o[z + m]);
                                z += n
                            } else if (null == q[A]) {
                                for (m = 0; n > m; ++m) r.push(null);
                                w = !0, A += p
                            } else {
                                if (f = o[z + x], g = o[z + y], i = q[A + x], j = q[A + y], k = 0, f == i) {
                                    for (m = 0; n > m; ++m) r.push(o[z + m]);
                                    r[l + y] += j, k = j, z += n, A += p
                                } else if (f > i) {
                                    if (s && z > 0 && null != o[z - n]) {
                                        for (h = g + (o[z - n + y] - g) * (i - f) / (o[z - n + x] - f), r.push(i), r.push(h + j), m = 2; n > m; ++m) r.push(o[z + m]);
                                        k = j
                                    }
                                    A += p
                                } else {
                                    if (w && s) {
                                        z += n;
                                        continue
                                    }
                                    for (m = 0; n > m; ++m) r.push(o[z + m]);
                                    s && A > 0 && null != q[A - p] && (k = j + (q[A - p + y] - j) * (f - i) / (q[A - p + x] - i)), r[l + y] += k, z += n
                                }
                                w = !1, l != r.length && u && (r[l + 2] += k)
                            }
                            if (v && l != r.length && l > 0 && null != r[l] && r[l] != r[l - n] && r[l + 1] != r[l - n + 1]) {
                                for (m = 0; n > m; ++m) r[l + n + m] = r[l + m];
                                r[l + 1] = r[l - n + 1]
                            }
                        }
                        d.points = r
                    }
                }
            }
            a.hooks.processDatapoints.push(c)
        }
        var c = {
            series: {
                stack: null
            }
        };
        a.plot.plugins.push({
            init: b,
            options: c,
            name: "stack",
            version: "1.2"
        })
    }(jQuery), function(a) {
        function b(a) {
            function b(a, b, e) {
                var g = null;
                if (c(b) && (j(b), d(a), f(a), i(b), q >= 2)) {
                    var h = k(b),
                        r = 0,
                        t = l();
                    r = m(h) ? -1 * n(p, h - 1, Math.floor(q / 2) - 1) - t : n(p, Math.ceil(q / 2), h - 2) + t + 2 * s, g = o(e, b, r), e.points = g
                }
                return g
            }

            function c(a) {
                return null != a.bars && a.bars.show && null != a.bars.order
            }

            function d(a) {
                var b = u ? a.getPlaceholder().innerHeight() : a.getPlaceholder().innerWidth(),
                    c = u ? e(a.getData(), 1) : e(a.getData(), 0),
                    d = c[1] - c[0];
                t = d / b
            }

            function e(a, b) {
                for (var c = new Array, d = 0; d < a.length; d++) c[0] = a[d].data[0][b], c[1] = a[d].data[a[d].data.length - 1][b];
                return c
            }

            function f(a) {
                p = g(a.getData()), q = p.length
            }

            function g(a) {
                for (var b = new Array, c = 0; c < a.length; c++) null != a[c].bars.order && a[c].bars.show && b.push(a[c]);
                return b.sort(h)
            }

            function h(a, b) {
                var c = a.bars.order,
                    d = b.bars.order;
                return d > c ? -1 : c > d ? 1 : 0
            }

            function i(a) {
                r = a.bars.lineWidth ? a.bars.lineWidth : 2, s = r * t
            }

            function j(a) {
                a.bars.horizontal && (u = !0)
            }

            function k(a) {
                for (var b = 0, c = 0; c < p.length; ++c)
                    if (a == p[c]) {
                        b = c;
                        break
                    }
                return b + 1
            }

            function l() {
                var a = 0;
                return q % 2 != 0 && (a = p[Math.ceil(q / 2)].bars.barWidth / 2), a
            }

            function m(a) {
                return a <= Math.ceil(q / 2)
            }

            function n(a, b, c) {
                for (var d = 0, e = b; c >= e; e++) d += a[e].bars.barWidth + 2 * s;
                return d
            }

            function o(a, b, c) {
                for (var d = a.pointsize, e = a.points, f = 0, g = u ? 1 : 0; g < e.length; g += d) e[g] += c, b.data[f][3] = e[g], f++;
                return e
            }
            var p, q, r, s, t = 1,
                u = !1;
            a.hooks.processDatapoints.push(b)
        }
        var c = {
            series: {
                bars: {
                    order: null
                }
            }
        };
        a.plot.plugins.push({
            init: b,
            options: c,
            name: "orderBars",
            version: "0.2"
        })
    }(jQuery), function(a) {
        var b = {
                tooltip: !1,
                tooltipOpts: {
                    content: "%s | X: %x | Y: %y",
                    xDateFormat: null,
                    yDateFormat: null,
                    shifts: {
                        x: 10,
                        y: 20
                    },
                    defaultTheme: !0,
                    onHover: function() {}
                }
            },
            c = function(a) {
                this.tipPosition = {
                    x: 0,
                    y: 0
                }, this.init(a)
            };
        c.prototype.init = function(b) {
            var c = this;
            b.hooks.bindEvents.push(function(b, d) {
                if (c.plotOptions = b.getOptions(), c.plotOptions.tooltip !== !1 && "undefined" != typeof c.plotOptions.tooltip) {
                    c.tooltipOptions = c.plotOptions.tooltipOpts;
                    var e = c.getDomElement();
                    a(b.getPlaceholder()).bind("plothover", function(a, b, d) {
                        if (d) {
                            var f;
                            f = c.stringFormat(c.tooltipOptions.content, d), e.html(f).css({
                                left: c.tipPosition.x + c.tooltipOptions.shifts.x,
                                top: c.tipPosition.y + c.tooltipOptions.shifts.y
                            }).show(), "function" == typeof c.tooltipOptions.onHover && c.tooltipOptions.onHover(d, e)
                        } else e.hide().html("")
                    }), d.mousemove(function(a) {
                        var b = {};
                        b.x = a.pageX, b.y = a.pageY, c.updateTooltipPosition(b)
                    })
                }
            })
        }, c.prototype.getDomElement = function() {
            var b;
            return a("#flotTip").length > 0 ? b = a("#flotTip") : (b = a("<div />").attr("id", "flotTip"), b.appendTo("body").hide().css({
                position: "absolute"
            }), this.tooltipOptions.defaultTheme && b.css({
                background: "#fff",
                "z-index": "100",
                padding: "0.4em 0.6em",
                "border-radius": "0.5em",
                "font-size": "0.8em",
                border: "1px solid #111"
            })), b
        }, c.prototype.updateTooltipPosition = function(a) {
            this.tipPosition.x = a.x, this.tipPosition.y = a.y
        }, c.prototype.stringFormat = function(a, b) {
            var c = /%p\.{0,1}(\d{0,})/,
                d = /%s/,
                e = /%x\.{0,1}(\d{0,})/,
                f = /%y\.{0,1}(\d{0,})/;
            return "function" == typeof a && (a = a(b.series.data[b.dataIndex][0], b.series.data[b.dataIndex][1])), "undefined" != typeof b.series.percent && (a = this.adjustValPrecision(c, a, b.series.percent)), "undefined" != typeof b.series.label && (a = a.replace(d, b.series.label)), this.isTimeMode("xaxis", b) && this.isXDateFormat(b) && (a = a.replace(e, this.timestampToDate(b.series.data[b.dataIndex][0], this.tooltipOptions.xDateFormat))), this.isTimeMode("yaxis", b) && this.isYDateFormat(b) && (a = a.replace(f, this.timestampToDate(b.series.data[b.dataIndex][1], this.tooltipOptions.yDateFormat))), "number" == typeof b.series.data[b.dataIndex][0] && (a = this.adjustValPrecision(e, a, b.series.data[b.dataIndex][0])), "number" == typeof b.series.data[b.dataIndex][1] && (a = this.adjustValPrecision(f, a, b.series.data[b.dataIndex][1])), "undefined" != typeof b.series.xaxis.tickFormatter && (a = a.replace(e, b.series.xaxis.tickFormatter(b.series.data[b.dataIndex][0], b.series.xaxis))), "undefined" != typeof b.series.yaxis.tickFormatter && (a = a.replace(f, b.series.yaxis.tickFormatter(b.series.data[b.dataIndex][1], b.series.yaxis))), a
        }, c.prototype.isTimeMode = function(a, b) {
            return "undefined" != typeof b.series[a].options.mode && "time" === b.series[a].options.mode
        }, c.prototype.isXDateFormat = function() {
            return "undefined" != typeof this.tooltipOptions.xDateFormat && null !== this.tooltipOptions.xDateFormat
        }, c.prototype.isYDateFormat = function() {
            return "undefined" != typeof this.tooltipOptions.yDateFormat && null !== this.tooltipOptions.yDateFormat
        }, c.prototype.timestampToDate = function(b, c) {
            var d = new Date(b);
            return a.plot.formatDate(d, c)
        }, c.prototype.adjustValPrecision = function(a, b, c) {
            var d;
            return null !== b.match(a) && "" !== RegExp.$1 && (d = RegExp.$1, c = c.toFixed(d), b = b.replace(a, c)), b
        };
        var d = [],
            e = function(a) {
                d.push(new c(a))
            };
        a.plot.plugins.push({
            init: e,
            options: b,
            name: "tooltip",
            version: "0.6"
        })
    }(jQuery), function(a) {
        a.supr = function(b, d) {
            var e = {
                    customScroll: {
                        color: "#fff",
                        rscolor: "#fff",
                        size: "3px",
                        opacity: "1",
                        alwaysVisible: !1
                    },
                    header: {
                        fixed: !0,
                        shrink: !0
                    },
                    breadcrumbs: {
                        auto: !0,
                        homeicon: "s16 icomoon-icon-screen-2",
                        dividerIcon: "s16 icomoon-icon-arrow-right-3"
                    },
                    sidebar: {
                        fixed: !0,
                        rememberToggle: !0,
                        offCanvas: !0
                    },
                    rightSidebar: {
                        fixed: !0,
                        rememberToggle: !0
                    },
                    sideNav: {
                        toggleMode: !0,
                        showArrows: !0,
                        sideNavArrowIcon: "icomoon-icon-arrow-down-2 s16",
                        subOpenSpeed: 300,
                        subCloseSpeed: 400,
                        animationEasing: "linear",
                        absoluteUrl: !1,
                        subDir: ""
                    },
                    panels: {
                        refreshIcon: "im-spinner6",
                        toggleIcon: "im-minus",
                        collapseIcon: "im-plus",
                        closeIcon: "im-close",
                        showControlsOnHover: !0,
                        loadingEffect: "facebook",
                        loaderColor: "#bac3d2",
                        rememberSortablePosition: !0
                    },
                    accordion: {
                        toggleIcon: "l-arrows-minus s16",
                        collapseIcon: "l-arrows-plus s16"
                    },
                    tables: {
                        responsive: !0,
                        customscroll: !0
                    },
                    alerts: {
                        animation: !0,
                        closeEffect: "bounceOutDown"
                    },
                    dropdownMenu: {
                        animation: !0,
                        openEffect: "fadeIn"
                    },
                    backToTop: !0
                },
                f = this;
            f.settings = {};
            var b = (a(b), b);
            f.init = function() {
                if (f.settings = a.extend({}, e, d), this.browserSelector(), this.storejs(), this.firstImpression(), this.mouseWheel(), this.retinaReady(), this.toggleSidebar(), this.sideBarNav(), this.setCurrentNav(), this.waitMe(), this.panels(), this.checkBoxesAndRadios(), this.accordions(), this.quickSearch(), this.equalHeight(), this.respondjs(), f.settings.backToTop && this.backToTop(), f.settings.breadcrumbs.auto && this.breadCrumbs(), a(".modal").on("show.bs.modal", function() {
                        f.centerModal()
                    }), f.settings.dropdownMenu.animation && this.dropdownMenuAnimations(), this.dropdownMenuFix(), this.animatedProgressBars(), f.settings.tables.responsive && this.responsiveTables(), this.emailApp(), this.toDoWidget(), f.settings.header.fixed && 1 == store.get("fixed-header") && this.fixedHeader(!0), f.settings.header.shrink && this.shrinkHeader(), f.settings.sidebar.fixed && 1 == store.get("fixed-left-sidebar") && this.fixedSidebar("left"), f.settings.rightSidebar.fixed && 1 == store.get("fixed-right-sidebar") && this.fixedSidebar("right"), f.settings.sidebar.rememberToggle) {
                    var b = f.getBreakPoint();
                    (1 == store.get("sidebarToggle") && "large" == b || 1 == store.get("sidebarToggle") && "laptop" == b) && (f.toggleLeftSidebar(), f.sideBarNavToggle(), f.collapseSideBarNav(!1), f.removeFixedSidebar("left"))
                }
                if (f.settings.rightSidebar.rememberToggle) {
                    var b = f.getBreakPoint();
                    (1 == store.get("rightSidebarToggle") && "large" == b || 1 == store.get("rightSidebarToggle") && "laptop" == b) && (f.toggleRightSidebarBtn("hide"), f.hideRightSidebar()), (1 == store.get("rightSidebarToggle") && "tablet" == b || 1 == store.get("rightSidebarToggle") && "phone" == b) && f.toggleRightSidebarBtn("hide")
                }
                a(window).load(function() {
                    0 == store.get("fixed-header") && 1 == store.get("fixed-right-sidebar") && f.rightSidebarTopPosition(), f.stickyFooter()
                }), a(window).resize(function() {
                    f.centerModal(), f.stickyFooter()
                }), a(window).scroll(function() {
                    0 == store.get("fixed-header") && 1 == store.get("fixed-right-sidebar") && f.rightSidebarTopPosition(), f.stickyFooter()
                })
            }, f.stickyFooter = function() {
                $footer = a("#footer");
                var b = a(".page-content");
                $footer.css(b.height() < a(window).height() ? {
                    position: "absolute"
                } : {
                    position: "static"
                })
            }, f.getBreakPoint = function() {
                var a = jRespond([{
                    label: "phone",
                    enter: 0,
                    exit: 767
                }, {
                    label: "tablet",
                    enter: 768,
                    exit: 979
                }, {
                    label: "laptop",
                    enter: 980,
                    exit: 1366
                }, {
                    label: "large",
                    enter: 1367,
                    exit: 1e4
                }]);
                return a.getBreakpoint()
            }, f.fixedHeader = function(b) {
                var c = a("#header");
                return 1 == b ? (c.addClass("header-fixed"), store.set("fixed-header", 1), a("body").addClass("fixed-header"), !0) : (c.removeClass("header-fixed"), store.set("fixed-header", 0), a("body").removeClass("fixed-header"), !1)
            }, f.fixedSidebar = function(b) {
                var c = a(".page-sidebar"),
                    d = a("#right-sidebar"),
                    e = f.getBreakPoint();
                return "left" !== b || "large" != e && "laptop" != e && c.hasClass("collapse-sidebar") ? "right" !== b || "large" != e && "laptop" != e ? void 0 : (d.addClass("sidebar-fixed"), f.addScrollTo(d.find(".sidebar-scrollarea"), "right", f.settings.customScroll.rscolor), store.set("fixed-right-sidebar", 1), a("body").addClass("fixed-right-sidebar"), !0) : (c.addClass("sidebar-fixed"), f.addScrollTo(c.find(".sidebar-scrollarea"), "right", f.settings.customScroll.color), store.set("fixed-left-sidebar", 1), a("body").addClass("fixed-left-sidebar"), !0)
            }, f.rightSidebarTopPosition = function() {
                var b = a(document).scrollTop();
                b > 49 ? a("#right-sidebar").addClass("rstop") : a("#right-sidebar").removeClass("rstop")
            }, f.addScrollTo = function(a, b, c) {
                a.slimScroll({
                    position: b,
                    height: "100%",
                    distance: "0px",
                    railVisible: !1,
                    size: f.settings.customScroll.size,
                    color: c,
                    railOpacity: f.settings.customScroll.opacity,
                    railColor: f.settings.customScroll.railColor
                })
            }, f.removeScrollTo = function(a) {
                a.parent().hasClass("slimScrollDiv") && (a.parent().replaceWith(a), a.attr("style", ""))
            }, f.removeFixedSidebar = function(b) {
                if ("left" === b) {
                    var c = a("#sidebar .sidebar-scrollarea");
                    a("#sidebar").removeClass("sidebar-fixed"), f.removeScrollTo(c), store.set("fixed-left-sidebar", 0), a("body").removeClass("fixed-left-sidebar")
                }
                if ("right" === b) {
                    var c = a("#right-sidebar .sidebar-scrollarea");
                    a("#right-sidebar").removeClass("sidebar-fixed"), f.removeScrollTo(c), store.set("fixed-right-sidebar", 0), a("body").removeClass("fixed-right-sidebar")
                }
            }, f.toggleRightSidebarBtn = function(b) {
                var c = a("#toggle-right-sidebar");
                "hide" === b && (c.addClass("hide-right-sidebar"), store.set("rightSidebarToggle", 1), c.find("i").removeClass("s16 icomoon-icon-indent-increase").addClass("s16 icomoon-icon-indent-decrease")), "show" === b && (c.removeClass("hide-right-sidebar"), store.set("rightSidebarToggle", 0), c.find("i").removeClass("s16 icomoon-icon-indent-decrease").addClass("s16 icomoon-icon-indent-increase"))
            }, f.toggleSidebar = function() {
                var b = a(".collapseBtn"),
                    c = a("#toggle-right-sidebar"),
                    d = f.getBreakPoint(),
                    e = (a("#sidebar .sidebar-scrollarea"), a(".page-content"), a(".page-sidebar"));
                c.on("click", function(b) {
                    b.preventDefault(), a(this).hasClass("hide-right-sidebar") ? (f.toggleRightSidebarBtn("show"), f.showRightSidebar()) : (f.hideRightSidebar(), f.toggleRightSidebarBtn("hide"))
                }), b.on("click", function(a) {
                    a.preventDefault(), e.hasClass("hide-sidebar") ? f.showLeftSidebar() : e.hasClass("collapse-sidebar") ? (f.unToggleLeftSidebar(), f.collapseSideBarNav(!0)) : "phone" == d ? f.hideLeftSidebar() : (f.toggleLeftSidebar(), f.collapseSideBarNav(!1), f.stickyFooter()), e.hasClass("collapse-sidebar") ? (store.set("sidebarToggle", 1), f.sideBarNavToggle()) : store.set("sidebarToggle", 0)
                })
            }, f.hideRightSidebar = function() {
                var b = f.getBreakPoint();
                a("#right-sidebar").addClass("hide-sidebar"), a("#right-sidebarbg").addClass("hide-sidebar"), a(".page-content, #footer").removeClass("right-sidebar-page"), ("laptop" == b || "tablet" == b || "phone" == b) && a(".page-content").removeClass("rOverLap"), a("#back-to-top").removeClass("rightsidebar")
            }, f.showRightSidebar = function() {
                var b = f.getBreakPoint();
                a("#right-sidebar").removeClass("hide-sidebar"), a("#right-sidebarbg").removeClass("hide-sidebar"), ("laptop" == b || "tablet" == b || "phone" == b) && a(".page-content").addClass("rOverLap"), a(".page-content, #footer").addClass("right-sidebar-page"), a("#back-to-top").addClass("rightsidebar")
            }, f.hideLeftSidebar = function() {
                var b = f.getBreakPoint();
                a(".page-sidebar").addClass("hide-sidebar"), a("#sidebarbg").addClass("hide-sidebar"), a(".page-content, #footer").addClass("full-page"), a(".page-content, #footer").removeClass("sidebar-page"), "phone" != b || f.settings.sidebar.offCanvas || a(".page-content").addClass("overLap"), ("phone" == b && f.settings.sidebar.offCanvas || "tablet" == b && f.settings.sidebar.offCanvas) && a(".page-content, #footer").removeClass("offCanvas")
            }, f.toggleLeftSidebar = function() {
                var b = f.getBreakPoint(),
                    c = a("#sidebar .sidebar-scrollarea");
                f.settings.sidebar.fixed && f.removeScrollTo(c), a(".page-sidebar, #sidebarbg").addClass("collapse-sidebar"), a(".page-content, #footer").addClass("collapsed-sidebar"), a(".page-content, #footer").removeClass("sidebar-page"), "tablet" != b || f.settings.sidebar.offCanvas || a(".page-content, #footer").removeClass("overLap")
            }, f.unToggleLeftSidebar = function() {
                var b = f.getBreakPoint(),
                    c = a("#sidebar .sidebar-scrollarea");
                f.settings.sidebar.fixed && f.addScrollTo(c, "right", f.settings.customScroll.color), a(".page-sidebar, #sidebarbg").removeClass("collapse-sidebar"), a(".page-content, #footer").removeClass("collapsed-sidebar"), a(".page-content, #footer").addClass("sidebar-page"), "tablet" != b || f.settings.sidebar.offCanvas || a(".page-content, #footer").addClass("overLap")
            }, f.showLeftSidebar = function() {
                var b = f.getBreakPoint(),
                    c = a("#sidebar .sidebar-scrollarea");
                f.settings.sidebar.fixed && f.addScrollTo(c), a(".page-sidebar").removeClass("hide-sidebar"), a("#sidebarbg").removeClass("hide-sidebar"), a("#sidebarbg").removeClass("collapse-sidebar"), a(".page-sidebar").removeClass("collapse-sidebar"), a(".page-content, #footer").removeClass("full-page"), ("large" == b || "laptop" == b && !f.settings.sidebar.offCanvas) && a(".page-content, #footer").removeClass("overLap"), "phone" != b || f.settings.sidebar.offCanvas || a(".page-content, #footer").addClass("overLap"), ("phone" == b && f.settings.sidebar.offCanvas || "tablet" == b && f.settings.sidebar.offCanvas) && a(".page-content, #footer").addClass("offCanvas"), a(".page-content, #footer").removeClass("collapsed-sidebar"), a(".page-content, #footer").addClass("sidebar-page")
            }, f.sideBarNav = function() {
                var b = (a(".page-sidebar .sidebar-scrollarea"), a(".mainnav> ul")),
                    c = (b.find("li.current"), b.find("li")),
                    d = b.find("a"),
                    e = b.find("li>ul.sub");
                e.closest("li").addClass("hasSub"), e.prev("a").hasClass("notExpand") || e.prev("a").addClass("notExpand"), f.settings.sideNav.showArrows && (a(".mainnav").hasClass("show-arrows") || a(".mainnav").addClass("show-arrows"), e.prev("a").find("i.hasDrop").length || e.prev("a").prepend('<i class="' + f.settings.sideNav.sideNavArrowIcon + ' hasDrop"></i>')), d.on("click", function(d) {
                    var e = a(this);
                    e.hasClass("notExpand") ? (d.preventDefault(), a(".page-sidebar").hasClass("collapse-sidebar") || (a(this).closest("li").closest("ul").hasClass("show") ? (e.next("ul").slideDown(f.settings.sideNav.subOpenSpeed, f.settings.sideNav.animationEasing), e.next("ul").addClass("show"), e.addClass("expand").removeClass("notExpand"), c.removeClass("highlight-menu"), e.closest("li.hasSub").addClass("highlight-menu")) : (navexpand = b.find("li.hasSub .expand"), navexpand.next("ul").removeClass("show"), navexpand.next("ul").slideUp(f.settings.sideNav.subCloseSpeed, f.settings.sideNav.animationEasing), navexpand.addClass("notExpand").removeClass("expand"), navexpand.find(".sideNav-arrow").removeClass("rotateM180").addClass("rotate0"), e.next("ul").slideDown(f.settings.sideNav.subOpenSpeed, f.settings.sideNav.animationEasing), e.next("ul").addClass("show"), e.addClass("expand").removeClass("notExpand"), c.removeClass("highlight-menu"), e.closest("li.hasSub").addClass("highlight-menu")))) : e.hasClass("expand") && (d.preventDefault(), e.next("ul").removeClass("show"), e.next("ul").slideUp(f.settings.sideNav.subCloseSpeed, f.settings.sideNav.animationEasing), e.addClass("notExpand").removeClass("expand"), c.removeClass("highlight-menu"))
                })
            }, f.sideBarNavToggle = function() {
                var b = a(".mainnav"),
                    c = b.find("li");
                Modernizr.touch ? c.click(function() {
                    _this = a(this), _this.hasClass("hover-li") ? _this.removeClass("hover-li") : (c.each(function() {
                        a(this).removeClass("hover-li")
                    }), _this.addClass("hover-li"))
                }) : c.hover(function() {
                    a(this).addClass("hover-li")
                }, function() {
                    a(this).removeClass("hover-li")
                })
            }, f.setCurrentNav = function() {
                var b = document.domain,
                    c = a(".mainnav> ul"),
                    d = c.find("a");
                if ("" === b) {
                    var e = window.location.pathname.split("/"),
                        g = e.pop();
                    this.setCurrentClass(d, g)
                } else if (f.settings.sideNav.absoluteUrl) {
                    var h = "http://" + b + window.location.pathname;
                    setCurrentClass(d, h)
                } else {
                    var i = window.location.pathname.split("/"),
                        i = i.pop();
                    if ("" != f.settings.sideNav.subDir) var i = window.location.pathname + f.settings.sideNav.subDir;
                    this.setCurrentClass(d, i)
                }
            }, f.setCurrentClass = function(b, c) {
                b.each(function() {
                    var b = a(this).attr("href");
                    if (b === c) {
                        if (a(this).addClass("active"), f.settings.header.fixed && 1 == store.get("fixed-header") && a(this).append("<span class='indicator'></span>"), ulElem = a(this).closest("ul"), ulElem.hasClass("sub")) {
                            ulElem.addClass("show").css("display", "block");
                            var d = a(this).closest("li.hasSub").children("a.notExpand");
                            d.removeClass("notExpand").addClass("expand active-state"), d.closest("li.hasSub").addClass("highlight-menu")
                        }
                    } else "" == c && (c = "index.html"), b === c && (a(this).addClass("active"), f.settings.header.fixed && 1 == store.get("fixed-header") && a(this).append("<span class='indicator'></span>"))
                })
            }, f.panels = function() {
                var b = a(".panel");
                b.each(function(b) {
                    self = a(this), panelHeading = self.find(".panel-heading"), panelsid = "supr" + b, self.attr("id", panelsid), (self.hasClass("toggle") || self.hasClass("panelClose") || self.hasClass("panelRefresh")) && (panelHeading.find(".panel-controls-right").length ? panelControlsRight = panelHeading.find(".panel-controls-right") : (panelHeading.append('<div class="panel-controls panel-controls-right">'), panelControlsRight = panelHeading.find(".panel-controls-right"))), self.hasClass("panelRefresh") && !panelControlsRight.find("a.panel-refresh").length && panelControlsRight.append('<a href="#" class="panel-refresh"><i class="' + f.settings.panels.refreshIcon + '"></i></a>'), self.hasClass("toggle") && !panelControlsRight.find("a.toggle").length && (self.hasClass("panel-closed") ? (panelControlsRight.append('<a href="#" class="toggle panel-maximize"><i class="' + f.settings.panels.collapseIcon + '"></i></a>'), self.find(".panel-body").slideToggle(0), self.find(".panel-footer").slideToggle(0), self.find(".panel-heading").toggleClass("min")) : panelControlsRight.append('<a href="#" class="toggle panel-minimize"><i class="' + f.settings.panels.toggleIcon + '"></i></a>')), self.hasClass("panelClose") && !panelControlsRight.find("a.panel-close").length && panelControlsRight.append('<a href="#" class="panel-close"><i class="' + f.settings.panels.closeIcon + '"></i></a>'), self.hasClass("showControls") ? (self.find(".panel-controls-left").addClass("panel-controls-show"), self.find(".panel-controls-right").addClass("panel-controls-show")) : f.settings.panels.showControlsOnHover && (self.find(".panel-controls-left").addClass("panel-controls-hide"), self.find(".panel-controls-right").addClass("panel-controls-hide"));
                    var c = a(this).find(".scroll"),
                        d = c.data("height");
                    c.slimScroll({
                        position: "right",
                        height: "100%",
                        distance: "0",
                        railVisible: !1,
                        size: f.settings.customScroll.size,
                        color: "#777",
                        railOpacity: f.settings.customScroll.opacity,
                        railColor: "#fff",
                        height: d
                    });
                    var e = a(this).find(".scroll-horizontal");
                    e.slimScrollHorizontal({
                        size: f.settings.customScroll.size,
                        color: "#777",
                        railOpacity: f.settings.customScroll.opacity,
                        railColor: "#fff",
                        width: "100%",
                        positon: "bottom",
                        start: "left",
                        railVisible: !0
                    })
                }), panelControls = b.find(".panel-controls"), panelControlsLink = panelControls.find("a"), f.settings.panels.showControlsOnHover && b.hover(function() {
                    a(this).find(".panel-controls").hasClass("panel-controls-hide") && a(this).find(".panel-controls").fadeIn(300)
                }, function() {
                    a(this).find(".panel-controls").hasClass("panel-controls-hide") && a(this).find(".panel-controls").fadeOut(300)
                }), panelControlsLink.click(function(b) {
                    b.preventDefault(), self = a(this), thisIcon = self.find("i"), thisPanel = self.closest(".panel"), thisPanelBody = thisPanel.find(".panel-body"), thisPanelFooter = thisPanel.find(".panel-footer"), thisPanelHeading = thisPanel.find(".panel-heading"), self.hasClass("panel-close") && setTimeout(function() {
                        thisPanel.remove()
                    }, 500), self.hasClass("toggle") && (self.toggleClass("panel-minimize panel-maximize"), thisIcon.toggleClass(f.settings.panels.toggleIcon + " " + f.settings.panels.collapseIcon), thisPanelBody.slideToggle(200), thisPanelFooter.slideToggle(200), thisPanelHeading.toggleClass("min")), self.hasClass("panel-refresh") && (self.closest(".panel").waitMe({
                        effect: f.settings.panels.loadingEffect,
                        text: "",
                        bg: "rgba(255,255,255,0.7)",
                        color: f.settings.panels.loaderColor
                    }), setTimeout(function() {
                        self.closest(".panel").waitMe("hide")
                    }, 3e3))
                });
                var c = "panels_position_" + h;
                if (!a(".contentwrapper").hasClass("notSortable")) {
                    var d = a(".contentwrapper").find(".sortable-layout"),
                        e = d.find(".panelMove"),
                        g = e.find(".panel-heading"),
                        h = window.location.href,
                        i = localStorage.getItem(c);
                    if (f.settings.panels.rememberSortablePosition && i) {
                        var j = JSON.parse(i);
                        for (var k in j.grid) {
                            var l = d.eq(k);
                            for (var m in j.grid[k].section) l.append(a("#" + j.grid[k].section[m].id))
                        }
                    }
                    d.sortable({
                        items: e,
                        handle: g,
                        placeholder: "panel-placeholder",
                        forcePlaceholderSize: !0,
                        helper: "original",
                        forceHelperSize: !0,
                        cursor: "move",
                        delay: 200,
                        opacity: .8,
                        zIndex: 1e4,
                        tolerance: "pointer",
                        iframeFix: !1,
                        revert: !0,
                        update: function(a, b) {
                            f.settings.panels.rememberSortablePosition && panelSavePosition(b.item)
                        }
                    }).sortable("option", "connectWith", d), a(".reset-layout").click(function() {
                        bootbox.confirm({
                            message: "Warning!!! This action will reset panels position",
                            title: "Are you sure ?",
                            className: "modal-style2",
                            callback: function(a) {
                                a && (localStorage.removeItem(c), location.reload())
                            }
                        }), f.centerModal()
                    }), panelSavePosition = function() {
                        var b = [];
                        d.each(function() {
                            var c = [];
                            a(this).children(".panelMove").each(function() {
                                var b = {};
                                b.id = a(this).attr("id"), c.push(b)
                            });
                            var d = {
                                section: c
                            };
                            b.push(d)
                        });
                        var e = JSON.stringify({
                            grid: b
                        });
                        i != e && localStorage.setItem(c, e, null)
                    }
                }
            }, f.waitMe = function() {
                ! function(a) {
                    a.fn.waitMe = function(b) {
                        return this.each(function() {
                            function c() {
                                k.removeClass(l + "_container"), k.find("." + l).remove()
                            }
                            var d, e, f, g, h, i, j, k = a(this),
                                l = "waitMe",
                                m = !1,
                                n = "background-color",
                                o = "",
                                p = "",
                                q = {
                                    init: function() {
                                        function c() {
                                            g = a('<div class="' + l + '"></div>');
                                            var b = "width:" + j.sizeW + ";height:" + j.sizeH;
                                            switch (j.effect) {
                                                case "none":
                                                    f = 0;
                                                    break;
                                                case "bounce":
                                                    f = 3, h = "", i = b;
                                                    break;
                                                case "rotateplane":
                                                    f = 1, h = "", i = b;
                                                    break;
                                                case "stretch":
                                                    f = 5, h = "", i = b;
                                                    break;
                                                case "orbit":
                                                    f = 2, h = b, i = "";
                                                    break;
                                                case "roundBounce":
                                                    f = 12, h = b, i = "";
                                                    break;
                                                case "win8":
                                                    f = 5, m = !0, h = b, i = b;
                                                    break;
                                                case "win8_linear":
                                                    f = 5, m = !0, h = b, i = "";
                                                    break;
                                                case "ios":
                                                    f = 12, h = b, i = "";
                                                    break;
                                                case "facebook":
                                                    f = 3, h = "", i = b;
                                                    break;
                                                case "rotation":
                                                    f = 1, n = "border-color", h = "", i = b;
                                                    break;
                                                case "timer":
                                                    f = 2, o = "border-color:" + j.color, h = b, i = "";
                                                    break;
                                                case "pulse":
                                                    f = 1, n = "border-color", h = "", i = b;
                                                    break;
                                                case "progressBar":
                                                    f = 1, h = "", i = b;
                                                    break;
                                                case "bouncePulse":
                                                    f = 3, h = "", i = b
                                            }
                                            if ("" == j.sizeW && "" == j.sizeH && (i = "", h = ""), "" != h && "" != o && (o = ";" + o), f > 0) {
                                                e = a('<div class="' + l + "_progress " + j.effect + '"></div>');
                                                for (var c = 1; f >= c; ++c) p += m ? '<div class="' + l + "_progress_elem" + c + '" style="' + i + '"><div style="' + n + ":" + j.color + '"></div></div>' : '<div class="' + l + "_progress_elem" + c + '" style="' + n + ":" + j.color + ";" + i + '"></div>';
                                                e = a('<div class="' + l + "_progress " + j.effect + '" style="' + h + o + '">' + p + "</div>")
                                            }
                                            j.text && (d = a('<div class="' + l + '_text" style="color:' + j.color + '">' + j.text + "</div>")), k.find("> ." + l) && k.find("> ." + l).remove(), waitMeDivObj = a('<div class="' + l + '_content"></div>'), waitMeDivObj.append(e, d), g.append(waitMeDivObj), "HTML" == k[0].tagName && (k = a("body")), k.addClass(l + "_container").append(g), k.find("> ." + l).css({
                                                background: j.bg
                                            }), k.find("." + l + "_content").css({
                                                marginTop: -k.find("." + l + "_content").outerHeight() / 2 + "px"
                                            })
                                        }
                                        var q = {
                                            effect: "bounce",
                                            text: "",
                                            bg: "rgba(255,255,255,0.7)",
                                            color: "#000",
                                            sizeW: "",
                                            sizeH: ""
                                        };
                                        j = a.extend(q, b), c()
                                    },
                                    hide: function() {
                                        c()
                                    }
                                };
                            return q[b] ? q[b].apply(this, Array.prototype.slice.call(arguments, 1)) : "object" != typeof b && b ? void(a.event.special.destroyed = {
                                remove: function(a) {
                                    a.handler && a.handler()
                                }
                            }) : q.init.apply(this, arguments)
                        })
                    }
                }(jQuery)
            }, f.backToTop = function() {
                a(window).scroll(function() {
                    a(window).scrollTop() > 200 ? a("#back-to-top").fadeIn(200) : a("#back-to-top").fadeOut(200)
                }), a("#back-to-top, .back-to-top").click(function() {
                    return a("html, body").animate({
                        scrollTop: 0
                    }, "800"), !1
                })
            }, f.centerModal = function() {
                a(".modal").each(function() {
                    0 == a(this).hasClass("in") && a(this).show();
                    var b = a(window).height() - 60,
                        c = a(this).find(".modal-header").outerHeight() || 2,
                        d = a(this).find(".modal-footer").outerHeight() || 2;
                    a(this).find(".modal-content").css({
                        "max-height": function() {
                            return b
                        }
                    }), a(this).find(".modal-body").css({
                        "max-height": function() {
                            return b - (c + d)
                        }
                    }), a(this).find(".modal-dialog").addClass("modal-dialog-center").css({
                        "margin-top": function() {
                            return -(a(this).outerHeight() / 2)
                        },
                        "margin-left": function() {
                            return -(a(this).outerWidth() / 2)
                        }
                    }), 0 == a(this).hasClass("in") && a(this).hide()
                })
            }, f.accordions = function() {
                var b = a(".accordion");
                b.collapse(), accPutIcon = function() {
                    b.each(function() {
                        accExp = a(this).find(".panel-collapse.in"), accExp.prev(".panel-heading").addClass("content-in").find("a.accordion-toggle").append('<i class="' + f.settings.accordion.toggleIcon + '"></i>'), accNor = a(this).find(".panel-collapse").not(".panel-collapse.in"), accNor.prev(".panel-heading").find("a.accordion-toggle").append('<i class="' + f.settings.accordion.collapseIcon + '"></i>')
                    })
                }, accUpdIcon = function() {
                    b.each(function() {
                        accExp = a(this).find(".panel-collapse.in"), accExp.prev(".panel-heading").find("i").remove(), accExp.prev(".panel-heading").addClass("content-in").find("a.accordion-toggle").append('<i class="' + f.settings.accordion.toggleIcon + '"></i>'), accNor = a(this).find(".panel-collapse").not(".panel-collapse.in"), accNor.prev(".panel-heading").find("i").remove(), accNor.prev(".panel-heading").removeClass("content-in").find("a.accordion-toggle").append('<i class="' + f.settings.accordion.collapseIcon + '"></i>')
                    })
                }, accPutIcon(), a(".accordion").on("shown.bs.collapse", function() {
                    accUpdIcon()
                }).on("hidden.bs.collapse", function() {
                    accUpdIcon()
                })
            }, f.breadCrumbs = function() {
                var b = a(".heading > .breadcrumb"),
                    c = '<i class="' + f.settings.breadcrumbs.homeicon + '"></i>',
                    d = a(".mainnav a.active"),
                    e = d.closest(".sub");
                b.empty(), b.append("<li>You are here:</li>"), b.append('<li><a href="index.html" class="tip" title="back to dashboard">' + c + "</a></li>"), b.append('<span class="divider"><i class="' + f.settings.breadcrumbs.dividerIcon + '"></i></span>'), e.closest("li").hasClass("hasSub") ? (navel1 = e.prev("a.active-state"), link = navel1.attr("href"), text1 = navel1.children(".notification").remove().end().text().trim(), b.append('<li><a href="' + link + '">' + text1 + "</a></li>"), text = d.children(".notification").remove().end().text(), b.append('<span class="divider"><i class="' + f.settings.breadcrumbs.dividerIcon + '"></i></span>'), b.append("<li>" + text + "</li>")) : (text = d.children(".notification").remove().end().text(), b.append("<li>" + text + "</li>"))
            }, f.checkBoxesAndRadios = function() {
                var b = a("input[type=checkbox]"),
                    c = a("input[type=radio]");
                b.each(function(b) {
                    chboxClass = "undefined" == typeof a(this).data("class") ? "checkbox-custom" : a(this).data("class"), "undefined" == typeof a(this).attr("id") ? (chboxId = "chbox" + b, a(this).attr("id", chboxId)) : chboxId = a(this).attr("id"), chboxLabeltxt = "undefined" == typeof a(this).data("label") ? "" : a(this).data("label"), a(this).parent().hasClass(chboxClass) || a(this).parent().hasClass("toggle") || (a(this).wrap('<div class="' + chboxClass + '">'), a(this).parent().append('<label for="' + chboxId + '">' + chboxLabeltxt + "</label>"))
                }), c.each(function(b) {
                    radioClass = "undefined" == typeof a(this).data("class") ? "radio-custom" : a(this).data("class"), "undefined" == typeof a(this).attr("id") ? (radioId = "radio" + b, a(this).attr("id", radioId)) : radioId = a(this).attr("id"), radioLabeltxt = "undefined" == typeof a(this).data("label") ? "" : a(this).data("label"), a(this).parent().hasClass(radioClass) || a(this).parent().hasClass("toggle") || (a(this).wrap('<div class="' + radioClass + '">'), a(this).parent().append('<label for="' + radioId + '">' + radioLabeltxt + "</label>"))
                })
            }, f.shrinkHeader = function() {
                var b, c, d, e = a("#header"),
                    f = a("body");
                return d = e.position().top, b = a(document), c = !1, a(window).on("scroll touchmove", function() {
                    return c = !0
                }), setInterval(function() {
                    return c ? (e.toggleClass("shrink", b.scrollTop() > d), f.toggleClass("shrink-header", b.scrollTop() > d), c = !1) : void 0
                }, 250)
            }, f.storejs = function() {
                ! function(a) {
                    function b() {
                        try {
                            return h in a && a[h]
                        } catch (b) {
                            return !1
                        }
                    }

                    function c(a) {
                        return function() {
                            var b = Array.prototype.slice.call(arguments, 0);
                            b.unshift(e), j.appendChild(e), e.addBehavior("#default#userData"), e.load(h);
                            var c = a.apply(f, b);
                            return j.removeChild(e), c
                        }
                    }

                    function d(a) {
                        return a.replace(/^d/, "___$&").replace(m, "___")
                    }
                    var e, f = {},
                        g = a.document,
                        h = "localStorage",
                        i = "script";
                    if (f.disabled = !1, f.set = function() {}, f.get = function() {}, f.remove = function() {}, f.clear = function() {}, f.transact = function(a, b, c) {
                            var d = f.get(a);
                            null == c && (c = b, b = null), "undefined" == typeof d && (d = b || {}), c(d), f.set(a, d)
                        }, f.getAll = function() {}, f.forEach = function() {}, f.serialize = function(a) {
                            return JSON.stringify(a)
                        }, f.deserialize = function(a) {
                            if ("string" != typeof a) return void 0;
                            try {
                                return JSON.parse(a)
                            } catch (b) {
                                return a || void 0
                            }
                        }, b()) e = a[h], f.set = function(a, b) {
                        return void 0 === b ? f.remove(a) : (e.setItem(a, f.serialize(b)), b)
                    }, f.get = function(a) {
                        return f.deserialize(e.getItem(a))
                    }, f.remove = function(a) {
                        e.removeItem(a)
                    }, f.clear = function() {
                        e.clear()
                    }, f.getAll = function() {
                        var a = {};
                        return f.forEach(function(b, c) {
                            a[b] = c
                        }), a
                    }, f.forEach = function(a) {
                        for (var b = 0; b < e.length; b++) {
                            var c = e.key(b);
                            a(c, f.get(c))
                        }
                    };
                    else if (g.documentElement.addBehavior) {
                        var j, k;
                        try {
                            k = new ActiveXObject("htmlfile"), k.open(), k.write("<" + i + ">document.w=window</" + i + '><iframe src="/favicon.ico"></iframe>'), k.close(), j = k.w.frames[0].document, e = j.createElement("div")
                        } catch (l) {
                            e = g.createElement("div"), j = g.body
                        }
                        var m = new RegExp("[!\"#$%&'()*+,/\\\\:;<=>?@[\\]^`{|}~]", "g");
                        f.set = c(function(a, b, c) {
                            return b = d(b), void 0 === c ? f.remove(b) : (a.setAttribute(b, f.serialize(c)), a.save(h), c)
                        }), f.get = c(function(a, b) {
                            return b = d(b), f.deserialize(a.getAttribute(b))
                        }), f.remove = c(function(a, b) {
                            b = d(b), a.removeAttribute(b), a.save(h)
                        }), f.clear = c(function(a) {
                            var b = a.XMLDocument.documentElement.attributes;
                            a.load(h);
                            for (var c, d = 0; c = b[d]; d++) a.removeAttribute(c.name);
                            a.save(h)
                        }), f.getAll = function() {
                            var a = {};
                            return f.forEach(function(b, c) {
                                a[b] = c
                            }), a
                        }, f.forEach = c(function(a, b) {
                            for (var c, d = a.XMLDocument.documentElement.attributes, e = 0; c = d[e]; ++e) b(c.name, f.deserialize(a.getAttribute(c.name)))
                        })
                    }
                    try {
                        var n = "__storejs__";
                        f.set(n, n), f.get(n) != n && (f.disabled = !0), f.remove(n)
                    } catch (l) {
                        f.disabled = !0
                    }
                    f.enabled = !f.disabled, "undefined" != typeof module && module.exports && this.module !== module ? module.exports = f : "function" == typeof define && define.amd ? define(f) : a.store = f
                }(Function("return this")())
            }, f.mouseWheel = function() {
                ! function(a) {
                    function b(b) {
                        var c = b || window.event,
                            d = [].slice.call(arguments, 1),
                            e = 0,
                            f = 0,
                            g = 0;
                        return b = a.event.fix(c), b.type = "mousewheel", c.wheelDelta && (e = c.wheelDelta / 120), c.detail && (e = -c.detail / 3), g = e, void 0 !== c.axis && c.axis === c.HORIZONTAL_AXIS && (g = 0, f = -1 * e), void 0 !== c.wheelDeltaY && (g = c.wheelDeltaY / 120), void 0 !== c.wheelDeltaX && (f = -1 * c.wheelDeltaX / 120), d.unshift(b, e, f, g), (a.event.dispatch || a.event.handle).apply(this, d)
                    }
                    var c = ["DOMMouseScroll", "mousewheel"];
                    if (a.event.fixHooks)
                        for (var d = c.length; d;) a.event.fixHooks[c[--d]] = a.event.mouseHooks;
                    a.event.special.mousewheel = {
                        setup: function() {
                            if (this.addEventListener)
                                for (var a = c.length; a;) this.addEventListener(c[--a], b, !1);
                            else this.onmousewheel = b
                        },
                        teardown: function() {
                            if (this.removeEventListener)
                                for (var a = c.length; a;) this.removeEventListener(c[--a], b, !1);
                            else this.onmousewheel = null
                        }
                    }, a.fn.extend({
                        mousewheel: function(a) {
                            return a ? this.bind("mousewheel", a) : this.trigger("mousewheel")
                        },
                        unmousewheel: function(a) {
                            return this.unbind("mousewheel", a)
                        }
                    })
                }(jQuery)
            }, f.dropdownMenuFix = function() {
                var b = f.getBreakPoint();
                a("ul.dropdown-menu").each("phone" == b || "tablet" == b ? function() {
                    a(this).removeClass("right"), a(this).removeClass("left");
                    var b = a(this).parent().innerWidth(),
                        c = a(this).innerWidth(),
                        d = b / 2 - c / 2;
                    d += "px", a(this).css("margin-left", d)
                } : function() {
                    if (!a(this).hasClass("left")) {
                        var b = a(this).parent().innerWidth(),
                            c = a(this).innerWidth(),
                            d = b / 2 - c / 2;
                        d += "px", a(this).css("margin-left", d)
                    }
                }), a(".dropdown-form").click(function(a) {
                    a.stopPropagation()
                })
            }, f.expandSideBarNav = function() {
                nav = a(".mainnav"), nava = nav.find("a"), nava.next("ul").slideDown(f.settings.sideNav.subOpenSpeed, f.settings.sideNav.animationEasing), nava.next("ul").addClass("expand"), nava.addClass("drop").removeClass("notExpand")
            }, f.collapseSideBarNav = function(b) {
                nav = a(".mainnav"), nava = nav.find("a.expand"), navactiv = nav.find("a.active-state"), b ? (navactiv.next("ul").slideDown(f.settings.sideNav.subOpenSpeed, f.settings.sideNav.animationEasing).addClass("show"), navactiv.addClass("expand").removeClass("notExpand")) : (nava.next("ul").slideUp(f.settings.sideNav.subOpenSpeed, f.settings.sideNav.animationEasing), nava.next("ul").removeClass("show"), setTimeout(function() {
                    nava.next("ul").removeAttr("style")
                }, f.settings.sideNav.subCloseSpeed), nava.addClass("notExpand").removeClass("expand"))
            }, f.dropdownMenuAnimations = function() {
                openEffect = "animated " + f.settings.dropdownMenu.openEffect, a(".dropdown").on("show.bs.dropdown", function() {
                    a(this).find(".dropdown-menu").addClass(openEffect)
                })
            }, f.retinaReady = function() {
                ! function() {
                    function a() {}

                    function b(a) {
                        return f.retinaImageSuffix + a
                    }

                    function c(a, c) {
                        if (this.path = a || "", "undefined" != typeof c && null !== c) this.at_2x_path = c, this.perform_check = !1;
                        else {
                            if (void 0 !== document.createElement) {
                                var d = document.createElement("a");
                                d.href = this.path, d.pathname = d.pathname.replace(g, b), this.at_2x_path = d.href
                            } else {
                                var e = this.path.split("?");
                                e[0] = e[0].replace(g, b), this.at_2x_path = e.join("?")
                            }
                            this.perform_check = !0
                        }
                    }

                    function d(a) {
                        this.el = a, this.path = new c(this.el.getAttribute("src"), this.el.getAttribute("data-at2x"));
                        var b = this;
                        this.path.check_2x_variant(function(a) {
                            a && b.swap()
                        })
                    }
                    var e = "undefined" == typeof exports ? window : exports,
                        f = {
                            retinaImageSuffix: "@2x",
                            check_mime_type: !0,
                            force_original_dimensions: !0
                        };
                    e.Retina = a, a.configure = function(a) {
                        null === a && (a = {});
                        for (var b in a) a.hasOwnProperty(b) && (f[b] = a[b])
                    }, a.init = function(a) {
                        null === a && (a = e);
                        var b = a.onload || function() {};
                        a.onload = function() {
                            var a, c, e = document.getElementsByTagName("img"),
                                f = [];
                            for (a = 0; a < e.length; a += 1) c = e[a], c.getAttributeNode("data-no-retina") || f.push(new d(c));
                            b()
                        }
                    }, a.isRetina = function() {
                        var a = "(-webkit-min-device-pixel-ratio: 1.5), (min--moz-device-pixel-ratio: 1.5), (-o-min-device-pixel-ratio: 3/2), (min-resolution: 1.5dppx)";
                        return e.devicePixelRatio > 1 ? !0 : e.matchMedia && e.matchMedia(a).matches ? !0 : !1
                    };
                    var g = /\.\w+$/;
                    e.RetinaImagePath = c, c.confirmed_paths = [], c.prototype.is_external = function() {
                        return !(!this.path.match(/^https?\:/i) || this.path.match("//" + document.domain))
                    }, c.prototype.check_2x_variant = function(a) {
                        var b, d = this;
                        return this.is_external() ? a(!1) : this.perform_check || "undefined" == typeof this.at_2x_path || null === this.at_2x_path ? this.at_2x_path in c.confirmed_paths ? a(!0) : (b = new XMLHttpRequest, b.open("HEAD", this.at_2x_path), b.onreadystatechange = function() {
                            if (4 !== b.readyState) return a(!1);
                            if (b.status >= 200 && b.status <= 399) {
                                if (f.check_mime_type) {
                                    var e = b.getResponseHeader("Content-Type");
                                    if (null === e || !e.match(/^image/i)) return a(!1)
                                }
                                return c.confirmed_paths.push(d.at_2x_path), a(!0)
                            }
                            return a(!1)
                        }, void b.send()) : a(!0)
                    }, e.RetinaImage = d, d.prototype.swap = function(a) {
                        function b() {
                            c.el.complete ? (f.force_original_dimensions && (c.el.setAttribute("width", c.el.offsetWidth), c.el.setAttribute("height", c.el.offsetHeight)), c.el.setAttribute("src", a)) : setTimeout(b, 5)
                        }
                        "undefined" == typeof a && (a = this.path.at_2x_path);
                        var c = this;
                        b()
                    }, a.isRetina() && a.init(e)
                }()
            }, f.waitMe = function() {
                ! function(a) {
                    a.fn.waitMe = function(b) {
                        return this.each(function() {
                            var c, d, e, f, g, h, i, j = a(this),
                                k = !1,
                                l = "background-color",
                                m = "",
                                n = {
                                    init: function() {
                                        switch (i = a.extend({
                                            effect: "bounce",
                                            text: "",
                                            bg: "rgba(255,255,255,0.7)",
                                            color: "#000",
                                            sizeW: "",
                                            sizeH: ""
                                        }, b), f = a('<div class="waitMe"></div>'), i.effect) {
                                            case "none":
                                                e = 0;
                                                break;
                                            case "bounce":
                                                e = 3, g = "", h = "width:" + i.sizeW + ";height:" + i.sizeH;
                                                break;
                                            case "rotateplane":
                                                e = 1, g = "", h = "width:" + i.sizeW + ";height:" + i.sizeH;
                                                break;
                                            case "stretch":
                                                e = 5, g = "", h = "width:" + i.sizeW + ";height:" + i.sizeH;
                                                break;
                                            case "orbit":
                                                e = 2, g = "width:" + i.sizeW + ";height:" + i.sizeH, h = "";
                                                break;
                                            case "roundBounce":
                                                e = 12, g = "width:" + i.sizeW + ";height:" + i.sizeH, h = "";
                                                break;
                                            case "win8":
                                                e = 5, k = !0, g = "width:" + i.sizeW + ";height:" + i.sizeH, h = "width:" + i.sizeW + ";height:" + i.sizeH;
                                                break;
                                            case "win8_linear":
                                                e = 5, k = !0, g = "width:" + i.sizeW + ";height:" + i.sizeH, h = "";
                                                break;
                                            case "ios":
                                                e = 12, g = "width:" + i.sizeW + ";height:" + i.sizeH, h = "";
                                                break;
                                            case "facebook":
                                                e = 3, g = "", h = "width:" + i.sizeW + ";height:" + i.sizeH;
                                                break;
                                            case "rotation":
                                                e = 1, l = "border-color", g = "", h = "width:" + i.sizeW + ";height:" + i.sizeH
                                        }
                                        if ("" == i.sizeW && "" == i.sizeH && (g = h = ""), e > 0) {
                                            d = a('<div class="waitMe_progress ' + i.effect + '"></div>');
                                            for (var n = 1; e >= n; ++n) m = k ? m + ('<div class="waitMe_progress_elem' + n + '" style="' + h + '"><div style="' + l + ":" + i.color + '"></div></div>') : m + ('<div class="waitMe_progress_elem' + n + '" style="' + l + ":" + i.color + ";" + h + '"></div>');
                                            d = a('<div class="waitMe_progress ' + i.effect + '" style="' + g + '">' + m + "</div>")
                                        }
                                        i.text && (c = a('<div class="waitMe_text" style="color:' + i.color + '">' + i.text + "</div>")), j.find("> .waitMe") && j.find("> .waitMe").remove(), waitMeDivObj = a('<div class="waitMe_content"></div>'), waitMeDivObj.append(d, c), f.append(waitMeDivObj), "HTML" == j[0].tagName && (j = a("body")), j.addClass("waitMe_container").append(f), j.find("> .waitMe").css({
                                            background: i.bg
                                        }), j.find(".waitMe_content").css({
                                            marginTop: -j.find(".waitMe_content").outerHeight() / 2 + "px"
                                        })
                                    },
                                    hide: function() {
                                        j.removeClass("waitMe_container"), j.find(".waitMe").remove()
                                    }
                                };
                            return n[b] ? n[b].apply(this, Array.prototype.slice.call(arguments, 1)) : "object" != typeof b && b ? void(a.event.special.destroyed = {
                                remove: function(a) {
                                    a.handler && a.handler()
                                }
                            }) : n.init.apply(this, arguments)
                        })
                    }
                }(jQuery)
            }, f.animatedProgressBars = function() {
                ! function(a) {
                    "use strict";
                    var b = function(c, d) {
                        this.$element = a(c), this.options = a.extend({}, b.defaults, d)
                    };
                    b.defaults = {
                        transition_delay: 300,
                        refresh_speed: 50,
                        display_text: "none",
                        use_percentage: !0,
                        percent_format: function(a) {
                            return a + "%"
                        },
                        amount_format: function(a, b) {
                            return a + " / " + b
                        },
                        update: a.noop,
                        done: a.noop,
                        fail: a.noop
                    }, b.prototype.transition = function() {
                        var c = this.$element,
                            d = c.parent(),
                            e = this.$back_text,
                            f = this.$front_text,
                            g = this.options,
                            h = parseInt(c.attr("data-transitiongoal")),
                            i = parseInt(c.attr("aria-valuemin")) || 0,
                            j = parseInt(c.attr("aria-valuemax")) || 100,
                            k = d.hasClass("vertical"),
                            l = g.update && "function" == typeof g.update ? g.update : b.defaults.update,
                            m = g.done && "function" == typeof g.done ? g.done : b.defaults.done,
                            n = g.fail && "function" == typeof g.fail ? g.fail : b.defaults.fail;
                        if (isNaN(h)) return void n("data-transitiongoal not set");
                        var o = Math.round(100 * (h - i) / (j - i));
                        if ("center" === g.display_text && !e && !f) {
                            this.$back_text = e = a("<span>").addClass("progressbar-back-text").prependTo(d), this.$front_text = f = a("<span>").addClass("progressbar-front-text").prependTo(c);
                            var p;
                            k ? (p = d.css("height"), e.css({
                                height: p,
                                "line-height": p
                            }), f.css({
                                height: p,
                                "line-height": p
                            }), a(window).resize(function() {
                                p = d.css("height"), e.css({
                                    height: p,
                                    "line-height": p
                                }), f.css({
                                    height: p,
                                    "line-height": p
                                })
                            })) : (p = d.css("width"), f.css({
                                width: p
                            }), a(window).resize(function() {
                                p = d.css("width"), f.css({
                                    width: p
                                })
                            }))
                        }
                        setTimeout(function() {
                            var a, b, n, p, q;
                            k ? c.css("height", o + "%") : c.css("width", o + "%");
                            var r = setInterval(function() {
                                k ? (n = c.height(), p = d.height()) : (n = c.width(), p = d.width()), a = Math.round(100 * n / p), b = Math.round(i + n / p * (j - i)), a >= o && (a = o, b = h, m(c), clearInterval(r)), "none" !== g.display_text && (q = g.use_percentage ? g.percent_format(a) : g.amount_format(b, j, i), "fill" === g.display_text ? c.text(q) : "center" === g.display_text && (e.text(q), f.text(q))), c.attr("aria-valuenow", b), l(a, c)
                            }, g.refresh_speed)
                        }, g.transition_delay)
                    };
                    var c = a.fn.progressbar;
                    a.fn.progressbar = function(c) {
                        return this.each(function() {
                            var d = a(this),
                                e = d.data("bs.progressbar"),
                                f = "object" == typeof c && c;
                            e || d.data("bs.progressbar", e = new b(this, f)), e.transition()
                        })
                    }, a.fn.progressbar.Constructor = b, a.fn.progressbar.noConflict = function() {
                        return a.fn.progressbar = c, this
                    }
                }(window.jQuery)
            }, f.browserSelector = function() {
                function a(a) {
                    var b = a.toLowerCase(),
                        d = function(a) {
                            return b.indexOf(a) > -1
                        },
                        e = "gecko",
                        f = "webkit",
                        g = "safari",
                        h = "opera",
                        i = "mobile",
                        j = document.documentElement,
                        k = [!/opera|webtv/i.test(b) && /msie\s(\d)/.test(b) ? "ie ie" + RegExp.$1 : d("firefox/2") ? e + " ff2" : d("firefox/3.5") ? e + " ff3 ff3_5" : d("firefox/3.6") ? e + " ff3 ff3_6" : d("firefox/3") ? e + " ff3" : d("gecko/") ? e : d("opera") ? h + (/version\/(\d+)/.test(b) ? " " + h + RegExp.$1 : /opera(\s|\/)(\d+)/.test(b) ? " " + h + RegExp.$2 : "") : d("konqueror") ? "konqueror" : d("blackberry") ? i + " blackberry" : d("android") ? i + " android" : d("chrome") ? f + " chrome" : d("iron") ? f + " iron" : d("applewebkit/") ? f + " " + g + (/version\/(\d+)/.test(b) ? " " + g + RegExp.$1 : "") : d("mozilla/") ? e : "", d("j2me") ? i + " j2me" : d("iphone") ? i + " iphone" : d("ipod") ? i + " ipod" : d("ipad") ? i + " ipad" : d("mac") ? "mac" : d("darwin") ? "mac" : d("webtv") ? "webtv" : d("win") ? "win" + (d("windows nt 6.0") ? " vista" : "") : d("freebsd") ? "freebsd" : d("x11") || d("linux") ? "linux" : "", "js"];
                    return c = k.join(" "), j.className += " " + c, c
                }
                a(navigator.userAgent)
            }, f.firstImpression = function() {
                window.firstImpression = function(a, b) {
                    var c, d, e, f;
                    return c = function(a, b, c) {
                        var d, e, f;
                        return arguments.length > 1 && "[object Object]" !== String(b) ? (c = c || {}, (null === b || void 0 === b) && (c.expires = -1), "number" == typeof c.expires && (d = c.expires, f = c.expires = new Date, f.setTime(f.getTime() + 24 * d * 60 * 60 * 1e3)), c.path = "/", document.cookie = [encodeURIComponent(a), "=", encodeURIComponent(b), c.expires ? "; expires=" + c.expires.toUTCString() : "", c.path ? "; path=" + c.path : "", c.domain ? "; domain=" + c.domain : "", c.secure ? "; secure" : ""].join("")) : (e = new RegExp("(?:^|; )" + encodeURIComponent(a) + "=([^;]*)").exec(document.cookie), e ? decodeURIComponent(e[1]) : null)
                    }, void 0 === a && (a = "_firstImpression"), void 0 === b && (b = 730), null === a ? void c("_firstImpression", null) : null === b ? void c(a, null) : (d = function() {
                        return c(a)
                    }, e = function() {
                        c(a, !0, {
                            expires: b
                        })
                    }, (f = function() {
                        var a = d();
                        return a || e(), !a
                    })())
                }
            }, f.matchHeight = function() {
                ! function(a) {
                    var b = -1,
                        c = -1,
                        d = function(b) {
                            var c = null,
                                d = [];
                            return a(b).each(function() {
                                var b = a(this),
                                    f = b.offset().top - e(b.css("margin-top")),
                                    g = 0 < d.length ? d[d.length - 1] : null;
                                null === g ? d.push(b) : 1 >= Math.floor(Math.abs(c - f)) ? d[d.length - 1] = g.add(b) : d.push(b), c = f
                            }), d
                        },
                        e = function(a) {
                            return parseFloat(a) || 0
                        },
                        f = function(b) {
                            var c = {
                                byRow: !0,
                                remove: !1,
                                property: "height"
                            };
                            return "object" == typeof b && (c = a.extend(c, b)), "boolean" == typeof b && (c.byRow = b), "remove" === b && (c.remove = !0), c
                        },
                        g = a.fn.matchHeight = function(b) {
                            if (b = f(b), b.remove) {
                                var c = this;
                                return this.css(b.property, ""), a.each(g._groups, function(a, b) {
                                    b.elements = b.elements.not(c)
                                }), this
                            }
                            return 1 >= this.length ? this : (g._groups.push({
                                elements: this,
                                options: b
                            }), g._apply(this, b), this)
                        };
                    g._groups = [], g._throttle = 80, g._maintainScroll = !1, g._beforeUpdate = null, g._afterUpdate = null, g._apply = function(b, c) {
                        var h = f(c),
                            i = a(b),
                            j = [i],
                            k = a(window).scrollTop(),
                            l = a("html").outerHeight(!0),
                            m = i.parents().filter(":hidden");
                        return m.css("display", "block"), h.byRow && (i.each(function() {
                            var b = a(this),
                                c = "inline-block" === b.css("display") ? "inline-block" : "block";
                            b.data("style-cache", b.attr("style")), b.css({
                                display: c,
                                "padding-top": "0",
                                "padding-bottom": "0",
                                "margin-top": "0",
                                "margin-bottom": "0",
                                "border-top-width": "0",
                                "border-bottom-width": "0",
                                height: "100px"
                            })
                        }), j = d(i), i.each(function() {
                            var b = a(this);
                            b.attr("style", b.data("style-cache") || "").css("height", "")
                        })), a.each(j, function(b, c) {
                            var d = a(c),
                                f = 0;
                            h.byRow && 1 >= d.length || (d.each(function() {
                                var b = a(this),
                                    c = {
                                        display: "inline-block" === b.css("display") ? "inline-block" : "block"
                                    };
                                c[h.property] = "", b.css(c), b.outerHeight(!1) > f && (f = b.outerHeight(!1)), b.css("display", "")
                            }), d.each(function() {
                                var b = a(this),
                                    c = 0;
                                "border-box" !== b.css("box-sizing") && (c += e(b.css("border-top-width")) + e(b.css("border-bottom-width")), c += e(b.css("padding-top")) + e(b.css("padding-bottom"))), b.css(h.property, f - c)
                            }))
                        }), m.css("display", ""), g._maintainScroll && a(window).scrollTop(k / l * a("html").outerHeight(!0)), this
                    }, g._applyDataApi = function() {
                        var b = {};
                        a("[data-match-height], [data-mh]").each(function() {
                            var c = a(this),
                                d = c.attr("data-match-height") || c.attr("data-mh");
                            b[d] = d in b ? b[d].add(c) : c
                        }), a.each(b, function() {
                            this.matchHeight(!0)
                        })
                    };
                    var h = function(b) {
                        g._beforeUpdate && g._beforeUpdate(b, g._groups), a.each(g._groups, function() {
                            g._apply(this.elements, this.options)
                        }), g._afterUpdate && g._afterUpdate(b, g._groups)
                    };
                    g._update = function(d, e) {
                        if (e && "resize" === e.type) {
                            var f = a(window).width();
                            if (f === b) return;
                            b = f
                        }
                        d ? -1 === c && (c = setTimeout(function() {
                            h(e), c = -1
                        }, g._throttle)) : h(e)
                    }, a(g._applyDataApi), a(window).bind("load", function(a) {
                        g._update(!1, a)
                    }), a(window).bind("resize orientationchange", function(a) {
                        g._update(!0, a)
                    })
                }(jQuery)
            }, f.equalHeight = function() {
                f.matchHeight()
            }, f.quickSearch = function() {
                a(".chat-search input").length && a(".chat-search input").val("").quicksearch(".user-list .list-group-item", {
                    removeDiacritics: !0
                }), a(".todo-search input").length && a(".todo-search input").val("").quicksearch(".todo-list .todo-task-item"), a(".users-search input").length && a(".users-search input").val("").quicksearch(".recent-users-widget .list-group-item"), a(".icon-search").length && a(".icon-search").val("").quicksearch(".col-md-3", {
                    removeDiacritics: !0
                })
            }, f.resSearchButton = function() {
                var b = a(".resSearchBtn"),
                    c = a(".closeSearchForm"),
                    d = a("#header .navbar-form");
                b.click(function() {
                    d.addClass("show animated fadeIn"), c.addClass("show")
                }), c.click(function() {
                    a(this).removeClass("show"), d.removeClass("show animated fadeIn")
                })
            }, f.resSidebarButton = function() {
                var b = a("#showNav");
                b.click(function() {
                    a(this).hasClass("sidebar-showed") ? (f.hideLeftSidebar(), a(this).removeClass("sidebar-showed")) : (f.showLeftSidebar(), a(this).addClass("sidebar-showed"))
                })
            }, f.responsiveTables = function() {
                var b = a(".table").not(".non-responsive");
                b.each(function() {
                    a(this).wrap('<div class="table-responsive" />'), f.settings.tables.customscroll && a("div.table-responsive").slimScrollHorizontal({
                        size: f.settings.customScroll.size,
                        color: "#f3f3f3",
                        railOpacity: "0.3",
                        width: "100%",
                        positon: "bottom",
                        start: "left",
                        railVisible: !0,
                        distance: "3px"
                    })
                })
            }, f.emailApp = function() {
                var b = a("#email-sidebar"),
                    c = a("#email-content");
                a("#email-toggle").click(function() {
                    a(this).hasClass("pushed") ? (a(this).removeClass("pushed"), b.removeClass("email-sidebar-hide"), b.addClass("email-sidebar-show"), c.removeClass("email-content-expand"), c.addClass("email-content-contract")) : (a(this).addClass("pushed"), b.removeClass("email-sidebar-show"), b.addClass("email-sidebar-hide"), c.removeClass("email-content-contract"), c.addClass("email-content-expand"))
                })
            }, f.collapseEmailAppSidebar = function() {
                var b = a("#email-sidebar"),
                    c = a("#email-content");
                b.removeClass("email-sidebar-show"), b.addClass("email-sidebar-hide"), c.removeClass("email-content-contract"), c.addClass("email-content-expand"), a("#email-toggle").addClass("pushed")
            }, f.expandEmailAppSidebar = function() {
                var b = a("#email-sidebar"),
                    c = a("#email-content");
                b.removeClass("email-sidebar-hide"), b.addClass("email-sidebar-show"), c.removeClass("email-content-expand"), c.addClass("email-content-contract"), a("#email-toggle").removeClass("pushed")
            }, f.toDoWidget = function() {
                var b = a(".todo-widget"),
                    c = b.find(".todo-task-item"),
                    d = c.find('input[type="checkbox"]'),
                    e = c.find(".close");
                d.change(function() {
                    a(this).prop("checked") ? a(this).closest(".todo-task-item").addClass("task-done") : a(this).closest(".todo-task-item").removeClass("task-done")
                }), e.click(function() {
                    a(this).closest(".todo-task-item").fadeOut("500")
                })
            }, f.removeDefaultClassess = function() {
                var b = (f.getBreakPoint(), a("#sidebar")),
                    c = a("#right-sidebar"),
                    d = a(".page-content");
                d.addClass("sidebar-page"), d.addClass("right-sidebar-page"), b.removeClass("hidden-lg hidden-md hidden-sm hidden-xs"), c.removeClass("hidden-lg hidden-md hidden-sm hidden-xs"), a("#sidebarbg, #right-sidebarbg").removeClass("hidden-lg hidden-md hidden-sm hidden-xs")
            }, f.respondjs = function() {
                var b = jRespond([{
                    label: "phone",
                    enter: 0,
                    exit: 767
                }, {
                    label: "tablet",
                    enter: 768,
                    exit: 979
                }, {
                    label: "laptop",
                    enter: 980,
                    exit: 1366
                }, {
                    label: "large",
                    enter: 1367,
                    exit: 1e4
                }]);
                return b.addFunc({
                    breakpoint: "large",
                    enter: function() {
                        f.removeDefaultClassess(), store.set("rightSidebarToggle", 0), f.toggleRightSidebarBtn("show"), f.showRightSidebar()
                    },
                    exit: function() {}
                }), b.addFunc({
                    breakpoint: "laptop",
                    enter: function() {
                        f.removeDefaultClassess(), f.hideRightSidebar()
                    },
                    exit: function() {}
                }), b.addFunc({
                    breakpoint: "tablet",
                    enter: function() {
                        f.removeDefaultClassess(), f.toggleLeftSidebar(), f.sideBarNavToggle(), f.collapseSideBarNav(!1), f.hideRightSidebar(), f.dropdownMenuFix()
                    },
                    exit: function() {
                        f.showLeftSidebar(), f.dropdownMenuFix()
                    }
                }), b.addFunc({
                    breakpoint: "phone",
                    enter: function() {
                        f.removeDefaultClassess(), f.dropdownMenuFix(), f.hideLeftSidebar(), f.collapseEmailAppSidebar(), a("#email-content").addClass("email-content-offCanvas"), f.hideRightSidebar()
                    },
                    exit: function() {
                        f.showLeftSidebar(), a("#email-content").removeClass("email-content-offCanvas"), f.expandEmailAppSidebar()
                    }
                }), b
            }, f.init()
        }, a.fn.supr = function(b) {
            return this.each(function() {
                if (void 0 == a(this).data("supr")) {
                    var c = new a.supr(this, b);
                    a(this).data("supr", c)
                }
            })
        }
    }(jQuery), window.console || (console = {
        log: function() {}
    }), navigator.userAgent.match(/IEMobile\/10\.0/)) {
    var msViewportStyle = document.createElement("style");
    msViewportStyle.appendChild(document.createTextNode("@-ms-viewport{width:auto!important}")), document.querySelector("head").appendChild(msViewportStyle)
}
var nua = navigator.userAgent,
    isAndroid = nua.indexOf("Mozilla/5.0") > -1 && nua.indexOf("Android ") > -1 && nua.indexOf("AppleWebKit") > -1 && -1 === nua.indexOf("Chrome");
isAndroid && $("select.form-control").removeClass("form-control").css("width", "100%"), window.addEventListener("load", function() {
    FastClick.attach(document.body)
}, !1), $(document).ready(function() {
    $("a[href^=#]").click(function(a) {
        a.preventDefault()
    }), $("body").supr({
        customScroll: {
            color: "#c4c4c4",
            rscolor: "#95A5A6",
            size: "5px",
            opacity: "1",
            alwaysVisible: !1
        },
        header: {
            fixed: !0,
            shrink: !0
        },
        breadcrumbs: {
            auto: !0,
            homeicon: "s16 icomoon-icon-screen-2",
            dividerIcon: "s16 icomoon-icon-arrow-right-3"
        },
        sidebar: {
            fixed: !0,
            rememberToggle: !0,
            offCanvas: !1
        },
        rightSidebar: {
            fixed: !0,
            rememberToggle: !0
        },
        sideNav: {
            toggleMode: !0,
            showArrows: !0,
            sideNavArrowIcon: "icomoon-icon-arrow-down-2 s16",
            subOpenSpeed: 200,
            subCloseSpeed: 300,
            animationEasing: "linear",
            absoluteUrl: !1,
            subDir: ""
        },
        panels: {
            refreshIcon: "brocco-icon-refresh s12",
            toggleIcon: "icomoon-icon-plus",
            collapseIcon: "icomoon-icon-minus",
            closeIcon: "icomoon-icon-close",
            showControlsOnHover: !1,
            loadingEffect: "rotateplane",
            loaderColor: "#616469",
            rememberSortablePosition: !0
        },
        accordion: {
            toggleIcon: "icomoon-icon-minus s12",
            collapseIcon: "icomoon-icon-plus s12"
        },
        tables: {
            responsive: !0,
            customscroll: !0
        },
        alerts: {
            animation: !0,
            closeEffect: "bounceOutDown"
        },
        dropdownMenu: {
            animation: !0,
            openEffect: "fadeIn"
        },
        backToTop: !0
    }), $("[data-toggle=tooltip]").tooltip({
        container: "body"
    }), $(".tip").tooltip({
        placement: "top",
        container: "body"
    }), $(".tipR").tooltip({
        placement: "right",
        container: "body"
    }), $(".tipB").tooltip({
        placement: "bottom",
        container: "body"
    }), $(".tipL").tooltip({
        placement: "left",
        container: "body"
    }), $("[data-toggle=popover]").popover({
        html: !0
    });
    var a = $("body").data("supr");
    firstImpression() && (a.settings.header.fixed ? store.set("fixed-header", 1) : store.set("fixed-header", 0), a.settings.sidebar.fixed ? store.set("fixed-left-sidebar", 1) : store.set("fixed-left-sidebar", 0), a.settings.rightSidebar.fixed ? store.set("fixed-right-sidebar", 1) : store.set("fixed-right-sidebar", 0)), 1 == store.get("fixed-header") ? $("#fixed-header-toggle").prop("checked", !0) : $("#fixed-header-toggle").prop("checked", !1), 1 == store.get("fixed-left-sidebar") ? $("#fixed-left-sidebar").prop("checked", !0) : $("#fixed-left-sidebar").prop("checked", !1), 1 == store.get("fixed-right-sidebar") ? $("#fixed-right-sidebar").prop("checked", !0) : $("#fixed-right-sidebar").prop("checked", !1), $("#fixed-header-toggle").on("change", function() {
        a.fixedHeader(this.checked ? !0 : !1)
    }), $("#fixed-left-sidebar").on("change", function() {
        this.checked ? a.fixedSidebar("left") : a.removeFixedSidebar("left")
    }), $("#fixed-right-sidebar").on("change", function() {
        this.checked ? a.fixedSidebar("right") : a.removeFixedSidebar("right")
    })
}), $(document).ready(function() {
    randNum = function() {
        return Math.floor(21 * Math.random()) + 20
    };
    var a = ["#88bbc8", "#ed7a53", "#9FC569", "#bbdce3", "#9a3b1b", "#5a8022", "#2c7282"];
    $(".simple-chart").length && $(function() {
        for (var b = [], c = [], d = 0; 14 > d; d += .5) b.push([d, Math.sin(d)]), c.push([d, Math.cos(d)]); {
            var e = {
                grid: {
                    show: !0,
                    aboveData: !0,
                    color: "#3f3f3f",
                    labelMargin: 5,
                    axisMargin: 0,
                    borderWidth: 0,
                    borderColor: null,
                    minBorderMargin: 5,
                    clickable: !0,
                    hoverable: !0,
                    autoHighlight: !0,
                    mouseActiveRadius: 20
                },
                series: {
                    grow: {
                        active: !1
                    },
                    lines: {
                        show: !0,
                        fill: !1,
                        lineWidth: 4,
                        steps: !1
                    },
                    points: {
                        show: !0,
                        radius: 5,
                        symbol: "circle",
                        fill: !0,
                        borderColor: "#fff"
                    }
                },
                legend: {
                    position: "se"
                },
                colors: a,
                shadowSize: 1,
                tooltip: !0,
                tooltipOpts: {
                    content: "%s : %y.3",
                    shifts: {
                        x: -30,
                        y: -50
                    }
                }
            };
            $.plot($(".simple-chart"), [{
                label: "Received",
                data: b,
                lines: {
                    fillColor: "#f2f7f9"
                },
                points: {
                    fillColor: "#88bbc8"
                }
            }, {
                label: "Payment",
                data: c,
                lines: {
                    fillColor: "#fff8f2"
                },
                points: {
                    fillColor: "#ed7a53"
                }
            }], e)
        }
    }), $(".lines-chart").length && $(function() {
        var b = [
                [1, 3 + randNum()],
                [2, 6 + randNum()],
                [3, 9 + randNum()],
                [4, 12 + randNum()],
                [5, 15 + randNum()],
                [6, 18 + randNum()],
                [7, 21 + randNum()],
                [8, 15 + randNum()],
                [9, 18 + randNum()],
                [10, 21 + randNum()],
                [11, 24 + randNum()],
                [12, 27 + randNum()],
                [13, 30 + randNum()],
                [14, 33 + randNum()],
                [15, 24 + randNum()],
                [16, 27 + randNum()],
                [17, 30 + randNum()],
                [18, 33 + randNum()],
                [19, 36 + randNum()],
                [20, 39 + randNum()],
                [21, 42 + randNum()],
                [22, 45 + randNum()],
                [23, 36 + randNum()],
                [24, 39 + randNum()],
                [25, 42 + randNum()],
                [26, 45 + randNum()],
                [27, 38 + randNum()],
                [28, 51 + randNum()],
                [29, 55 + randNum()],
                [30, 60 + randNum()]
            ],
            c = [
                [1, randNum() - 5],
                [2, randNum() - 4],
                [3, randNum() - 4],
                [4, randNum()],
                [5, 4 + randNum()],
                [6, 4 + randNum()],
                [7, 5 + randNum()],
                [8, 5 + randNum()],
                [9, 6 + randNum()],
                [10, 6 + randNum()],
                [11, 6 + randNum()],
                [12, 2 + randNum()],
                [13, 3 + randNum()],
                [14, 4 + randNum()],
                [15, 4 + randNum()],
                [16, 4 + randNum()],
                [17, 5 + randNum()],
                [18, 5 + randNum()],
                [19, 2 + randNum()],
                [20, 2 + randNum()],
                [21, 3 + randNum()],
                [22, 3 + randNum()],
                [23, 3 + randNum()],
                [24, 2 + randNum()],
                [25, 4 + randNum()],
                [26, 4 + randNum()],
                [27, 5 + randNum()],
                [28, 2 + randNum()],
                [29, 2 + randNum()],
                [30, 3 + randNum()]
            ],
            d = $(".lines-chart"),
            e = {
                grid: {
                    show: !0,
                    aboveData: !0,
                    color: "#3f3f3f",
                    labelMargin: 5,
                    axisMargin: 0,
                    borderWidth: 0,
                    borderColor: null,
                    minBorderMargin: 5,
                    clickable: !0,
                    hoverable: !0,
                    autoHighlight: !0,
                    mouseActiveRadius: 20
                },
                series: {
                    grow: {
                        active: !1
                    },
                    lines: {
                        show: !0,
                        fill: !0,
                        lineWidth: 2,
                        steps: !1
                    },
                    points: {
                        show: !1
                    }
                },
                legend: {
                    position: "se"
                },
                yaxis: {
                    min: 0
                },
                xaxis: {
                    ticks: 11,
                    tickDecimals: 0
                },
                colors: a,
                shadowSize: 1,
                tooltip: !0,
                tooltipOpts: {
                    content: "%s : %y.0",
                    shifts: {
                        x: -30,
                        y: -50
                    }
                }
            };
        $.plot(d, [{
            label: "Received",
            data: b,
            lines: {
                fillColor: "#f2f7f9"
            },
            points: {
                fillColor: "#88bbc8"
            }
        }, {
            label: "Refund",
            data: c,
            lines: {
                fillColor: "#fff8f2"
            },
            points: {
                fillColor: "#ed7a53"
            }
        }], e)
    }), $(".order-bars-chart").length && $(function() {
        for (var b = [], c = 0; 10 >= c; c += 1) b.push([c, parseInt(30 * Math.random())]);
        for (var d = [], c = 0; 10 >= c; c += 1) d.push([c, parseInt(30 * Math.random())]);
        for (var e = [], c = 0; 10 >= c; c += 1) e.push([c, parseInt(30 * Math.random())]);
        var f = new Array;
        f.push({
            label: "Received",
            data: b,
            bars: {
                order: 1
            }
        }), f.push({
            label: "Payment",
            data: d,
            bars: {
                order: 2
            }
        }), f.push({
            label: "Dishouner",
            data: e,
            bars: {
                order: 3
            }
        });
        var g = {
            bars: {
                show: !0,
                barWidth: .2,
                fill: 1
            },
            grid: {
                show: !0,
                aboveData: !1,
                color: "#3f3f3f",
                labelMargin: 5,
                axisMargin: 0,
                borderWidth: 0,
                borderColor: null,
                minBorderMargin: 5,
                clickable: !0,
                hoverable: !0,
                autoHighlight: !1,
                mouseActiveRadius: 20
            },
            series: {
                grow: {
                    active: !1
                }
            },
            legend: {
                position: "ne"
            },
            colors: a,
            tooltip: !0,
            tooltipOpts: {
                content: "%s : %y.0",
                shifts: {
                    x: -30,
                    y: -50
                }
            }
        };
        $.plot($(".order-bars-chart"), f, g)
    }), $(".simple-donut").length && $(function() {
        var a = [{
            label: "Project A",
            data: 38,
            color: "#88bbc8"
        }, {
            label: "Project B",
            data: 23,
            color: "#ed7a53"
        }, {
            label: "Project C",
            data: 15,
            color: "#9FC569"
        }, {
            label: "Project D",
            data: 9,
            color: "#bbdce3"
        }, {
            label: "Project E",
            data: 7,
            color: "#9a3b1b"
        }, {
            label: "Project F",
            data: 5,
            color: "#5a8022"
        }, {
            label: "Project G",
            data: 3,
            color: "#2c7282"
        }];
        $.plot($(".simple-donut"), a, {
            series: {
                pie: {
                    show: !0,
                    innerRadius: .4,
                    highlight: {
                        opacity: .1
                    },
                    radius: 1,
                    stroke: {
                        color: "#fff",
                        width: 8
                    },
                    startAngle: 2,
                    combine: {
                        color: "#353535",
                        threshold: .05
                    },
                    label: {
                        show: !0,
                        radius: 1,
                        formatter: function(a, b) {
                            return '<div class="pie-chart-label">' + a + "&nbsp;" + Math.round(b.percent) + "%</div>"
                        }
                    }
                },
                grow: {
                    active: !1
                }
            },
            legend: {
                show: !1
            },
            grid: {
                hoverable: !0,
                clickable: !0
            },
            tooltip: !0,
            tooltipOpts: {
                content: "%s : %y.1%",
                shifts: {
                    x: -30,
                    y: -50
                }
            }
        })
    }), $(".stacked-bars-chart").length && $(function() {
        for (var b = [], c = 0; 10 >= c; c += 1) b.push([c, parseInt(30 * Math.random())]);
        for (var d = [], c = 0; 10 >= c; c += 1) d.push([c, parseInt(30 * Math.random())]);
        for (var e = [], c = 0; 10 >= c; c += 1) e.push([c, parseInt(30 * Math.random())]);
        var f = new Array;
        f.push({
            label: "Data One",
            data: b
        }), f.push({
            label: "Data Two",
            data: d
        }), f.push({
            label: "Data Tree",
            data: e
        });
        var g = 0,
            h = !0,
            i = !1,
            j = !1,
            k = {
                grid: {
                    show: !0,
                    aboveData: !1,
                    color: "#3f3f3f",
                    labelMargin: 5,
                    axisMargin: 0,
                    borderWidth: 0,
                    borderColor: null,
                    minBorderMargin: 5,
                    clickable: !0,
                    hoverable: !0,
                    autoHighlight: !0,
                    mouseActiveRadius: 20
                },
                series: {
                    grow: {
                        active: !1
                    },
                    stack: g,
                    lines: {
                        show: i,
                        fill: !0,
                        steps: j
                    },
                    bars: {
                        show: h,
                        barWidth: .5,
                        fill: 1
                    }
                },
                xaxis: {
                    ticks: 11,
                    tickDecimals: 0
                },
                legend: {
                    position: "se"
                },
                colors: a,
                shadowSize: 1,
                tooltip: !0,
                tooltipOpts: {
                    content: "%s : %y.0",
                    shifts: {
                        x: -30,
                        y: -50
                    }
                }
            };
        $.plot($(".stacked-bars-chart"), f, k)
    }), $(".simple-pie").length && $(function() {
        var a = [{
            label: "USA",
            data: 38,
            color: "#88bbc8"
        }, {
            label: "Brazil",
            data: 23,
            color: "#ed7a53"
        }, {
            label: "India",
            data: 15,
            color: "#9FC569"
        }, {
            label: "Turkey",
            data: 9,
            color: "#bbdce3"
        }, {
            label: "France",
            data: 7,
            color: "#9a3b1b"
        }, {
            label: "China",
            data: 5,
            color: "#5a8022"
        }, {
            label: "Germany",
            data: 3,
            color: "#2c7282"
        }];
        $.plot($(".simple-pie"), a, {
            series: {
                pie: {
                    show: !0,
                    highlight: {
                        opacity: .1
                    },
                    radius: 1,
                    stroke: {
                        color: "#fff",
                        width: 2
                    },
                    startAngle: 2,
                    combine: {
                        color: "#353535",
                        threshold: .05
                    },
                    label: {
                        show: !0,
                        radius: 1,
                        formatter: function(a, b) {
                            return '<div class="pie-chart-label">' + a + "&nbsp;" + Math.round(b.percent) + "%</div>"
                        }
                    }
                },
                grow: {
                    active: !1
                }
            },
            legend: {
                show: !1
            },
            grid: {
                hoverable: !0,
                clickable: !0
            },
            tooltip: !0,
            tooltipOpts: {
                content: "%s : %y.1%",
                shifts: {
                    x: -30,
                    y: -50
                }
            }
        })
    }), $(".horizontal-bars-chart").length && $(function() {
        for (var b = [], c = 0; 5 >= c; c += 1) b.push([parseInt(30 * Math.random()), c]);
        for (var d = [], c = 0; 5 >= c; c += 1) d.push([parseInt(30 * Math.random()), c]);
        for (var e = [], c = 0; 5 >= c; c += 1) e.push([parseInt(30 * Math.random()), c]);
        var f = new Array;
        f.push({
            data: b,
            bars: {
                horizontal: !0,
                show: !0,
                barWidth: .2,
                order: 1
            }
        }), f.push({
            data: d,
            bars: {
                horizontal: !0,
                show: !0,
                barWidth: .2,
                order: 2
            }
        }), f.push({
            data: e,
            bars: {
                horizontal: !0,
                show: !0,
                barWidth: .2,
                order: 3
            }
        });
        var g = {
            grid: {
                show: !0,
                aboveData: !1,
                color: "#3f3f3f",
                labelMargin: 5,
                axisMargin: 0,
                borderWidth: 0,
                borderColor: null,
                minBorderMargin: 5,
                clickable: !0,
                hoverable: !0,
                autoHighlight: !1,
                mouseActiveRadius: 20
            },
            series: {
                grow: {
                    active: !1
                },
                bars: {
                    show: !0,
                    horizontal: !0,
                    barWidth: .2,
                    fill: 1
                }
            },
            legend: {
                position: "ne"
            },
            colors: a,
            tooltip: !0,
            tooltipOpts: {
                content: "%s : %y.0",
                shifts: {
                    x: -30,
                    y: -50
                }
            }
        };
        $.plot($(".horizontal-bars-chart"), f, g)
    }), $(".auto-update-chart").length && $(function() {
        function b() {
            for (d.length > 0 && (d = d.slice(1)); d.length < e;) {
                var a = d.length > 0 ? d[d.length - 1] : 50,
                    b = a + 10 * Math.random() - 5;
                0 > b && (b = 0), b > 100 && (b = 100), d.push(b)
            }
            for (var c = [], f = 0; f < d.length; ++f) c.push([f, d[f]]);
            return c
        }

        function c() {
            h.setData([b()]), h.draw(), setTimeout(c, f)
        }
        var d = [],
            e = 300,
            f = 200,
            g = {
                series: {
                    grow: {
                        active: !1
                    },
                    shadowSize: 0,
                    lines: {
                        show: !0,
                        fill: !0,
                        lineWidth: 2,
                        steps: !1
                    }
                },
                grid: {
                    show: !0,
                    aboveData: !1,
                    color: "#3f3f3f",
                    labelMargin: 5,
                    axisMargin: 0,
                    borderWidth: 0,
                    borderColor: null,
                    minBorderMargin: 5,
                    clickable: !0,
                    hoverable: !0,
                    autoHighlight: !1,
                    mouseActiveRadius: 20
                },
                colors: a,
                tooltip: !0,
                tooltipOpts: {
                    content: "Value is : %y.0",
                    shifts: {
                        x: -30,
                        y: -50
                    }
                },
                yaxis: {
                    min: 0,
                    max: 100
                },
                xaxis: {
                    show: !0
                }
            },
            h = $.plot($(".auto-update-chart"), [b()], g);
        c()
    })
});
var positive = [1, 5, 3, 7, 8, 6, 10],
    negative = [10, 6, 8, 7, 3, 5, 1],
    negative1 = [7, 6, 8, 7, 6, 5, 4];
$("#stat1").sparkline(positive, {
    height: 15,
    spotRadius: 0,
    barColor: "#9FC569",
    type: "bar"
}), $("#stat2").sparkline(negative, {
    height: 15,
    spotRadius: 0,
    barColor: "#ED7A53",
    type: "bar"
}), $("#stat3").sparkline(negative1, {
    height: 15,
    spotRadius: 0,
    barColor: "#ED7A53",
    type: "bar"
}), $("#stat4").sparkline(positive, {
    height: 15,
    spotRadius: 0,
    barColor: "#9FC569",
    type: "bar"
}), $("#stat5").sparkline(positive, {
    height: 15,
    spotRadius: 0,
    barColor: "#9FC569",
    type: "bar"
}), $("#stat6").sparkline(positive, {
    width: 70,
    height: 20,
    lineColor: "#88bbc8",
    fillColor: "#f2f7f9",
    spotColor: "#e72828",
    maxSpotColor: "#005e20",
    minSpotColor: "#f7941d",
    spotRadius: 3,
    lineWidth: 2
})