/*!
 * pace.js v1.2.4
 * https://github.com/CodeByZach/pace/
 * Licensed MIT © HubSpot, Inc.
 */
(function () {
    function t(t, e) {
        return function () {
            return t.apply(e, arguments);
        };
    }
    var e,
        i,
        n,
        s,
        o,
        r,
        a,
        l,
        h,
        c,
        u,
        d,
        p,
        f,
        g,
        m,
        v,
        _,
        b,
        y,
        w,
        x,
        k,
        C,
        T,
        D,
        S,
        A,
        E,
        I,
        P,
        M,
        O,
        H,
        L,
        N,
        W,
        R,
        z,
        j,
        F,
        q,
        Y,
        B,
        $,
        X,
        U = [].slice,
        K = {}.hasOwnProperty,
        V = function (t, e) {
            for (var i in e) K.call(e, i) && (t[i] = e[i]);
            function n() {
                this.constructor = t;
            }
            return (n.prototype = e.prototype), (t.prototype = new n()), (t.__super__ = e.prototype), t;
        },
        G =
            [].indexOf ||
            function (t) {
                for (var e = 0, i = this.length; e < i; e++) if (e in this && this[e] === t) return e;
                return -1;
            };
    function Q() {}
    for (
        _ = {
            className: "",
            catchupTime: 100,
            initialRate: 0.03,
            minTime: 250,
            ghostTime: 100,
            maxProgressPerFrame: 20,
            easeFactor: 1.25,
            startOnPageLoad: !0,
            restartOnPushState: !0,
            restartOnRequestAfter: 500,
            target: "body",
            elements: { checkInterval: 100, selectors: ["body"] },
            eventLag: { minSamples: 10, sampleCount: 3, lagThreshold: 3 },
            ajax: { trackMethods: ["GET"], trackWebSockets: !0, ignoreURLs: [] },
        },
            D = function () {
                var t;
                return null != (t = "undefined" != typeof performance && null !== performance && "function" == typeof performance.now ? performance.now() : void 0) ? t : +new Date();
            },
            A = window.requestAnimationFrame || window.mozRequestAnimationFrame || window.webkitRequestAnimationFrame || window.msRequestAnimationFrame,
            v = window.cancelAnimationFrame || window.mozCancelAnimationFrame,
            d = function (t, e, i) {
                if ("function" == typeof t.addEventListener) return t.addEventListener(e, i, !1);
                var n;
                "function" != typeof t["on" + e] || "object" != typeof t["on" + e].eventListeners
                    ? ((n = new s()),
                      "function" == typeof t["on" + e] && n.on(e, t["on" + e]),
                      (t["on" + e] = function (t) {
                          return n.trigger(e, t);
                      }),
                      (t["on" + e].eventListeners = n))
                    : (n = t["on" + e].eventListeners),
                    n.on(e, i);
            },
            null == A &&
                ((A = function (t) {
                    return setTimeout(t, 50);
                }),
                (v = function (t) {
                    return clearTimeout(t);
                })),
            I = function (t) {
                var e = D(),
                    i = function () {
                        var n = D() - e;
                        return 33 <= n
                            ? ((e = D()),
                              t(n, function () {
                                  return A(i);
                              }))
                            : setTimeout(i, 33 - n);
                    };
                return i();
            },
            E = function () {
                var t = arguments[0],
                    e = arguments[1],
                    i = 3 <= arguments.length ? U.call(arguments, 2) : [];
                return "function" == typeof t[e] ? t[e].apply(t, i) : t[e];
            },
            b = function () {
                for (var t, e, i, n = arguments[0], s = 2 <= arguments.length ? U.call(arguments, 1) : [], o = 0, r = s.length; o < r; o++)
                    if ((e = s[o])) for (t in e) K.call(e, t) && ((i = e[t]), null != n[t] && "object" == typeof n[t] && null != i && "object" == typeof i ? b(n[t], i) : (n[t] = i));
                return n;
            },
            f = function (t) {
                for (var e, i, n = (e = 0), s = 0, o = t.length; s < o; s++) (i = t[s]), (n += Math.abs(i)), e++;
                return n / e;
            },
            w = function (t, e) {
                var i, n;
                if ((null == t && (t = "options"), null == e && (e = !0), (n = document.querySelector("[data-pace-" + t + "]")))) {
                    if (((i = n.getAttribute("data-pace-" + t)), !e)) return i;
                    try {
                        return JSON.parse(i);
                    } catch (t) {
                        return "undefined" != typeof console && null !== console ? console.error("Error parsing inline pace options", t) : void 0;
                    }
                }
            },
            Q.prototype.on = function (t, e, i, n) {
                var s;
                return null == n && (n = !1), null == this.bindings && (this.bindings = {}), null == (s = this.bindings)[t] && (s[t] = []), this.bindings[t].push({ handler: e, ctx: i, once: n });
            },
            Q.prototype.once = function (t, e, i) {
                return this.on(t, e, i, !0);
            },
            Q.prototype.off = function (t, e) {
                var i, n, s;
                if (null != (null != (n = this.bindings) ? n[t] : void 0)) {
                    if (null == e) return delete this.bindings[t];
                    for (i = 0, s = []; i < this.bindings[t].length; ) this.bindings[t][i].handler === e ? s.push(this.bindings[t].splice(i, 1)) : s.push(i++);
                    return s;
                }
            },
            Q.prototype.trigger = function () {
                var t,
                    e,
                    i,
                    n,
                    s,
                    o,
                    r = arguments[0],
                    a = 2 <= arguments.length ? U.call(arguments, 1) : [];
                if (null != (n = this.bindings) && n[r]) {
                    for (i = 0, o = []; i < this.bindings[r].length; ) (e = (s = this.bindings[r][i]).handler), (t = s.ctx), (s = s.once), e.apply(null != t ? t : this, a), s ? o.push(this.bindings[r].splice(i, 1)) : o.push(i++);
                    return o;
                }
            },
            X = Q,
            r = window.Pace || {},
            window.Pace = r,
            b(r, X.prototype),
            S = r.options = b({}, _, window.paceOptions, w()),
            j = 0,
            q = (B = ["ajax", "document", "eventLag", "elements"]).length;
        j < q;
        j++
    )
        !0 === S[(H = B[j])] && (S[H] = _[H]);
    function J() {
        return J.__super__.constructor.apply(this, arguments);
    }
    function Z() {
        this.progress = 0;
    }
    function tt() {
        this.bindings = {};
    }
    function et() {
        var t,
            e = this;
        et.__super__.constructor.apply(this, arguments),
            (t = function (t) {
                var i = t.open;
                return (t.open = function (n, s, o) {
                    return O(n) && e.trigger("request", { type: n, url: s, request: t }), i.apply(t, arguments);
                });
            }),
            (window.XMLHttpRequest = function (e) {
                return (e = new z(e)), t(e), e;
            });
        try {
            y(window.XMLHttpRequest, z);
        } catch (t) {}
        if (null != R) {
            window.XDomainRequest = function () {
                var e = new R();
                return t(e), e;
            };
            try {
                y(window.XDomainRequest, R);
            } catch (t) {}
        }
        if (null != W && S.ajax.trackWebSockets) {
            window.WebSocket = function (t, i) {
                var n = null != i ? new W(t, i) : new W(t);
                return O("socket") && e.trigger("request", { type: "socket", url: t, protocols: i, request: n }), n;
            };
            try {
                y(window.WebSocket, W);
            } catch (t) {}
        }
    }
    function it() {
        this.complete = t(this.complete, this);
        var e = this;
        (this.elements = []),
            x().on("request", function () {
                return e.watch.apply(e, arguments);
            });
    }
    function nt(e) {
        var i, s, o, r;
        for (null == e && (e = {}), this.complete = t(this.complete, this), this.elements = [], null == e.selectors && (e.selectors = []), s = 0, o = (r = e.selectors).length; s < o; s++)
            (i = r[s]), this.elements.push(new n(i, this.complete));
    }
    function st(t, e) {
        (this.selector = t), (this.completeCallback = e), (this.progress = 0), this.check();
    }
    function ot() {
        var t,
            e,
            i = this;
        (this.progress = null != (e = this.states[document.readyState]) ? e : 100),
            (t = document.onreadystatechange),
            (document.onreadystatechange = function () {
                return null != i.states[document.readyState] && (i.progress = i.states[document.readyState]), "function" == typeof t ? t.apply(null, arguments) : void 0;
            });
    }
    function rt(t) {
        (this.source = t), (this.last = this.sinceLastUpdate = 0), (this.rate = S.initialRate), (this.catchup = 0), (this.progress = this.lastProgress = 0), null != this.source && (this.progress = E(this.source, "progress"));
    }
    V(J, (X = Error)),
        (o = J),
        (Z.prototype.getElement = function () {
            var t;
            if (null == this.el) {
                if (!(t = document.querySelector(S.target))) throw new o();
                (this.el = document.createElement("div")), (this.el.className = "pace pace-active"), (document.body.className = document.body.className.replace(/(pace-done )|/, "pace-running "));
                var e = "" !== S.className ? " " + S.className : "";
                (this.el.innerHTML = '<div class="pace-progress' + e + '">\n  <div class="pace-progress-inner"></div>\n</div>\n<div class="pace-activity"></div>'),
                    null != t.firstChild ? t.insertBefore(this.el, t.firstChild) : t.appendChild(this.el);
            }
            return this.el;
        }),
        (Z.prototype.finish = function () {
            var t = this.getElement();
            return (t.className = t.className.replace("pace-active", "pace-inactive")), (document.body.className = document.body.className.replace("pace-running ", "pace-done "));
        }),
        (Z.prototype.update = function (t) {
            return (this.progress = t), r.trigger("progress", t), this.render();
        }),
        (Z.prototype.destroy = function () {
            try {
                this.getElement().parentNode.removeChild(this.getElement());
            } catch (t) {
                o = t;
            }
            return (this.el = void 0);
        }),
        (Z.prototype.render = function () {
            var t, e, i, n, s, o, a;
            if (null == document.querySelector(S.target)) return !1;
            for (t = this.getElement(), n = "translate3d(" + this.progress + "%, 0, 0)", s = 0, o = (a = ["webkitTransform", "msTransform", "transform"]).length; s < o; s++) (e = a[s]), (t.children[0].style[e] = n);
            return (
                (!this.lastRenderedProgress || this.lastRenderedProgress | (0 !== this.progress) | 0) &&
                    (t.children[0].setAttribute("data-progress-text", (0 | this.progress) + "%"),
                    100 <= this.progress ? (i = "99") : ((i = this.progress < 10 ? "0" : ""), (i += 0 | this.progress)),
                    t.children[0].setAttribute("data-progress", "" + i)),
                r.trigger("change", this.progress),
                (this.lastRenderedProgress = this.progress)
            );
        }),
        (Z.prototype.done = function () {
            return 100 <= this.progress;
        }),
        (i = Z),
        (tt.prototype.trigger = function (t, e) {
            var i, n, s, o, r;
            if (null != this.bindings[t]) {
                for (r = [], n = 0, s = (o = this.bindings[t]).length; n < s; n++) (i = o[n]), r.push(i.call(this, e));
                return r;
            }
        }),
        (tt.prototype.on = function (t, e) {
            var i;
            return null == (i = this.bindings)[t] && (i[t] = []), this.bindings[t].push(e);
        }),
        (s = tt),
        (z = window.XMLHttpRequest),
        (R = window.XDomainRequest),
        (W = window.WebSocket),
        (y = function (t, e) {
            var i,
                n = [];
            for (i in e.prototype)
                try {
                    null == t[i] && "function" != typeof e[i]
                        ? "function" == typeof Object.defineProperty
                            ? n.push(
                                  Object.defineProperty(t, i, {
                                      get: (function (t) {
                                          return function () {
                                              return e.prototype[t];
                                          };
                                      })(i),
                                      configurable: !0,
                                      enumerable: !0,
                                  })
                              )
                            : n.push((t[i] = e.prototype[i]))
                        : n.push(void 0);
                } catch (t) {}
            return n;
        }),
        (C = []),
        (r.ignore = function () {
            var t = arguments[0],
                e = 2 <= arguments.length ? U.call(arguments, 1) : [];
            return C.unshift("ignore"), (e = t.apply(null, e)), C.shift(), e;
        }),
        (r.track = function () {
            var t = arguments[0],
                e = 2 <= arguments.length ? U.call(arguments, 1) : [];
            return C.unshift("track"), (e = t.apply(null, e)), C.shift(), e;
        }),
        (O = function (t) {
            if ((null == t && (t = "GET"), "track" === C[0])) return "force";
            if (!C.length && S.ajax) {
                if ("socket" === t && S.ajax.trackWebSockets) return !0;
                if (((t = t.toUpperCase()), 0 <= G.call(S.ajax.trackMethods, t))) return !0;
            }
            return !1;
        }),
        V(et, s),
        (a = et),
        (F = null),
        (M = function (t) {
            for (var e, i = S.ajax.ignoreURLs, n = 0, s = i.length; n < s; n++)
                if ("string" == typeof (e = i[n])) {
                    if (-1 !== t.indexOf(e)) return !0;
                } else if (e.test(t)) return !0;
            return !1;
        }),
        (x = function () {
            return (F = null == F ? new a() : F);
        })().on("request", function (t) {
            var i,
                n = t.type,
                s = t.request,
                o = t.url;
            if (!M(o))
                return r.running || (!1 === S.restartOnRequestAfter && "force" !== O(n))
                    ? void 0
                    : ((i = arguments),
                      "boolean" == typeof (o = S.restartOnRequestAfter || 0) && (o = 0),
                      setTimeout(function () {
                          var t,
                              o,
                              a,
                              l,
                              h = "socket" === n ? s.readyState < 1 : 0 < (h = s.readyState) && h < 4;
                          if (h) {
                              for (r.restart(), l = [], t = 0, o = (a = r.sources).length; t < o; t++) {
                                  if ((H = a[t]) instanceof e) {
                                      H.watch.apply(H, i);
                                      break;
                                  }
                                  l.push(void 0);
                              }
                              return l;
                          }
                      }, o));
        }),
        (it.prototype.watch = function (t) {
            var e = t.type,
                i = t.request;
            t = t.url;
            if (!M(t)) return (i = new ("socket" === e ? c : u)(i, this.complete)), this.elements.push(i);
        }),
        (it.prototype.complete = function (t) {
            return (this.elements = this.elements.filter(function (e) {
                return e !== t;
            }));
        }),
        (e = it),
        (u = function (t, e) {
            var i,
                n,
                s,
                o,
                r = this;
            if (((this.progress = 0), null != window.ProgressEvent))
                for (
                    d(t, "progress", function (t) {
                        return t.lengthComputable ? (r.progress = (100 * t.loaded) / t.total) : (r.progress = r.progress + (100 - r.progress) / 2);
                    }),
                        i = 0,
                        n = (o = ["load", "abort", "timeout", "error"]).length;
                    i < n;
                    i++
                )
                    d(t, o[i], function () {
                        return e(r), (r.progress = 100);
                    });
            else
                (s = t.onreadystatechange),
                    (t.onreadystatechange = function () {
                        var i;
                        return 0 === (i = t.readyState) || 4 === i ? (e(r), (r.progress = 100)) : 3 === t.readyState && (r.progress = 50), "function" == typeof s ? s.apply(null, arguments) : void 0;
                    });
        }),
        (c = function (t, e) {
            for (var i, n = this, s = (this.progress = 0), o = (i = ["error", "open"]).length; s < o; s++)
                d(t, i[s], function () {
                    return e(n), (n.progress = 100);
                });
        }),
        (nt.prototype.complete = function (t) {
            return (this.elements = this.elements.filter(function (e) {
                return e !== t;
            }));
        }),
        (w = nt),
        (st.prototype.check = function () {
            var t = this;
            return document.querySelector(this.selector)
                ? this.done()
                : setTimeout(function () {
                      return t.check();
                  }, S.elements.checkInterval);
        }),
        (st.prototype.done = function () {
            return this.completeCallback(this), (this.completeCallback = null), (this.progress = 100);
        }),
        (n = st),
        (ot.prototype.states = { loading: 0, interactive: 50, complete: 100 }),
        (X = ot),
        (V = function () {
            var t,
                e,
                i,
                n,
                s,
                o = this;
            (this.progress = 0),
                (s = []),
                (n = 0),
                (i = D()),
                (e = setInterval(function () {
                    var r = D() - i - 50;
                    return (
                        (i = D()),
                        s.push(r),
                        s.length > S.eventLag.sampleCount && s.shift(),
                        (t = f(s)),
                        ++n >= S.eventLag.minSamples && t < S.eventLag.lagThreshold ? ((o.progress = 100), clearInterval(e)) : (o.progress = (3 / (t + 3)) * 100)
                    );
                }, 50));
        }),
        (rt.prototype.tick = function (t, e) {
            return (
                100 <= (e = null == e ? E(this.source, "progress") : e) && (this.done = !0),
                e === this.last
                    ? (this.sinceLastUpdate += t)
                    : (this.sinceLastUpdate && (this.rate = (e - this.last) / this.sinceLastUpdate), (this.catchup = (e - this.progress) / S.catchupTime), (this.sinceLastUpdate = 0), (this.last = e)),
                e > this.progress && (this.progress += this.catchup * t),
                (e = 1 - Math.pow(this.progress / 100, S.easeFactor)),
                (this.progress += e * this.rate * t),
                (this.progress = Math.min(this.lastProgress + S.maxProgressPerFrame, this.progress)),
                (this.progress = Math.max(0, this.progress)),
                (this.progress = Math.min(100, this.progress)),
                (this.lastProgress = this.progress),
                this.progress
            );
        }),
        (h = rt),
        (m = p = N = g = P = L = null),
        (r.running = !1),
        (k = function () {
            if (S.restartOnPushState) return r.restart();
        }),
        null != window.history.pushState &&
            ((Y = window.history.pushState),
            (window.history.pushState = function () {
                return k(), Y.apply(window.history, arguments);
            })),
        null != window.history.replaceState &&
            (($ = window.history.replaceState),
            (window.history.replaceState = function () {
                return k(), $.apply(window.history, arguments);
            })),
        (l = { ajax: e, elements: w, document: X, eventLag: V }),
        (T = function () {
            var t, e, n, s, o, a, c, u;
            for (r.sources = L = [], e = 0, s = (a = ["ajax", "elements", "document", "eventLag"]).length; e < s; e++) !1 !== S[(t = a[e])] && L.push(new l[t](S[t]));
            for (n = 0, o = (u = null != (c = S.extraSources) ? c : []).length; n < o; n++) (H = u[n]), L.push(new H(S));
            return (r.bar = g = new i()), (P = []), (N = new h());
        })(),
        (r.stop = function () {
            return r.trigger("stop"), (r.running = !1), g.destroy(), (m = !0), null != p && ("function" == typeof v && v(p), (p = null)), T();
        }),
        (r.restart = function () {
            return r.trigger("restart"), r.stop(), r.start();
        }),
        (r.go = function () {
            var t;
            return (
                (r.running = !0),
                g.render(),
                (t = D()),
                (m = !1),
                (p = I(function (e, i) {
                    g.progress;
                    for (var n, s, o, a, l, c, u, d, p, f, v = (c = 0), _ = !0, b = (u = 0), y = L.length; u < y; b = ++u)
                        for (H = L[b], l = null != P[b] ? P[b] : (P[b] = []), o = d = 0, p = (s = null != (f = H.elements) ? f : [H]).length; d < p; o = ++d)
                            (a = s[o]), (_ &= (a = null != l[o] ? l[o] : (l[o] = new h(a))).done), a.done || (v++, (c += a.tick(e)));
                    return (
                        (n = c / v),
                        g.update(N.tick(e, n)),
                        g.done() || _ || m
                            ? (g.update(100),
                              r.trigger("done"),
                              setTimeout(function () {
                                  return g.finish(), (r.running = !1), r.trigger("hide");
                              }, Math.max(S.ghostTime, Math.max(S.minTime - (D() - t), 0))))
                            : i()
                    );
                }))
            );
        }),
        (r.start = function (t) {
            b(S, t), (r.running = !0);
            try {
                g.render();
            } catch (t) {
                o = t;
            }
            return document.querySelector(".pace") ? (r.trigger("start"), r.go()) : setTimeout(r.start, 50);
        }),
        "function" == typeof define && define.amd
            ? define(function () {
                  return r;
              })
            : "object" == typeof exports
            ? (module.exports = r)
            : S.startOnPageLoad && r.start();
}.call(this),
    (function (t, e) {
        "use strict";
        "object" == typeof module && "object" == typeof module.exports
            ? (module.exports = t.document
                  ? e(t, !0)
                  : function (t) {
                        if (!t.document) throw new Error("jQuery requires a window with a document");
                        return e(t);
                    })
            : e(t);
    })("undefined" != typeof window ? window : this, function (t, e) {
        "use strict";
        var i = [],
            n = Object.getPrototypeOf,
            s = i.slice,
            o = i.flat
                ? function (t) {
                      return i.flat.call(t);
                  }
                : function (t) {
                      return i.concat.apply([], t);
                  },
            r = i.push,
            a = i.indexOf,
            l = {},
            h = l.toString,
            c = l.hasOwnProperty,
            u = c.toString,
            d = u.call(Object),
            p = {},
            f = function (t) {
                return "function" == typeof t && "number" != typeof t.nodeType && "function" != typeof t.item;
            },
            g = function (t) {
                return null != t && t === t.window;
            },
            m = t.document,
            v = { type: !0, src: !0, nonce: !0, noModule: !0 };
        function _(t, e, i) {
            var n,
                s,
                o = (i = i || m).createElement("script");
            if (((o.text = t), e)) for (n in v) (s = e[n] || (e.getAttribute && e.getAttribute(n))) && o.setAttribute(n, s);
            i.head.appendChild(o).parentNode.removeChild(o);
        }
        function b(t) {
            return null == t ? t + "" : "object" == typeof t || "function" == typeof t ? l[h.call(t)] || "object" : typeof t;
        }
        var y = "3.7.1",
            w = /HTML$/i,
            x = function (t, e) {
                return new x.fn.init(t, e);
            };
        function k(t) {
            var e = !!t && "length" in t && t.length,
                i = b(t);
            return !f(t) && !g(t) && ("array" === i || 0 === e || ("number" == typeof e && 0 < e && e - 1 in t));
        }
        function C(t, e) {
            return t.nodeName && t.nodeName.toLowerCase() === e.toLowerCase();
        }
        (x.fn = x.prototype = {
            jquery: y,
            constructor: x,
            length: 0,
            toArray: function () {
                return s.call(this);
            },
            get: function (t) {
                return null == t ? s.call(this) : t < 0 ? this[t + this.length] : this[t];
            },
            pushStack: function (t) {
                var e = x.merge(this.constructor(), t);
                return (e.prevObject = this), e;
            },
            each: function (t) {
                return x.each(this, t);
            },
            map: function (t) {
                return this.pushStack(
                    x.map(this, function (e, i) {
                        return t.call(e, i, e);
                    })
                );
            },
            slice: function () {
                return this.pushStack(s.apply(this, arguments));
            },
            first: function () {
                return this.eq(0);
            },
            last: function () {
                return this.eq(-1);
            },
            even: function () {
                return this.pushStack(
                    x.grep(this, function (t, e) {
                        return (e + 1) % 2;
                    })
                );
            },
            odd: function () {
                return this.pushStack(
                    x.grep(this, function (t, e) {
                        return e % 2;
                    })
                );
            },
            eq: function (t) {
                var e = this.length,
                    i = +t + (t < 0 ? e : 0);
                return this.pushStack(0 <= i && i < e ? [this[i]] : []);
            },
            end: function () {
                return this.prevObject || this.constructor();
            },
            push: r,
            sort: i.sort,
            splice: i.splice,
        }),
            (x.extend = x.fn.extend = function () {
                var t,
                    e,
                    i,
                    n,
                    s,
                    o,
                    r = arguments[0] || {},
                    a = 1,
                    l = arguments.length,
                    h = !1;
                for ("boolean" == typeof r && ((h = r), (r = arguments[a] || {}), a++), "object" == typeof r || f(r) || (r = {}), a === l && ((r = this), a--); a < l; a++)
                    if (null != (t = arguments[a]))
                        for (e in t)
                            (n = t[e]),
                                "__proto__" !== e &&
                                    r !== n &&
                                    (h && n && (x.isPlainObject(n) || (s = Array.isArray(n)))
                                        ? ((i = r[e]), (o = s && !Array.isArray(i) ? [] : s || x.isPlainObject(i) ? i : {}), (s = !1), (r[e] = x.extend(h, o, n)))
                                        : void 0 !== n && (r[e] = n));
                return r;
            }),
            x.extend({
                expando: "jQuery" + (y + Math.random()).replace(/\D/g, ""),
                isReady: !0,
                error: function (t) {
                    throw new Error(t);
                },
                noop: function () {},
                isPlainObject: function (t) {
                    var e, i;
                    return !(!t || "[object Object]" !== h.call(t) || ((e = n(t)) && ("function" != typeof (i = c.call(e, "constructor") && e.constructor) || u.call(i) !== d)));
                },
                isEmptyObject: function (t) {
                    var e;
                    for (e in t) return !1;
                    return !0;
                },
                globalEval: function (t, e, i) {
                    _(t, { nonce: e && e.nonce }, i);
                },
                each: function (t, e) {
                    var i,
                        n = 0;
                    if (k(t)) for (i = t.length; n < i && !1 !== e.call(t[n], n, t[n]); n++);
                    else for (n in t) if (!1 === e.call(t[n], n, t[n])) break;
                    return t;
                },
                text: function (t) {
                    var e,
                        i = "",
                        n = 0,
                        s = t.nodeType;
                    if (!s) for (; (e = t[n++]); ) i += x.text(e);
                    return 1 === s || 11 === s ? t.textContent : 9 === s ? t.documentElement.textContent : 3 === s || 4 === s ? t.nodeValue : i;
                },
                makeArray: function (t, e) {
                    var i = e || [];
                    return null != t && (k(Object(t)) ? x.merge(i, "string" == typeof t ? [t] : t) : r.call(i, t)), i;
                },
                inArray: function (t, e, i) {
                    return null == e ? -1 : a.call(e, t, i);
                },
                isXMLDoc: function (t) {
                    var e = t && t.namespaceURI,
                        i = t && (t.ownerDocument || t).documentElement;
                    return !w.test(e || (i && i.nodeName) || "HTML");
                },
                merge: function (t, e) {
                    for (var i = +e.length, n = 0, s = t.length; n < i; n++) t[s++] = e[n];
                    return (t.length = s), t;
                },
                grep: function (t, e, i) {
                    for (var n = [], s = 0, o = t.length, r = !i; s < o; s++) !e(t[s], s) !== r && n.push(t[s]);
                    return n;
                },
                map: function (t, e, i) {
                    var n,
                        s,
                        r = 0,
                        a = [];
                    if (k(t)) for (n = t.length; r < n; r++) null != (s = e(t[r], r, i)) && a.push(s);
                    else for (r in t) null != (s = e(t[r], r, i)) && a.push(s);
                    return o(a);
                },
                guid: 1,
                support: p,
            }),
            "function" == typeof Symbol && (x.fn[Symbol.iterator] = i[Symbol.iterator]),
            x.each("Boolean Number String Function Array Date RegExp Object Error Symbol".split(" "), function (t, e) {
                l["[object " + e + "]"] = e.toLowerCase();
            });
        var T = i.pop,
            D = i.sort,
            S = i.splice,
            A = "[\\x20\\t\\r\\n\\f]",
            E = new RegExp("^" + A + "+|((?:^|[^\\\\])(?:\\\\.)*)" + A + "+$", "g");
        x.contains = function (t, e) {
            var i = e && e.parentNode;
            return t === i || !(!i || 1 !== i.nodeType || !(t.contains ? t.contains(i) : t.compareDocumentPosition && 16 & t.compareDocumentPosition(i)));
        };
        var I = /([\0-\x1f\x7f]|^-?\d)|^-$|[^\x80-\uFFFF\w-]/g;
        function P(t, e) {
            return e ? ("\0" === t ? "�" : t.slice(0, -1) + "\\" + t.charCodeAt(t.length - 1).toString(16) + " ") : "\\" + t;
        }
        x.escapeSelector = function (t) {
            return (t + "").replace(I, P);
        };
        var M = m,
            O = r;
        !(function () {
            var e,
                n,
                o,
                r,
                l,
                h,
                u,
                d,
                f,
                g,
                m = O,
                v = x.expando,
                _ = 0,
                b = 0,
                y = tt(),
                w = tt(),
                k = tt(),
                I = tt(),
                P = function (t, e) {
                    return t === e && (l = !0), 0;
                },
                H = "checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped",
                L = "(?:\\\\[\\da-fA-F]{1,6}" + A + "?|\\\\[^\\r\\n\\f]|[\\w-]|[^\0-\\x7f])+",
                N = "\\[" + A + "*(" + L + ")(?:" + A + "*([*^$|!~]?=)" + A + "*(?:'((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\"|(" + L + "))|)" + A + "*\\]",
                W = ":(" + L + ")(?:\\((('((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\")|((?:\\\\.|[^\\\\()[\\]]|" + N + ")*)|.*)\\)|)",
                R = new RegExp(A + "+", "g"),
                z = new RegExp("^" + A + "*," + A + "*"),
                j = new RegExp("^" + A + "*([>+~]|" + A + ")" + A + "*"),
                F = new RegExp(A + "|>"),
                q = new RegExp(W),
                Y = new RegExp("^" + L + "$"),
                B = {
                    ID: new RegExp("^#(" + L + ")"),
                    CLASS: new RegExp("^\\.(" + L + ")"),
                    TAG: new RegExp("^(" + L + "|[*])"),
                    ATTR: new RegExp("^" + N),
                    PSEUDO: new RegExp("^" + W),
                    CHILD: new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\(" + A + "*(even|odd|(([+-]|)(\\d*)n|)" + A + "*(?:([+-]|)" + A + "*(\\d+)|))" + A + "*\\)|)", "i"),
                    bool: new RegExp("^(?:" + H + ")$", "i"),
                    needsContext: new RegExp("^" + A + "*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\(" + A + "*((?:-\\d)?\\d*)" + A + "*\\)|)(?=[^-]|$)", "i"),
                },
                $ = /^(?:input|select|textarea|button)$/i,
                X = /^h\d$/i,
                U = /^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/,
                K = /[+~]/,
                V = new RegExp("\\\\[\\da-fA-F]{1,6}" + A + "?|\\\\([^\\r\\n\\f])", "g"),
                G = function (t, e) {
                    var i = "0x" + t.slice(1) - 65536;
                    return e || (i < 0 ? String.fromCharCode(i + 65536) : String.fromCharCode((i >> 10) | 55296, (1023 & i) | 56320));
                },
                Q = function () {
                    lt();
                },
                J = dt(
                    function (t) {
                        return !0 === t.disabled && C(t, "fieldset");
                    },
                    { dir: "parentNode", next: "legend" }
                );
            try {
                m.apply((i = s.call(M.childNodes)), M.childNodes), i[M.childNodes.length].nodeType;
            } catch (e) {
                m = {
                    apply: function (t, e) {
                        O.apply(t, s.call(e));
                    },
                    call: function (t) {
                        O.apply(t, s.call(arguments, 1));
                    },
                };
            }
            function Z(t, e, i, n) {
                var s,
                    o,
                    r,
                    a,
                    l,
                    c,
                    u,
                    g = e && e.ownerDocument,
                    _ = e ? e.nodeType : 9;
                if (((i = i || []), "string" != typeof t || !t || (1 !== _ && 9 !== _ && 11 !== _))) return i;
                if (!n && (lt(e), (e = e || h), d)) {
                    if (11 !== _ && (l = U.exec(t)))
                        if ((s = l[1])) {
                            if (9 === _) {
                                if (!(r = e.getElementById(s))) return i;
                                if (r.id === s) return m.call(i, r), i;
                            } else if (g && (r = g.getElementById(s)) && Z.contains(e, r) && r.id === s) return m.call(i, r), i;
                        } else {
                            if (l[2]) return m.apply(i, e.getElementsByTagName(t)), i;
                            if ((s = l[3]) && e.getElementsByClassName) return m.apply(i, e.getElementsByClassName(s)), i;
                        }
                    if (!(I[t + " "] || (f && f.test(t)))) {
                        if (((u = t), (g = e), 1 === _ && (F.test(t) || j.test(t)))) {
                            for (((g = (K.test(t) && at(e.parentNode)) || e) == e && p.scope) || ((a = e.getAttribute("id")) ? (a = x.escapeSelector(a)) : e.setAttribute("id", (a = v))), o = (c = ct(t)).length; o--; )
                                c[o] = (a ? "#" + a : ":scope") + " " + ut(c[o]);
                            u = c.join(",");
                        }
                        try {
                            return m.apply(i, g.querySelectorAll(u)), i;
                        } catch (e) {
                            I(t, !0);
                        } finally {
                            a === v && e.removeAttribute("id");
                        }
                    }
                }
                return _t(t.replace(E, "$1"), e, i, n);
            }
            function tt() {
                var t = [];
                return function e(i, s) {
                    return t.push(i + " ") > n.cacheLength && delete e[t.shift()], (e[i + " "] = s);
                };
            }
            function et(t) {
                return (t[v] = !0), t;
            }
            function it(t) {
                var e = h.createElement("fieldset");
                try {
                    return !!t(e);
                } catch (t) {
                    return !1;
                } finally {
                    e.parentNode && e.parentNode.removeChild(e), (e = null);
                }
            }
            function nt(t) {
                return function (e) {
                    return C(e, "input") && e.type === t;
                };
            }
            function st(t) {
                return function (e) {
                    return (C(e, "input") || C(e, "button")) && e.type === t;
                };
            }
            function ot(t) {
                return function (e) {
                    return "form" in e
                        ? e.parentNode && !1 === e.disabled
                            ? "label" in e
                                ? "label" in e.parentNode
                                    ? e.parentNode.disabled === t
                                    : e.disabled === t
                                : e.isDisabled === t || (e.isDisabled !== !t && J(e) === t)
                            : e.disabled === t
                        : "label" in e && e.disabled === t;
                };
            }
            function rt(t) {
                return et(function (e) {
                    return (
                        (e = +e),
                        et(function (i, n) {
                            for (var s, o = t([], i.length, e), r = o.length; r--; ) i[(s = o[r])] && (i[s] = !(n[s] = i[s]));
                        })
                    );
                });
            }
            function at(t) {
                return t && void 0 !== t.getElementsByTagName && t;
            }
            function lt(t) {
                var e,
                    i = t ? t.ownerDocument || t : M;
                return (
                    i != h &&
                        9 === i.nodeType &&
                        i.documentElement &&
                        ((u = (h = i).documentElement),
                        (d = !x.isXMLDoc(h)),
                        (g = u.matches || u.webkitMatchesSelector || u.msMatchesSelector),
                        u.msMatchesSelector && M != h && (e = h.defaultView) && e.top !== e && e.addEventListener("unload", Q),
                        (p.getById = it(function (t) {
                            return (u.appendChild(t).id = x.expando), !h.getElementsByName || !h.getElementsByName(x.expando).length;
                        })),
                        (p.disconnectedMatch = it(function (t) {
                            return g.call(t, "*");
                        })),
                        (p.scope = it(function () {
                            return h.querySelectorAll(":scope");
                        })),
                        (p.cssHas = it(function () {
                            try {
                                return h.querySelector(":has(*,:jqfake)"), !1;
                            } catch (t) {
                                return !0;
                            }
                        })),
                        p.getById
                            ? ((n.filter.ID = function (t) {
                                  var e = t.replace(V, G);
                                  return function (t) {
                                      return t.getAttribute("id") === e;
                                  };
                              }),
                              (n.find.ID = function (t, e) {
                                  if (void 0 !== e.getElementById && d) {
                                      var i = e.getElementById(t);
                                      return i ? [i] : [];
                                  }
                              }))
                            : ((n.filter.ID = function (t) {
                                  var e = t.replace(V, G);
                                  return function (t) {
                                      var i = void 0 !== t.getAttributeNode && t.getAttributeNode("id");
                                      return i && i.value === e;
                                  };
                              }),
                              (n.find.ID = function (t, e) {
                                  if (void 0 !== e.getElementById && d) {
                                      var i,
                                          n,
                                          s,
                                          o = e.getElementById(t);
                                      if (o) {
                                          if ((i = o.getAttributeNode("id")) && i.value === t) return [o];
                                          for (s = e.getElementsByName(t), n = 0; (o = s[n++]); ) if ((i = o.getAttributeNode("id")) && i.value === t) return [o];
                                      }
                                      return [];
                                  }
                              })),
                        (n.find.TAG = function (t, e) {
                            return void 0 !== e.getElementsByTagName ? e.getElementsByTagName(t) : e.querySelectorAll(t);
                        }),
                        (n.find.CLASS = function (t, e) {
                            if (void 0 !== e.getElementsByClassName && d) return e.getElementsByClassName(t);
                        }),
                        (f = []),
                        it(function (t) {
                            var e;
                            (u.appendChild(t).innerHTML = "<a id='" + v + "' href='' disabled='disabled'></a><select id='" + v + "-\r\\' disabled='disabled'><option selected=''></option></select>"),
                                t.querySelectorAll("[selected]").length || f.push("\\[" + A + "*(?:value|" + H + ")"),
                                t.querySelectorAll("[id~=" + v + "-]").length || f.push("~="),
                                t.querySelectorAll("a#" + v + "+*").length || f.push(".#.+[+~]"),
                                t.querySelectorAll(":checked").length || f.push(":checked"),
                                (e = h.createElement("input")).setAttribute("type", "hidden"),
                                t.appendChild(e).setAttribute("name", "D"),
                                (u.appendChild(t).disabled = !0),
                                2 !== t.querySelectorAll(":disabled").length && f.push(":enabled", ":disabled"),
                                (e = h.createElement("input")).setAttribute("name", ""),
                                t.appendChild(e),
                                t.querySelectorAll("[name='']").length || f.push("\\[" + A + "*name" + A + "*=" + A + "*(?:''|\"\")");
                        }),
                        p.cssHas || f.push(":has"),
                        (f = f.length && new RegExp(f.join("|"))),
                        (P = function (t, e) {
                            if (t === e) return (l = !0), 0;
                            var i = !t.compareDocumentPosition - !e.compareDocumentPosition;
                            return (
                                i ||
                                (1 & (i = (t.ownerDocument || t) == (e.ownerDocument || e) ? t.compareDocumentPosition(e) : 1) || (!p.sortDetached && e.compareDocumentPosition(t) === i)
                                    ? t === h || (t.ownerDocument == M && Z.contains(M, t))
                                        ? -1
                                        : e === h || (e.ownerDocument == M && Z.contains(M, e))
                                        ? 1
                                        : r
                                        ? a.call(r, t) - a.call(r, e)
                                        : 0
                                    : 4 & i
                                    ? -1
                                    : 1)
                            );
                        })),
                    h
                );
            }
            for (e in ((Z.matches = function (t, e) {
                return Z(t, null, null, e);
            }),
            (Z.matchesSelector = function (t, e) {
                if ((lt(t), d && !I[e + " "] && (!f || !f.test(e))))
                    try {
                        var i = g.call(t, e);
                        if (i || p.disconnectedMatch || (t.document && 11 !== t.document.nodeType)) return i;
                    } catch (t) {
                        I(e, !0);
                    }
                return 0 < Z(e, h, null, [t]).length;
            }),
            (Z.contains = function (t, e) {
                return (t.ownerDocument || t) != h && lt(t), x.contains(t, e);
            }),
            (Z.attr = function (t, e) {
                (t.ownerDocument || t) != h && lt(t);
                var i = n.attrHandle[e.toLowerCase()],
                    s = i && c.call(n.attrHandle, e.toLowerCase()) ? i(t, e, !d) : void 0;
                return void 0 !== s ? s : t.getAttribute(e);
            }),
            (Z.error = function (t) {
                throw new Error("Syntax error, unrecognized expression: " + t);
            }),
            (x.uniqueSort = function (t) {
                var e,
                    i = [],
                    n = 0,
                    o = 0;
                if (((l = !p.sortStable), (r = !p.sortStable && s.call(t, 0)), D.call(t, P), l)) {
                    for (; (e = t[o++]); ) e === t[o] && (n = i.push(o));
                    for (; n--; ) S.call(t, i[n], 1);
                }
                return (r = null), t;
            }),
            (x.fn.uniqueSort = function () {
                return this.pushStack(x.uniqueSort(s.apply(this)));
            }),
            ((n = x.expr = {
                cacheLength: 50,
                createPseudo: et,
                match: B,
                attrHandle: {},
                find: {},
                relative: { ">": { dir: "parentNode", first: !0 }, " ": { dir: "parentNode" }, "+": { dir: "previousSibling", first: !0 }, "~": { dir: "previousSibling" } },
                preFilter: {
                    ATTR: function (t) {
                        return (t[1] = t[1].replace(V, G)), (t[3] = (t[3] || t[4] || t[5] || "").replace(V, G)), "~=" === t[2] && (t[3] = " " + t[3] + " "), t.slice(0, 4);
                    },
                    CHILD: function (t) {
                        return (
                            (t[1] = t[1].toLowerCase()),
                            "nth" === t[1].slice(0, 3) ? (t[3] || Z.error(t[0]), (t[4] = +(t[4] ? t[5] + (t[6] || 1) : 2 * ("even" === t[3] || "odd" === t[3]))), (t[5] = +(t[7] + t[8] || "odd" === t[3]))) : t[3] && Z.error(t[0]),
                            t
                        );
                    },
                    PSEUDO: function (t) {
                        var e,
                            i = !t[6] && t[2];
                        return B.CHILD.test(t[0])
                            ? null
                            : (t[3] ? (t[2] = t[4] || t[5] || "") : i && q.test(i) && (e = ct(i, !0)) && (e = i.indexOf(")", i.length - e) - i.length) && ((t[0] = t[0].slice(0, e)), (t[2] = i.slice(0, e))), t.slice(0, 3));
                    },
                },
                filter: {
                    TAG: function (t) {
                        var e = t.replace(V, G).toLowerCase();
                        return "*" === t
                            ? function () {
                                  return !0;
                              }
                            : function (t) {
                                  return C(t, e);
                              };
                    },
                    CLASS: function (t) {
                        var e = y[t + " "];
                        return (
                            e ||
                            ((e = new RegExp("(^|" + A + ")" + t + "(" + A + "|$)")) &&
                                y(t, function (t) {
                                    return e.test(("string" == typeof t.className && t.className) || (void 0 !== t.getAttribute && t.getAttribute("class")) || "");
                                }))
                        );
                    },
                    ATTR: function (t, e, i) {
                        return function (n) {
                            var s = Z.attr(n, t);
                            return null == s
                                ? "!=" === e
                                : !e ||
                                      ((s += ""),
                                      "=" === e
                                          ? s === i
                                          : "!=" === e
                                          ? s !== i
                                          : "^=" === e
                                          ? i && 0 === s.indexOf(i)
                                          : "*=" === e
                                          ? i && -1 < s.indexOf(i)
                                          : "$=" === e
                                          ? i && s.slice(-i.length) === i
                                          : "~=" === e
                                          ? -1 < (" " + s.replace(R, " ") + " ").indexOf(i)
                                          : "|=" === e && (s === i || s.slice(0, i.length + 1) === i + "-"));
                        };
                    },
                    CHILD: function (t, e, i, n, s) {
                        var o = "nth" !== t.slice(0, 3),
                            r = "last" !== t.slice(-4),
                            a = "of-type" === e;
                        return 1 === n && 0 === s
                            ? function (t) {
                                  return !!t.parentNode;
                              }
                            : function (e, i, l) {
                                  var h,
                                      c,
                                      u,
                                      d,
                                      p,
                                      f = o !== r ? "nextSibling" : "previousSibling",
                                      g = e.parentNode,
                                      m = a && e.nodeName.toLowerCase(),
                                      b = !l && !a,
                                      y = !1;
                                  if (g) {
                                      if (o) {
                                          for (; f; ) {
                                              for (u = e; (u = u[f]); ) if (a ? C(u, m) : 1 === u.nodeType) return !1;
                                              p = f = "only" === t && !p && "nextSibling";
                                          }
                                          return !0;
                                      }
                                      if (((p = [r ? g.firstChild : g.lastChild]), r && b)) {
                                          for (y = (d = (h = (c = g[v] || (g[v] = {}))[t] || [])[0] === _ && h[1]) && h[2], u = d && g.childNodes[d]; (u = (++d && u && u[f]) || (y = d = 0) || p.pop()); )
                                              if (1 === u.nodeType && ++y && u === e) {
                                                  c[t] = [_, d, y];
                                                  break;
                                              }
                                      } else if ((b && (y = d = (h = (c = e[v] || (e[v] = {}))[t] || [])[0] === _ && h[1]), !1 === y))
                                          for (; (u = (++d && u && u[f]) || (y = d = 0) || p.pop()) && (!(a ? C(u, m) : 1 === u.nodeType) || !++y || (b && ((c = u[v] || (u[v] = {}))[t] = [_, y]), u !== e)); );
                                      return (y -= s) === n || (y % n == 0 && 0 <= y / n);
                                  }
                              };
                    },
                    PSEUDO: function (t, e) {
                        var i,
                            s = n.pseudos[t] || n.setFilters[t.toLowerCase()] || Z.error("unsupported pseudo: " + t);
                        return s[v]
                            ? s(e)
                            : 1 < s.length
                            ? ((i = [t, t, "", e]),
                              n.setFilters.hasOwnProperty(t.toLowerCase())
                                  ? et(function (t, i) {
                                        for (var n, o = s(t, e), r = o.length; r--; ) t[(n = a.call(t, o[r]))] = !(i[n] = o[r]);
                                    })
                                  : function (t) {
                                        return s(t, 0, i);
                                    })
                            : s;
                    },
                },
                pseudos: {
                    not: et(function (t) {
                        var e = [],
                            i = [],
                            n = vt(t.replace(E, "$1"));
                        return n[v]
                            ? et(function (t, e, i, s) {
                                  for (var o, r = n(t, null, s, []), a = t.length; a--; ) (o = r[a]) && (t[a] = !(e[a] = o));
                              })
                            : function (t, s, o) {
                                  return (e[0] = t), n(e, null, o, i), (e[0] = null), !i.pop();
                              };
                    }),
                    has: et(function (t) {
                        return function (e) {
                            return 0 < Z(t, e).length;
                        };
                    }),
                    contains: et(function (t) {
                        return (
                            (t = t.replace(V, G)),
                            function (e) {
                                return -1 < (e.textContent || x.text(e)).indexOf(t);
                            }
                        );
                    }),
                    lang: et(function (t) {
                        return (
                            Y.test(t || "") || Z.error("unsupported lang: " + t),
                            (t = t.replace(V, G).toLowerCase()),
                            function (e) {
                                var i;
                                do {
                                    if ((i = d ? e.lang : e.getAttribute("xml:lang") || e.getAttribute("lang"))) return (i = i.toLowerCase()) === t || 0 === i.indexOf(t + "-");
                                } while ((e = e.parentNode) && 1 === e.nodeType);
                                return !1;
                            }
                        );
                    }),
                    target: function (e) {
                        var i = t.location && t.location.hash;
                        return i && i.slice(1) === e.id;
                    },
                    root: function (t) {
                        return t === u;
                    },
                    focus: function (t) {
                        return (
                            t ===
                                (function () {
                                    try {
                                        return h.activeElement;
                                    } catch (t) {}
                                })() &&
                            h.hasFocus() &&
                            !!(t.type || t.href || ~t.tabIndex)
                        );
                    },
                    enabled: ot(!1),
                    disabled: ot(!0),
                    checked: function (t) {
                        return (C(t, "input") && !!t.checked) || (C(t, "option") && !!t.selected);
                    },
                    selected: function (t) {
                        return t.parentNode && t.parentNode.selectedIndex, !0 === t.selected;
                    },
                    empty: function (t) {
                        for (t = t.firstChild; t; t = t.nextSibling) if (t.nodeType < 6) return !1;
                        return !0;
                    },
                    parent: function (t) {
                        return !n.pseudos.empty(t);
                    },
                    header: function (t) {
                        return X.test(t.nodeName);
                    },
                    input: function (t) {
                        return $.test(t.nodeName);
                    },
                    button: function (t) {
                        return (C(t, "input") && "button" === t.type) || C(t, "button");
                    },
                    text: function (t) {
                        var e;
                        return C(t, "input") && "text" === t.type && (null == (e = t.getAttribute("type")) || "text" === e.toLowerCase());
                    },
                    first: rt(function () {
                        return [0];
                    }),
                    last: rt(function (t, e) {
                        return [e - 1];
                    }),
                    eq: rt(function (t, e, i) {
                        return [i < 0 ? i + e : i];
                    }),
                    even: rt(function (t, e) {
                        for (var i = 0; i < e; i += 2) t.push(i);
                        return t;
                    }),
                    odd: rt(function (t, e) {
                        for (var i = 1; i < e; i += 2) t.push(i);
                        return t;
                    }),
                    lt: rt(function (t, e, i) {
                        var n;
                        for (n = i < 0 ? i + e : e < i ? e : i; 0 <= --n; ) t.push(n);
                        return t;
                    }),
                    gt: rt(function (t, e, i) {
                        for (var n = i < 0 ? i + e : i; ++n < e; ) t.push(n);
                        return t;
                    }),
                },
            }).pseudos.nth = n.pseudos.eq),
            { radio: !0, checkbox: !0, file: !0, password: !0, image: !0 }))
                n.pseudos[e] = nt(e);
            for (e in { submit: !0, reset: !0 }) n.pseudos[e] = st(e);
            function ht() {}
            function ct(t, e) {
                var i,
                    s,
                    o,
                    r,
                    a,
                    l,
                    h,
                    c = w[t + " "];
                if (c) return e ? 0 : c.slice(0);
                for (a = t, l = [], h = n.preFilter; a; ) {
                    for (r in ((i && !(s = z.exec(a))) || (s && (a = a.slice(s[0].length) || a), l.push((o = []))),
                    (i = !1),
                    (s = j.exec(a)) && ((i = s.shift()), o.push({ value: i, type: s[0].replace(E, " ") }), (a = a.slice(i.length))),
                    n.filter))
                        !(s = B[r].exec(a)) || (h[r] && !(s = h[r](s))) || ((i = s.shift()), o.push({ value: i, type: r, matches: s }), (a = a.slice(i.length)));
                    if (!i) break;
                }
                return e ? a.length : a ? Z.error(t) : w(t, l).slice(0);
            }
            function ut(t) {
                for (var e = 0, i = t.length, n = ""; e < i; e++) n += t[e].value;
                return n;
            }
            function dt(t, e, i) {
                var n = e.dir,
                    s = e.next,
                    o = s || n,
                    r = i && "parentNode" === o,
                    a = b++;
                return e.first
                    ? function (e, i, s) {
                          for (; (e = e[n]); ) if (1 === e.nodeType || r) return t(e, i, s);
                          return !1;
                      }
                    : function (e, i, l) {
                          var h,
                              c,
                              u = [_, a];
                          if (l) {
                              for (; (e = e[n]); ) if ((1 === e.nodeType || r) && t(e, i, l)) return !0;
                          } else
                              for (; (e = e[n]); )
                                  if (1 === e.nodeType || r)
                                      if (((c = e[v] || (e[v] = {})), s && C(e, s))) e = e[n] || e;
                                      else {
                                          if ((h = c[o]) && h[0] === _ && h[1] === a) return (u[2] = h[2]);
                                          if (((c[o] = u)[2] = t(e, i, l))) return !0;
                                      }
                          return !1;
                      };
            }
            function pt(t) {
                return 1 < t.length
                    ? function (e, i, n) {
                          for (var s = t.length; s--; ) if (!t[s](e, i, n)) return !1;
                          return !0;
                      }
                    : t[0];
            }
            function ft(t, e, i, n, s) {
                for (var o, r = [], a = 0, l = t.length, h = null != e; a < l; a++) (o = t[a]) && ((i && !i(o, n, s)) || (r.push(o), h && e.push(a)));
                return r;
            }
            function gt(t, e, i, n, s, o) {
                return (
                    n && !n[v] && (n = gt(n)),
                    s && !s[v] && (s = gt(s, o)),
                    et(function (o, r, l, h) {
                        var c,
                            u,
                            d,
                            p,
                            f = [],
                            g = [],
                            v = r.length,
                            _ =
                                o ||
                                (function (t, e, i) {
                                    for (var n = 0, s = e.length; n < s; n++) Z(t, e[n], i);
                                    return i;
                                })(e || "*", l.nodeType ? [l] : l, []),
                            b = !t || (!o && e) ? _ : ft(_, f, t, l, h);
                        if ((i ? i(b, (p = s || (o ? t : v || n) ? [] : r), l, h) : (p = b), n)) for (c = ft(p, g), n(c, [], l, h), u = c.length; u--; ) (d = c[u]) && (p[g[u]] = !(b[g[u]] = d));
                        if (o) {
                            if (s || t) {
                                if (s) {
                                    for (c = [], u = p.length; u--; ) (d = p[u]) && c.push((b[u] = d));
                                    s(null, (p = []), c, h);
                                }
                                for (u = p.length; u--; ) (d = p[u]) && -1 < (c = s ? a.call(o, d) : f[u]) && (o[c] = !(r[c] = d));
                            }
                        } else (p = ft(p === r ? p.splice(v, p.length) : p)), s ? s(null, r, p, h) : m.apply(r, p);
                    })
                );
            }
            function mt(t) {
                for (
                    var e,
                        i,
                        s,
                        r = t.length,
                        l = n.relative[t[0].type],
                        h = l || n.relative[" "],
                        c = l ? 1 : 0,
                        u = dt(
                            function (t) {
                                return t === e;
                            },
                            h,
                            !0
                        ),
                        d = dt(
                            function (t) {
                                return -1 < a.call(e, t);
                            },
                            h,
                            !0
                        ),
                        p = [
                            function (t, i, n) {
                                var s = (!l && (n || i != o)) || ((e = i).nodeType ? u(t, i, n) : d(t, i, n));
                                return (e = null), s;
                            },
                        ];
                    c < r;
                    c++
                )
                    if ((i = n.relative[t[c].type])) p = [dt(pt(p), i)];
                    else {
                        if ((i = n.filter[t[c].type].apply(null, t[c].matches))[v]) {
                            for (s = ++c; s < r && !n.relative[t[s].type]; s++);
                            return gt(1 < c && pt(p), 1 < c && ut(t.slice(0, c - 1).concat({ value: " " === t[c - 2].type ? "*" : "" })).replace(E, "$1"), i, c < s && mt(t.slice(c, s)), s < r && mt((t = t.slice(s))), s < r && ut(t));
                        }
                        p.push(i);
                    }
                return pt(p);
            }
            function vt(t, e) {
                var i,
                    s,
                    r,
                    a,
                    l,
                    c,
                    u = [],
                    p = [],
                    f = k[t + " "];
                if (!f) {
                    for (e || (e = ct(t)), i = e.length; i--; ) (f = mt(e[i]))[v] ? u.push(f) : p.push(f);
                    (f = k(
                        t,
                        ((s = p),
                        (a = 0 < (r = u).length),
                        (l = 0 < s.length),
                        (c = function (t, e, i, c, u) {
                            var p,
                                f,
                                g,
                                v = 0,
                                b = "0",
                                y = t && [],
                                w = [],
                                k = o,
                                C = t || (l && n.find.TAG("*", u)),
                                D = (_ += null == k ? 1 : Math.random() || 0.1),
                                S = C.length;
                            for (u && (o = e == h || e || u); b !== S && null != (p = C[b]); b++) {
                                if (l && p) {
                                    for (f = 0, e || p.ownerDocument == h || (lt(p), (i = !d)); (g = s[f++]); )
                                        if (g(p, e || h, i)) {
                                            m.call(c, p);
                                            break;
                                        }
                                    u && (_ = D);
                                }
                                a && ((p = !g && p) && v--, t && y.push(p));
                            }
                            if (((v += b), a && b !== v)) {
                                for (f = 0; (g = r[f++]); ) g(y, w, e, i);
                                if (t) {
                                    if (0 < v) for (; b--; ) y[b] || w[b] || (w[b] = T.call(c));
                                    w = ft(w);
                                }
                                m.apply(c, w), u && !t && 0 < w.length && 1 < v + r.length && x.uniqueSort(c);
                            }
                            return u && ((_ = D), (o = k)), y;
                        }),
                        a ? et(c) : c)
                    )).selector = t;
                }
                return f;
            }
            function _t(t, e, i, s) {
                var o,
                    r,
                    a,
                    l,
                    h,
                    c = "function" == typeof t && t,
                    u = !s && ct((t = c.selector || t));
                if (((i = i || []), 1 === u.length)) {
                    if (2 < (r = u[0] = u[0].slice(0)).length && "ID" === (a = r[0]).type && 9 === e.nodeType && d && n.relative[r[1].type]) {
                        if (!(e = (n.find.ID(a.matches[0].replace(V, G), e) || [])[0])) return i;
                        c && (e = e.parentNode), (t = t.slice(r.shift().value.length));
                    }
                    for (o = B.needsContext.test(t) ? 0 : r.length; o-- && ((a = r[o]), !n.relative[(l = a.type)]); )
                        if ((h = n.find[l]) && (s = h(a.matches[0].replace(V, G), (K.test(r[0].type) && at(e.parentNode)) || e))) {
                            if ((r.splice(o, 1), !(t = s.length && ut(r)))) return m.apply(i, s), i;
                            break;
                        }
                }
                return (c || vt(t, u))(s, e, !d, i, !e || (K.test(t) && at(e.parentNode)) || e), i;
            }
            (ht.prototype = n.filters = n.pseudos),
                (n.setFilters = new ht()),
                (p.sortStable = v.split("").sort(P).join("") === v),
                lt(),
                (p.sortDetached = it(function (t) {
                    return 1 & t.compareDocumentPosition(h.createElement("fieldset"));
                })),
                (x.find = Z),
                (x.expr[":"] = x.expr.pseudos),
                (x.unique = x.uniqueSort),
                (Z.compile = vt),
                (Z.select = _t),
                (Z.setDocument = lt),
                (Z.tokenize = ct),
                (Z.escape = x.escapeSelector),
                (Z.getText = x.text),
                (Z.isXML = x.isXMLDoc),
                (Z.selectors = x.expr),
                (Z.support = x.support),
                (Z.uniqueSort = x.uniqueSort);
        })();
        var H = function (t, e, i) {
                for (var n = [], s = void 0 !== i; (t = t[e]) && 9 !== t.nodeType; )
                    if (1 === t.nodeType) {
                        if (s && x(t).is(i)) break;
                        n.push(t);
                    }
                return n;
            },
            L = function (t, e) {
                for (var i = []; t; t = t.nextSibling) 1 === t.nodeType && t !== e && i.push(t);
                return i;
            },
            N = x.expr.match.needsContext,
            W = /^<([a-z][^\/\0>:\x20\t\r\n\f]*)[\x20\t\r\n\f]*\/?>(?:<\/\1>|)$/i;
        function R(t, e, i) {
            return f(e)
                ? x.grep(t, function (t, n) {
                      return !!e.call(t, n, t) !== i;
                  })
                : e.nodeType
                ? x.grep(t, function (t) {
                      return (t === e) !== i;
                  })
                : "string" != typeof e
                ? x.grep(t, function (t) {
                      return -1 < a.call(e, t) !== i;
                  })
                : x.filter(e, t, i);
        }
        (x.filter = function (t, e, i) {
            var n = e[0];
            return (
                i && (t = ":not(" + t + ")"),
                1 === e.length && 1 === n.nodeType
                    ? x.find.matchesSelector(n, t)
                        ? [n]
                        : []
                    : x.find.matches(
                          t,
                          x.grep(e, function (t) {
                              return 1 === t.nodeType;
                          })
                      )
            );
        }),
            x.fn.extend({
                find: function (t) {
                    var e,
                        i,
                        n = this.length,
                        s = this;
                    if ("string" != typeof t)
                        return this.pushStack(
                            x(t).filter(function () {
                                for (e = 0; e < n; e++) if (x.contains(s[e], this)) return !0;
                            })
                        );
                    for (i = this.pushStack([]), e = 0; e < n; e++) x.find(t, s[e], i);
                    return 1 < n ? x.uniqueSort(i) : i;
                },
                filter: function (t) {
                    return this.pushStack(R(this, t || [], !1));
                },
                not: function (t) {
                    return this.pushStack(R(this, t || [], !0));
                },
                is: function (t) {
                    return !!R(this, "string" == typeof t && N.test(t) ? x(t) : t || [], !1).length;
                },
            });
        var z,
            j = /^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]+))$/;
        ((x.fn.init = function (t, e, i) {
            var n, s;
            if (!t) return this;
            if (((i = i || z), "string" == typeof t)) {
                if (!(n = "<" === t[0] && ">" === t[t.length - 1] && 3 <= t.length ? [null, t, null] : j.exec(t)) || (!n[1] && e)) return !e || e.jquery ? (e || i).find(t) : this.constructor(e).find(t);
                if (n[1]) {
                    if (((e = e instanceof x ? e[0] : e), x.merge(this, x.parseHTML(n[1], e && e.nodeType ? e.ownerDocument || e : m, !0)), W.test(n[1]) && x.isPlainObject(e))) for (n in e) f(this[n]) ? this[n](e[n]) : this.attr(n, e[n]);
                    return this;
                }
                return (s = m.getElementById(n[2])) && ((this[0] = s), (this.length = 1)), this;
            }
            return t.nodeType ? ((this[0] = t), (this.length = 1), this) : f(t) ? (void 0 !== i.ready ? i.ready(t) : t(x)) : x.makeArray(t, this);
        }).prototype = x.fn),
            (z = x(m));
        var F = /^(?:parents|prev(?:Until|All))/,
            q = { children: !0, contents: !0, next: !0, prev: !0 };
        function Y(t, e) {
            for (; (t = t[e]) && 1 !== t.nodeType; );
            return t;
        }
        x.fn.extend({
            has: function (t) {
                var e = x(t, this),
                    i = e.length;
                return this.filter(function () {
                    for (var t = 0; t < i; t++) if (x.contains(this, e[t])) return !0;
                });
            },
            closest: function (t, e) {
                var i,
                    n = 0,
                    s = this.length,
                    o = [],
                    r = "string" != typeof t && x(t);
                if (!N.test(t))
                    for (; n < s; n++)
                        for (i = this[n]; i && i !== e; i = i.parentNode)
                            if (i.nodeType < 11 && (r ? -1 < r.index(i) : 1 === i.nodeType && x.find.matchesSelector(i, t))) {
                                o.push(i);
                                break;
                            }
                return this.pushStack(1 < o.length ? x.uniqueSort(o) : o);
            },
            index: function (t) {
                return t ? ("string" == typeof t ? a.call(x(t), this[0]) : a.call(this, t.jquery ? t[0] : t)) : this[0] && this[0].parentNode ? this.first().prevAll().length : -1;
            },
            add: function (t, e) {
                return this.pushStack(x.uniqueSort(x.merge(this.get(), x(t, e))));
            },
            addBack: function (t) {
                return this.add(null == t ? this.prevObject : this.prevObject.filter(t));
            },
        }),
            x.each(
                {
                    parent: function (t) {
                        var e = t.parentNode;
                        return e && 11 !== e.nodeType ? e : null;
                    },
                    parents: function (t) {
                        return H(t, "parentNode");
                    },
                    parentsUntil: function (t, e, i) {
                        return H(t, "parentNode", i);
                    },
                    next: function (t) {
                        return Y(t, "nextSibling");
                    },
                    prev: function (t) {
                        return Y(t, "previousSibling");
                    },
                    nextAll: function (t) {
                        return H(t, "nextSibling");
                    },
                    prevAll: function (t) {
                        return H(t, "previousSibling");
                    },
                    nextUntil: function (t, e, i) {
                        return H(t, "nextSibling", i);
                    },
                    prevUntil: function (t, e, i) {
                        return H(t, "previousSibling", i);
                    },
                    siblings: function (t) {
                        return L((t.parentNode || {}).firstChild, t);
                    },
                    children: function (t) {
                        return L(t.firstChild);
                    },
                    contents: function (t) {
                        return null != t.contentDocument && n(t.contentDocument) ? t.contentDocument : (C(t, "template") && (t = t.content || t), x.merge([], t.childNodes));
                    },
                },
                function (t, e) {
                    x.fn[t] = function (i, n) {
                        var s = x.map(this, e, i);
                        return "Until" !== t.slice(-5) && (n = i), n && "string" == typeof n && (s = x.filter(n, s)), 1 < this.length && (q[t] || x.uniqueSort(s), F.test(t) && s.reverse()), this.pushStack(s);
                    };
                }
            );
        var B = /[^\x20\t\r\n\f]+/g;
        function $(t) {
            return t;
        }
        function X(t) {
            throw t;
        }
        function U(t, e, i, n) {
            var s;
            try {
                t && f((s = t.promise)) ? s.call(t).done(e).fail(i) : t && f((s = t.then)) ? s.call(t, e, i) : e.apply(void 0, [t].slice(n));
            } catch (t) {
                i.apply(void 0, [t]);
            }
        }
        (x.Callbacks = function (t) {
            var e, i;
            t =
                "string" == typeof t
                    ? ((e = t),
                      (i = {}),
                      x.each(e.match(B) || [], function (t, e) {
                          i[e] = !0;
                      }),
                      i)
                    : x.extend({}, t);
            var n,
                s,
                o,
                r,
                a = [],
                l = [],
                h = -1,
                c = function () {
                    for (r = r || t.once, o = n = !0; l.length; h = -1) for (s = l.shift(); ++h < a.length; ) !1 === a[h].apply(s[0], s[1]) && t.stopOnFalse && ((h = a.length), (s = !1));
                    t.memory || (s = !1), (n = !1), r && (a = s ? [] : "");
                },
                u = {
                    add: function () {
                        return (
                            a &&
                                (s && !n && ((h = a.length - 1), l.push(s)),
                                (function e(i) {
                                    x.each(i, function (i, n) {
                                        f(n) ? (t.unique && u.has(n)) || a.push(n) : n && n.length && "string" !== b(n) && e(n);
                                    });
                                })(arguments),
                                s && !n && c()),
                            this
                        );
                    },
                    remove: function () {
                        return (
                            x.each(arguments, function (t, e) {
                                for (var i; -1 < (i = x.inArray(e, a, i)); ) a.splice(i, 1), i <= h && h--;
                            }),
                            this
                        );
                    },
                    has: function (t) {
                        return t ? -1 < x.inArray(t, a) : 0 < a.length;
                    },
                    empty: function () {
                        return a && (a = []), this;
                    },
                    disable: function () {
                        return (r = l = []), (a = s = ""), this;
                    },
                    disabled: function () {
                        return !a;
                    },
                    lock: function () {
                        return (r = l = []), s || n || (a = s = ""), this;
                    },
                    locked: function () {
                        return !!r;
                    },
                    fireWith: function (t, e) {
                        return r || ((e = [t, (e = e || []).slice ? e.slice() : e]), l.push(e), n || c()), this;
                    },
                    fire: function () {
                        return u.fireWith(this, arguments), this;
                    },
                    fired: function () {
                        return !!o;
                    },
                };
            return u;
        }),
            x.extend({
                Deferred: function (e) {
                    var i = [
                            ["notify", "progress", x.Callbacks("memory"), x.Callbacks("memory"), 2],
                            ["resolve", "done", x.Callbacks("once memory"), x.Callbacks("once memory"), 0, "resolved"],
                            ["reject", "fail", x.Callbacks("once memory"), x.Callbacks("once memory"), 1, "rejected"],
                        ],
                        n = "pending",
                        s = {
                            state: function () {
                                return n;
                            },
                            always: function () {
                                return o.done(arguments).fail(arguments), this;
                            },
                            catch: function (t) {
                                return s.then(null, t);
                            },
                            pipe: function () {
                                var t = arguments;
                                return x
                                    .Deferred(function (e) {
                                        x.each(i, function (i, n) {
                                            var s = f(t[n[4]]) && t[n[4]];
                                            o[n[1]](function () {
                                                var t = s && s.apply(this, arguments);
                                                t && f(t.promise) ? t.promise().progress(e.notify).done(e.resolve).fail(e.reject) : e[n[0] + "With"](this, s ? [t] : arguments);
                                            });
                                        }),
                                            (t = null);
                                    })
                                    .promise();
                            },
                            then: function (e, n, s) {
                                var o = 0;
                                function r(e, i, n, s) {
                                    return function () {
                                        var a = this,
                                            l = arguments,
                                            h = function () {
                                                var t, h;
                                                if (!(e < o)) {
                                                    if ((t = n.apply(a, l)) === i.promise()) throw new TypeError("Thenable self-resolution");
                                                    (h = t && ("object" == typeof t || "function" == typeof t) && t.then),
                                                        f(h)
                                                            ? s
                                                                ? h.call(t, r(o, i, $, s), r(o, i, X, s))
                                                                : (o++, h.call(t, r(o, i, $, s), r(o, i, X, s), r(o, i, $, i.notifyWith)))
                                                            : (n !== $ && ((a = void 0), (l = [t])), (s || i.resolveWith)(a, l));
                                                }
                                            },
                                            c = s
                                                ? h
                                                : function () {
                                                      try {
                                                          h();
                                                      } catch (t) {
                                                          x.Deferred.exceptionHook && x.Deferred.exceptionHook(t, c.error), o <= e + 1 && (n !== X && ((a = void 0), (l = [t])), i.rejectWith(a, l));
                                                      }
                                                  };
                                        e ? c() : (x.Deferred.getErrorHook ? (c.error = x.Deferred.getErrorHook()) : x.Deferred.getStackHook && (c.error = x.Deferred.getStackHook()), t.setTimeout(c));
                                    };
                                }
                                return x
                                    .Deferred(function (t) {
                                        i[0][3].add(r(0, t, f(s) ? s : $, t.notifyWith)), i[1][3].add(r(0, t, f(e) ? e : $)), i[2][3].add(r(0, t, f(n) ? n : X));
                                    })
                                    .promise();
                            },
                            promise: function (t) {
                                return null != t ? x.extend(t, s) : s;
                            },
                        },
                        o = {};
                    return (
                        x.each(i, function (t, e) {
                            var r = e[2],
                                a = e[5];
                            (s[e[1]] = r.add),
                                a &&
                                    r.add(
                                        function () {
                                            n = a;
                                        },
                                        i[3 - t][2].disable,
                                        i[3 - t][3].disable,
                                        i[0][2].lock,
                                        i[0][3].lock
                                    ),
                                r.add(e[3].fire),
                                (o[e[0]] = function () {
                                    return o[e[0] + "With"](this === o ? void 0 : this, arguments), this;
                                }),
                                (o[e[0] + "With"] = r.fireWith);
                        }),
                        s.promise(o),
                        e && e.call(o, o),
                        o
                    );
                },
                when: function (t) {
                    var e = arguments.length,
                        i = e,
                        n = Array(i),
                        o = s.call(arguments),
                        r = x.Deferred(),
                        a = function (t) {
                            return function (i) {
                                (n[t] = this), (o[t] = 1 < arguments.length ? s.call(arguments) : i), --e || r.resolveWith(n, o);
                            };
                        };
                    if (e <= 1 && (U(t, r.done(a(i)).resolve, r.reject, !e), "pending" === r.state() || f(o[i] && o[i].then))) return r.then();
                    for (; i--; ) U(o[i], a(i), r.reject);
                    return r.promise();
                },
            });
        var K = /^(Eval|Internal|Range|Reference|Syntax|Type|URI)Error$/;
        (x.Deferred.exceptionHook = function (e, i) {
            t.console && t.console.warn && e && K.test(e.name) && t.console.warn("jQuery.Deferred exception: " + e.message, e.stack, i);
        }),
            (x.readyException = function (e) {
                t.setTimeout(function () {
                    throw e;
                });
            });
        var V = x.Deferred();
        function G() {
            m.removeEventListener("DOMContentLoaded", G), t.removeEventListener("load", G), x.ready();
        }
        (x.fn.ready = function (t) {
            return (
                V.then(t).catch(function (t) {
                    x.readyException(t);
                }),
                this
            );
        }),
            x.extend({
                isReady: !1,
                readyWait: 1,
                ready: function (t) {
                    (!0 === t ? --x.readyWait : x.isReady) || ((x.isReady = !0) !== t && 0 < --x.readyWait) || V.resolveWith(m, [x]);
                },
            }),
            (x.ready.then = V.then),
            "complete" === m.readyState || ("loading" !== m.readyState && !m.documentElement.doScroll) ? t.setTimeout(x.ready) : (m.addEventListener("DOMContentLoaded", G), t.addEventListener("load", G));
        var Q = function (t, e, i, n, s, o, r) {
                var a = 0,
                    l = t.length,
                    h = null == i;
                if ("object" === b(i)) for (a in ((s = !0), i)) Q(t, e, a, i[a], !0, o, r);
                else if (
                    void 0 !== n &&
                    ((s = !0),
                    f(n) || (r = !0),
                    h &&
                        (r
                            ? (e.call(t, n), (e = null))
                            : ((h = e),
                              (e = function (t, e, i) {
                                  return h.call(x(t), i);
                              }))),
                    e)
                )
                    for (; a < l; a++) e(t[a], i, r ? n : n.call(t[a], a, e(t[a], i)));
                return s ? t : h ? e.call(t) : l ? e(t[0], i) : o;
            },
            J = /^-ms-/,
            Z = /-([a-z])/g;
        function tt(t, e) {
            return e.toUpperCase();
        }
        function et(t) {
            return t.replace(J, "ms-").replace(Z, tt);
        }
        var it = function (t) {
            return 1 === t.nodeType || 9 === t.nodeType || !+t.nodeType;
        };
        function nt() {
            this.expando = x.expando + nt.uid++;
        }
        (nt.uid = 1),
            (nt.prototype = {
                cache: function (t) {
                    var e = t[this.expando];
                    return e || ((e = {}), it(t) && (t.nodeType ? (t[this.expando] = e) : Object.defineProperty(t, this.expando, { value: e, configurable: !0 }))), e;
                },
                set: function (t, e, i) {
                    var n,
                        s = this.cache(t);
                    if ("string" == typeof e) s[et(e)] = i;
                    else for (n in e) s[et(n)] = e[n];
                    return s;
                },
                get: function (t, e) {
                    return void 0 === e ? this.cache(t) : t[this.expando] && t[this.expando][et(e)];
                },
                access: function (t, e, i) {
                    return void 0 === e || (e && "string" == typeof e && void 0 === i) ? this.get(t, e) : (this.set(t, e, i), void 0 !== i ? i : e);
                },
                remove: function (t, e) {
                    var i,
                        n = t[this.expando];
                    if (void 0 !== n) {
                        if (void 0 !== e) {
                            i = (e = Array.isArray(e) ? e.map(et) : (e = et(e)) in n ? [e] : e.match(B) || []).length;
                            for (; i--; ) delete n[e[i]];
                        }
                        (void 0 === e || x.isEmptyObject(n)) && (t.nodeType ? (t[this.expando] = void 0) : delete t[this.expando]);
                    }
                },
                hasData: function (t) {
                    var e = t[this.expando];
                    return void 0 !== e && !x.isEmptyObject(e);
                },
            });
        var st = new nt(),
            ot = new nt(),
            rt = /^(?:\{[\w\W]*\}|\[[\w\W]*\])$/,
            at = /[A-Z]/g;
        function lt(t, e, i) {
            var n, s;
            if (void 0 === i && 1 === t.nodeType)
                if (((n = "data-" + e.replace(at, "-$&").toLowerCase()), "string" == typeof (i = t.getAttribute(n)))) {
                    try {
                        i = "true" === (s = i) || ("false" !== s && ("null" === s ? null : s === +s + "" ? +s : rt.test(s) ? JSON.parse(s) : s));
                    } catch (t) {}
                    ot.set(t, e, i);
                } else i = void 0;
            return i;
        }
        x.extend({
            hasData: function (t) {
                return ot.hasData(t) || st.hasData(t);
            },
            data: function (t, e, i) {
                return ot.access(t, e, i);
            },
            removeData: function (t, e) {
                ot.remove(t, e);
            },
            _data: function (t, e, i) {
                return st.access(t, e, i);
            },
            _removeData: function (t, e) {
                st.remove(t, e);
            },
        }),
            x.fn.extend({
                data: function (t, e) {
                    var i,
                        n,
                        s,
                        o = this[0],
                        r = o && o.attributes;
                    if (void 0 === t) {
                        if (this.length && ((s = ot.get(o)), 1 === o.nodeType && !st.get(o, "hasDataAttrs"))) {
                            for (i = r.length; i--; ) r[i] && 0 === (n = r[i].name).indexOf("data-") && ((n = et(n.slice(5))), lt(o, n, s[n]));
                            st.set(o, "hasDataAttrs", !0);
                        }
                        return s;
                    }
                    return "object" == typeof t
                        ? this.each(function () {
                              ot.set(this, t);
                          })
                        : Q(
                              this,
                              function (e) {
                                  var i;
                                  if (o && void 0 === e) return void 0 !== (i = ot.get(o, t)) || void 0 !== (i = lt(o, t)) ? i : void 0;
                                  this.each(function () {
                                      ot.set(this, t, e);
                                  });
                              },
                              null,
                              e,
                              1 < arguments.length,
                              null,
                              !0
                          );
                },
                removeData: function (t) {
                    return this.each(function () {
                        ot.remove(this, t);
                    });
                },
            }),
            x.extend({
                queue: function (t, e, i) {
                    var n;
                    if (t) return (e = (e || "fx") + "queue"), (n = st.get(t, e)), i && (!n || Array.isArray(i) ? (n = st.access(t, e, x.makeArray(i))) : n.push(i)), n || [];
                },
                dequeue: function (t, e) {
                    e = e || "fx";
                    var i = x.queue(t, e),
                        n = i.length,
                        s = i.shift(),
                        o = x._queueHooks(t, e);
                    "inprogress" === s && ((s = i.shift()), n--),
                        s &&
                            ("fx" === e && i.unshift("inprogress"),
                            delete o.stop,
                            s.call(
                                t,
                                function () {
                                    x.dequeue(t, e);
                                },
                                o
                            )),
                        !n && o && o.empty.fire();
                },
                _queueHooks: function (t, e) {
                    var i = e + "queueHooks";
                    return (
                        st.get(t, i) ||
                        st.access(t, i, {
                            empty: x.Callbacks("once memory").add(function () {
                                st.remove(t, [e + "queue", i]);
                            }),
                        })
                    );
                },
            }),
            x.fn.extend({
                queue: function (t, e) {
                    var i = 2;
                    return (
                        "string" != typeof t && ((e = t), (t = "fx"), i--),
                        arguments.length < i
                            ? x.queue(this[0], t)
                            : void 0 === e
                            ? this
                            : this.each(function () {
                                  var i = x.queue(this, t, e);
                                  x._queueHooks(this, t), "fx" === t && "inprogress" !== i[0] && x.dequeue(this, t);
                              })
                    );
                },
                dequeue: function (t) {
                    return this.each(function () {
                        x.dequeue(this, t);
                    });
                },
                clearQueue: function (t) {
                    return this.queue(t || "fx", []);
                },
                promise: function (t, e) {
                    var i,
                        n = 1,
                        s = x.Deferred(),
                        o = this,
                        r = this.length,
                        a = function () {
                            --n || s.resolveWith(o, [o]);
                        };
                    for ("string" != typeof t && ((e = t), (t = void 0)), t = t || "fx"; r--; ) (i = st.get(o[r], t + "queueHooks")) && i.empty && (n++, i.empty.add(a));
                    return a(), s.promise(e);
                },
            });
        var ht = /[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source,
            ct = new RegExp("^(?:([+-])=|)(" + ht + ")([a-z%]*)$", "i"),
            ut = ["Top", "Right", "Bottom", "Left"],
            dt = m.documentElement,
            pt = function (t) {
                return x.contains(t.ownerDocument, t);
            },
            ft = { composed: !0 };
        dt.getRootNode &&
            (pt = function (t) {
                return x.contains(t.ownerDocument, t) || t.getRootNode(ft) === t.ownerDocument;
            });
        var gt = function (t, e) {
            return "none" === (t = e || t).style.display || ("" === t.style.display && pt(t) && "none" === x.css(t, "display"));
        };
        function mt(t, e, i, n) {
            var s,
                o,
                r = 20,
                a = n
                    ? function () {
                          return n.cur();
                      }
                    : function () {
                          return x.css(t, e, "");
                      },
                l = a(),
                h = (i && i[3]) || (x.cssNumber[e] ? "" : "px"),
                c = t.nodeType && (x.cssNumber[e] || ("px" !== h && +l)) && ct.exec(x.css(t, e));
            if (c && c[3] !== h) {
                for (l /= 2, h = h || c[3], c = +l || 1; r--; ) x.style(t, e, c + h), (1 - o) * (1 - (o = a() / l || 0.5)) <= 0 && (r = 0), (c /= o);
                (c *= 2), x.style(t, e, c + h), (i = i || []);
            }
            return i && ((c = +c || +l || 0), (s = i[1] ? c + (i[1] + 1) * i[2] : +i[2]), n && ((n.unit = h), (n.start = c), (n.end = s))), s;
        }
        var vt = {};
        function _t(t, e) {
            for (var i, n, s, o, r, a, l, h = [], c = 0, u = t.length; c < u; c++)
                (n = t[c]).style &&
                    ((i = n.style.display),
                    e
                        ? ("none" === i && ((h[c] = st.get(n, "display") || null), h[c] || (n.style.display = "")),
                          "" === n.style.display &&
                              gt(n) &&
                              (h[c] =
                                  ((l = r = o = void 0),
                                  (r = (s = n).ownerDocument),
                                  (a = s.nodeName),
                                  (l = vt[a]) || ((o = r.body.appendChild(r.createElement(a))), (l = x.css(o, "display")), o.parentNode.removeChild(o), "none" === l && (l = "block"), (vt[a] = l)))))
                        : "none" !== i && ((h[c] = "none"), st.set(n, "display", i)));
            for (c = 0; c < u; c++) null != h[c] && (t[c].style.display = h[c]);
            return t;
        }
        x.fn.extend({
            show: function () {
                return _t(this, !0);
            },
            hide: function () {
                return _t(this);
            },
            toggle: function (t) {
                return "boolean" == typeof t
                    ? t
                        ? this.show()
                        : this.hide()
                    : this.each(function () {
                          gt(this) ? x(this).show() : x(this).hide();
                      });
            },
        });
        var bt,
            yt,
            wt = /^(?:checkbox|radio)$/i,
            xt = /<([a-z][^\/\0>\x20\t\r\n\f]*)/i,
            kt = /^$|^module$|\/(?:java|ecma)script/i;
        (bt = m.createDocumentFragment().appendChild(m.createElement("div"))),
            (yt = m.createElement("input")).setAttribute("type", "radio"),
            yt.setAttribute("checked", "checked"),
            yt.setAttribute("name", "t"),
            bt.appendChild(yt),
            (p.checkClone = bt.cloneNode(!0).cloneNode(!0).lastChild.checked),
            (bt.innerHTML = "<textarea>x</textarea>"),
            (p.noCloneChecked = !!bt.cloneNode(!0).lastChild.defaultValue),
            (bt.innerHTML = "<option></option>"),
            (p.option = !!bt.lastChild);
        var Ct = { thead: [1, "<table>", "</table>"], col: [2, "<table><colgroup>", "</colgroup></table>"], tr: [2, "<table><tbody>", "</tbody></table>"], td: [3, "<table><tbody><tr>", "</tr></tbody></table>"], _default: [0, "", ""] };
        function Tt(t, e) {
            var i;
            return (i = void 0 !== t.getElementsByTagName ? t.getElementsByTagName(e || "*") : void 0 !== t.querySelectorAll ? t.querySelectorAll(e || "*") : []), void 0 === e || (e && C(t, e)) ? x.merge([t], i) : i;
        }
        function Dt(t, e) {
            for (var i = 0, n = t.length; i < n; i++) st.set(t[i], "globalEval", !e || st.get(e[i], "globalEval"));
        }
        (Ct.tbody = Ct.tfoot = Ct.colgroup = Ct.caption = Ct.thead), (Ct.th = Ct.td), p.option || (Ct.optgroup = Ct.option = [1, "<select multiple='multiple'>", "</select>"]);
        var St = /<|&#?\w+;/;
        function At(t, e, i, n, s) {
            for (var o, r, a, l, h, c, u = e.createDocumentFragment(), d = [], p = 0, f = t.length; p < f; p++)
                if ((o = t[p]) || 0 === o)
                    if ("object" === b(o)) x.merge(d, o.nodeType ? [o] : o);
                    else if (St.test(o)) {
                        for (r = r || u.appendChild(e.createElement("div")), a = (xt.exec(o) || ["", ""])[1].toLowerCase(), l = Ct[a] || Ct._default, r.innerHTML = l[1] + x.htmlPrefilter(o) + l[2], c = l[0]; c--; ) r = r.lastChild;
                        x.merge(d, r.childNodes), ((r = u.firstChild).textContent = "");
                    } else d.push(e.createTextNode(o));
            for (u.textContent = "", p = 0; (o = d[p++]); )
                if (n && -1 < x.inArray(o, n)) s && s.push(o);
                else if (((h = pt(o)), (r = Tt(u.appendChild(o), "script")), h && Dt(r), i)) for (c = 0; (o = r[c++]); ) kt.test(o.type || "") && i.push(o);
            return u;
        }
        var Et = /^([^.]*)(?:\.(.+)|)/;
        function It() {
            return !0;
        }
        function Pt() {
            return !1;
        }
        function Mt(t, e, i, n, s, o) {
            var r, a;
            if ("object" == typeof e) {
                for (a in ("string" != typeof i && ((n = n || i), (i = void 0)), e)) Mt(t, a, i, n, e[a], o);
                return t;
            }
            if ((null == n && null == s ? ((s = i), (n = i = void 0)) : null == s && ("string" == typeof i ? ((s = n), (n = void 0)) : ((s = n), (n = i), (i = void 0))), !1 === s)) s = Pt;
            else if (!s) return t;
            return (
                1 === o &&
                    ((r = s),
                    ((s = function (t) {
                        return x().off(t), r.apply(this, arguments);
                    }).guid = r.guid || (r.guid = x.guid++))),
                t.each(function () {
                    x.event.add(this, e, s, n, i);
                })
            );
        }
        function Ot(t, e, i) {
            i
                ? (st.set(t, e, !1),
                  x.event.add(t, e, {
                      namespace: !1,
                      handler: function (t) {
                          var i,
                              n = st.get(this, e);
                          if (1 & t.isTrigger && this[e]) {
                              if (n) (x.event.special[e] || {}).delegateType && t.stopPropagation();
                              else if (((n = s.call(arguments)), st.set(this, e, n), this[e](), (i = st.get(this, e)), st.set(this, e, !1), n !== i)) return t.stopImmediatePropagation(), t.preventDefault(), i;
                          } else n && (st.set(this, e, x.event.trigger(n[0], n.slice(1), this)), t.stopPropagation(), (t.isImmediatePropagationStopped = It));
                      },
                  }))
                : void 0 === st.get(t, e) && x.event.add(t, e, It);
        }
        (x.event = {
            global: {},
            add: function (t, e, i, n, s) {
                var o,
                    r,
                    a,
                    l,
                    h,
                    c,
                    u,
                    d,
                    p,
                    f,
                    g,
                    m = st.get(t);
                if (it(t))
                    for (
                        i.handler && ((i = (o = i).handler), (s = o.selector)),
                            s && x.find.matchesSelector(dt, s),
                            i.guid || (i.guid = x.guid++),
                            (l = m.events) || (l = m.events = Object.create(null)),
                            (r = m.handle) ||
                                (r = m.handle = function (e) {
                                    return void 0 !== x && x.event.triggered !== e.type ? x.event.dispatch.apply(t, arguments) : void 0;
                                }),
                            h = (e = (e || "").match(B) || [""]).length;
                        h--;

                    )
                        (p = g = (a = Et.exec(e[h]) || [])[1]),
                            (f = (a[2] || "").split(".").sort()),
                            p &&
                                ((u = x.event.special[p] || {}),
                                (p = (s ? u.delegateType : u.bindType) || p),
                                (u = x.event.special[p] || {}),
                                (c = x.extend({ type: p, origType: g, data: n, handler: i, guid: i.guid, selector: s, needsContext: s && x.expr.match.needsContext.test(s), namespace: f.join(".") }, o)),
                                (d = l[p]) || (((d = l[p] = []).delegateCount = 0), (u.setup && !1 !== u.setup.call(t, n, f, r)) || (t.addEventListener && t.addEventListener(p, r))),
                                u.add && (u.add.call(t, c), c.handler.guid || (c.handler.guid = i.guid)),
                                s ? d.splice(d.delegateCount++, 0, c) : d.push(c),
                                (x.event.global[p] = !0));
            },
            remove: function (t, e, i, n, s) {
                var o,
                    r,
                    a,
                    l,
                    h,
                    c,
                    u,
                    d,
                    p,
                    f,
                    g,
                    m = st.hasData(t) && st.get(t);
                if (m && (l = m.events)) {
                    for (h = (e = (e || "").match(B) || [""]).length; h--; )
                        if (((p = g = (a = Et.exec(e[h]) || [])[1]), (f = (a[2] || "").split(".").sort()), p)) {
                            for (u = x.event.special[p] || {}, d = l[(p = (n ? u.delegateType : u.bindType) || p)] || [], a = a[2] && new RegExp("(^|\\.)" + f.join("\\.(?:.*\\.|)") + "(\\.|$)"), r = o = d.length; o--; )
                                (c = d[o]),
                                    (!s && g !== c.origType) ||
                                        (i && i.guid !== c.guid) ||
                                        (a && !a.test(c.namespace)) ||
                                        (n && n !== c.selector && ("**" !== n || !c.selector)) ||
                                        (d.splice(o, 1), c.selector && d.delegateCount--, u.remove && u.remove.call(t, c));
                            r && !d.length && ((u.teardown && !1 !== u.teardown.call(t, f, m.handle)) || x.removeEvent(t, p, m.handle), delete l[p]);
                        } else for (p in l) x.event.remove(t, p + e[h], i, n, !0);
                    x.isEmptyObject(l) && st.remove(t, "handle events");
                }
            },
            dispatch: function (t) {
                var e,
                    i,
                    n,
                    s,
                    o,
                    r,
                    a = new Array(arguments.length),
                    l = x.event.fix(t),
                    h = (st.get(this, "events") || Object.create(null))[l.type] || [],
                    c = x.event.special[l.type] || {};
                for (a[0] = l, e = 1; e < arguments.length; e++) a[e] = arguments[e];
                if (((l.delegateTarget = this), !c.preDispatch || !1 !== c.preDispatch.call(this, l))) {
                    for (r = x.event.handlers.call(this, l, h), e = 0; (s = r[e++]) && !l.isPropagationStopped(); )
                        for (l.currentTarget = s.elem, i = 0; (o = s.handlers[i++]) && !l.isImmediatePropagationStopped(); )
                            (l.rnamespace && !1 !== o.namespace && !l.rnamespace.test(o.namespace)) ||
                                ((l.handleObj = o), (l.data = o.data), void 0 !== (n = ((x.event.special[o.origType] || {}).handle || o.handler).apply(s.elem, a)) && !1 === (l.result = n) && (l.preventDefault(), l.stopPropagation()));
                    return c.postDispatch && c.postDispatch.call(this, l), l.result;
                }
            },
            handlers: function (t, e) {
                var i,
                    n,
                    s,
                    o,
                    r,
                    a = [],
                    l = e.delegateCount,
                    h = t.target;
                if (l && h.nodeType && !("click" === t.type && 1 <= t.button))
                    for (; h !== this; h = h.parentNode || this)
                        if (1 === h.nodeType && ("click" !== t.type || !0 !== h.disabled)) {
                            for (o = [], r = {}, i = 0; i < l; i++) void 0 === r[(s = (n = e[i]).selector + " ")] && (r[s] = n.needsContext ? -1 < x(s, this).index(h) : x.find(s, this, null, [h]).length), r[s] && o.push(n);
                            o.length && a.push({ elem: h, handlers: o });
                        }
                return (h = this), l < e.length && a.push({ elem: h, handlers: e.slice(l) }), a;
            },
            addProp: function (t, e) {
                Object.defineProperty(x.Event.prototype, t, {
                    enumerable: !0,
                    configurable: !0,
                    get: f(e)
                        ? function () {
                              if (this.originalEvent) return e(this.originalEvent);
                          }
                        : function () {
                              if (this.originalEvent) return this.originalEvent[t];
                          },
                    set: function (e) {
                        Object.defineProperty(this, t, { enumerable: !0, configurable: !0, writable: !0, value: e });
                    },
                });
            },
            fix: function (t) {
                return t[x.expando] ? t : new x.Event(t);
            },
            special: {
                load: { noBubble: !0 },
                click: {
                    setup: function (t) {
                        var e = this || t;
                        return wt.test(e.type) && e.click && C(e, "input") && Ot(e, "click", !0), !1;
                    },
                    trigger: function (t) {
                        var e = this || t;
                        return wt.test(e.type) && e.click && C(e, "input") && Ot(e, "click"), !0;
                    },
                    _default: function (t) {
                        var e = t.target;
                        return (wt.test(e.type) && e.click && C(e, "input") && st.get(e, "click")) || C(e, "a");
                    },
                },
                beforeunload: {
                    postDispatch: function (t) {
                        void 0 !== t.result && t.originalEvent && (t.originalEvent.returnValue = t.result);
                    },
                },
            },
        }),
            (x.removeEvent = function (t, e, i) {
                t.removeEventListener && t.removeEventListener(e, i);
            }),
            (x.Event = function (t, e) {
                if (!(this instanceof x.Event)) return new x.Event(t, e);
                t && t.type
                    ? ((this.originalEvent = t),
                      (this.type = t.type),
                      (this.isDefaultPrevented = t.defaultPrevented || (void 0 === t.defaultPrevented && !1 === t.returnValue) ? It : Pt),
                      (this.target = t.target && 3 === t.target.nodeType ? t.target.parentNode : t.target),
                      (this.currentTarget = t.currentTarget),
                      (this.relatedTarget = t.relatedTarget))
                    : (this.type = t),
                    e && x.extend(this, e),
                    (this.timeStamp = (t && t.timeStamp) || Date.now()),
                    (this[x.expando] = !0);
            }),
            (x.Event.prototype = {
                constructor: x.Event,
                isDefaultPrevented: Pt,
                isPropagationStopped: Pt,
                isImmediatePropagationStopped: Pt,
                isSimulated: !1,
                preventDefault: function () {
                    var t = this.originalEvent;
                    (this.isDefaultPrevented = It), t && !this.isSimulated && t.preventDefault();
                },
                stopPropagation: function () {
                    var t = this.originalEvent;
                    (this.isPropagationStopped = It), t && !this.isSimulated && t.stopPropagation();
                },
                stopImmediatePropagation: function () {
                    var t = this.originalEvent;
                    (this.isImmediatePropagationStopped = It), t && !this.isSimulated && t.stopImmediatePropagation(), this.stopPropagation();
                },
            }),
            x.each(
                {
                    altKey: !0,
                    bubbles: !0,
                    cancelable: !0,
                    changedTouches: !0,
                    ctrlKey: !0,
                    detail: !0,
                    eventPhase: !0,
                    metaKey: !0,
                    pageX: !0,
                    pageY: !0,
                    shiftKey: !0,
                    view: !0,
                    char: !0,
                    code: !0,
                    charCode: !0,
                    key: !0,
                    keyCode: !0,
                    button: !0,
                    buttons: !0,
                    clientX: !0,
                    clientY: !0,
                    offsetX: !0,
                    offsetY: !0,
                    pointerId: !0,
                    pointerType: !0,
                    screenX: !0,
                    screenY: !0,
                    targetTouches: !0,
                    toElement: !0,
                    touches: !0,
                    which: !0,
                },
                x.event.addProp
            ),
            x.each({ focus: "focusin", blur: "focusout" }, function (t, e) {
                function i(t) {
                    if (m.documentMode) {
                        var i = st.get(this, "handle"),
                            n = x.event.fix(t);
                        (n.type = "focusin" === t.type ? "focus" : "blur"), (n.isSimulated = !0), i(t), n.target === n.currentTarget && i(n);
                    } else x.event.simulate(e, t.target, x.event.fix(t));
                }
                (x.event.special[t] = {
                    setup: function () {
                        var n;
                        if ((Ot(this, t, !0), !m.documentMode)) return !1;
                        (n = st.get(this, e)) || this.addEventListener(e, i), st.set(this, e, (n || 0) + 1);
                    },
                    trigger: function () {
                        return Ot(this, t), !0;
                    },
                    teardown: function () {
                        var t;
                        if (!m.documentMode) return !1;
                        (t = st.get(this, e) - 1) ? st.set(this, e, t) : (this.removeEventListener(e, i), st.remove(this, e));
                    },
                    _default: function (e) {
                        return st.get(e.target, t);
                    },
                    delegateType: e,
                }),
                    (x.event.special[e] = {
                        setup: function () {
                            var n = this.ownerDocument || this.document || this,
                                s = m.documentMode ? this : n,
                                o = st.get(s, e);
                            o || (m.documentMode ? this.addEventListener(e, i) : n.addEventListener(t, i, !0)), st.set(s, e, (o || 0) + 1);
                        },
                        teardown: function () {
                            var n = this.ownerDocument || this.document || this,
                                s = m.documentMode ? this : n,
                                o = st.get(s, e) - 1;
                            o ? st.set(s, e, o) : (m.documentMode ? this.removeEventListener(e, i) : n.removeEventListener(t, i, !0), st.remove(s, e));
                        },
                    });
            }),
            x.each({ mouseenter: "mouseover", mouseleave: "mouseout", pointerenter: "pointerover", pointerleave: "pointerout" }, function (t, e) {
                x.event.special[t] = {
                    delegateType: e,
                    bindType: e,
                    handle: function (t) {
                        var i,
                            n = t.relatedTarget,
                            s = t.handleObj;
                        return (n && (n === this || x.contains(this, n))) || ((t.type = s.origType), (i = s.handler.apply(this, arguments)), (t.type = e)), i;
                    },
                };
            }),
            x.fn.extend({
                on: function (t, e, i, n) {
                    return Mt(this, t, e, i, n);
                },
                one: function (t, e, i, n) {
                    return Mt(this, t, e, i, n, 1);
                },
                off: function (t, e, i) {
                    var n, s;
                    if (t && t.preventDefault && t.handleObj) return (n = t.handleObj), x(t.delegateTarget).off(n.namespace ? n.origType + "." + n.namespace : n.origType, n.selector, n.handler), this;
                    if ("object" == typeof t) {
                        for (s in t) this.off(s, e, t[s]);
                        return this;
                    }
                    return (
                        (!1 !== e && "function" != typeof e) || ((i = e), (e = void 0)),
                        !1 === i && (i = Pt),
                        this.each(function () {
                            x.event.remove(this, t, i, e);
                        })
                    );
                },
            });
        var Ht = /<script|<style|<link/i,
            Lt = /checked\s*(?:[^=]|=\s*.checked.)/i,
            Nt = /^\s*<!\[CDATA\[|\]\]>\s*$/g;
        function Wt(t, e) {
            return (C(t, "table") && C(11 !== e.nodeType ? e : e.firstChild, "tr") && x(t).children("tbody")[0]) || t;
        }
        function Rt(t) {
            return (t.type = (null !== t.getAttribute("type")) + "/" + t.type), t;
        }
        function zt(t) {
            return "true/" === (t.type || "").slice(0, 5) ? (t.type = t.type.slice(5)) : t.removeAttribute("type"), t;
        }
        function jt(t, e) {
            var i, n, s, o, r, a;
            if (1 === e.nodeType) {
                if (st.hasData(t) && (a = st.get(t).events)) for (s in (st.remove(e, "handle events"), a)) for (i = 0, n = a[s].length; i < n; i++) x.event.add(e, s, a[s][i]);
                ot.hasData(t) && ((o = ot.access(t)), (r = x.extend({}, o)), ot.set(e, r));
            }
        }
        function Ft(t, e, i, n) {
            e = o(e);
            var s,
                r,
                a,
                l,
                h,
                c,
                u = 0,
                d = t.length,
                g = d - 1,
                m = e[0],
                v = f(m);
            if (v || (1 < d && "string" == typeof m && !p.checkClone && Lt.test(m)))
                return t.each(function (s) {
                    var o = t.eq(s);
                    v && (e[0] = m.call(this, s, o.html())), Ft(o, e, i, n);
                });
            if (d && ((r = (s = At(e, t[0].ownerDocument, !1, t, n)).firstChild), 1 === s.childNodes.length && (s = r), r || n)) {
                for (l = (a = x.map(Tt(s, "script"), Rt)).length; u < d; u++) (h = s), u !== g && ((h = x.clone(h, !0, !0)), l && x.merge(a, Tt(h, "script"))), i.call(t[u], h, u);
                if (l)
                    for (c = a[a.length - 1].ownerDocument, x.map(a, zt), u = 0; u < l; u++)
                        (h = a[u]),
                            kt.test(h.type || "") &&
                                !st.access(h, "globalEval") &&
                                x.contains(c, h) &&
                                (h.src && "module" !== (h.type || "").toLowerCase() ? x._evalUrl && !h.noModule && x._evalUrl(h.src, { nonce: h.nonce || h.getAttribute("nonce") }, c) : _(h.textContent.replace(Nt, ""), h, c));
            }
            return t;
        }
        function qt(t, e, i) {
            for (var n, s = e ? x.filter(e, t) : t, o = 0; null != (n = s[o]); o++) i || 1 !== n.nodeType || x.cleanData(Tt(n)), n.parentNode && (i && pt(n) && Dt(Tt(n, "script")), n.parentNode.removeChild(n));
            return t;
        }
        x.extend({
            htmlPrefilter: function (t) {
                return t;
            },
            clone: function (t, e, i) {
                var n,
                    s,
                    o,
                    r,
                    a,
                    l,
                    h,
                    c = t.cloneNode(!0),
                    u = pt(t);
                if (!(p.noCloneChecked || (1 !== t.nodeType && 11 !== t.nodeType) || x.isXMLDoc(t)))
                    for (r = Tt(c), n = 0, s = (o = Tt(t)).length; n < s; n++)
                        (a = o[n]), "input" === (h = (l = r[n]).nodeName.toLowerCase()) && wt.test(a.type) ? (l.checked = a.checked) : ("input" !== h && "textarea" !== h) || (l.defaultValue = a.defaultValue);
                if (e)
                    if (i) for (o = o || Tt(t), r = r || Tt(c), n = 0, s = o.length; n < s; n++) jt(o[n], r[n]);
                    else jt(t, c);
                return 0 < (r = Tt(c, "script")).length && Dt(r, !u && Tt(t, "script")), c;
            },
            cleanData: function (t) {
                for (var e, i, n, s = x.event.special, o = 0; void 0 !== (i = t[o]); o++)
                    if (it(i)) {
                        if ((e = i[st.expando])) {
                            if (e.events) for (n in e.events) s[n] ? x.event.remove(i, n) : x.removeEvent(i, n, e.handle);
                            i[st.expando] = void 0;
                        }
                        i[ot.expando] && (i[ot.expando] = void 0);
                    }
            },
        }),
            x.fn.extend({
                detach: function (t) {
                    return qt(this, t, !0);
                },
                remove: function (t) {
                    return qt(this, t);
                },
                text: function (t) {
                    return Q(
                        this,
                        function (t) {
                            return void 0 === t
                                ? x.text(this)
                                : this.empty().each(function () {
                                      (1 !== this.nodeType && 11 !== this.nodeType && 9 !== this.nodeType) || (this.textContent = t);
                                  });
                        },
                        null,
                        t,
                        arguments.length
                    );
                },
                append: function () {
                    return Ft(this, arguments, function (t) {
                        (1 !== this.nodeType && 11 !== this.nodeType && 9 !== this.nodeType) || Wt(this, t).appendChild(t);
                    });
                },
                prepend: function () {
                    return Ft(this, arguments, function (t) {
                        if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
                            var e = Wt(this, t);
                            e.insertBefore(t, e.firstChild);
                        }
                    });
                },
                before: function () {
                    return Ft(this, arguments, function (t) {
                        this.parentNode && this.parentNode.insertBefore(t, this);
                    });
                },
                after: function () {
                    return Ft(this, arguments, function (t) {
                        this.parentNode && this.parentNode.insertBefore(t, this.nextSibling);
                    });
                },
                empty: function () {
                    for (var t, e = 0; null != (t = this[e]); e++) 1 === t.nodeType && (x.cleanData(Tt(t, !1)), (t.textContent = ""));
                    return this;
                },
                clone: function (t, e) {
                    return (
                        (t = null != t && t),
                        (e = null == e ? t : e),
                        this.map(function () {
                            return x.clone(this, t, e);
                        })
                    );
                },
                html: function (t) {
                    return Q(
                        this,
                        function (t) {
                            var e = this[0] || {},
                                i = 0,
                                n = this.length;
                            if (void 0 === t && 1 === e.nodeType) return e.innerHTML;
                            if ("string" == typeof t && !Ht.test(t) && !Ct[(xt.exec(t) || ["", ""])[1].toLowerCase()]) {
                                t = x.htmlPrefilter(t);
                                try {
                                    for (; i < n; i++) 1 === (e = this[i] || {}).nodeType && (x.cleanData(Tt(e, !1)), (e.innerHTML = t));
                                    e = 0;
                                } catch (t) {}
                            }
                            e && this.empty().append(t);
                        },
                        null,
                        t,
                        arguments.length
                    );
                },
                replaceWith: function () {
                    var t = [];
                    return Ft(
                        this,
                        arguments,
                        function (e) {
                            var i = this.parentNode;
                            x.inArray(this, t) < 0 && (x.cleanData(Tt(this)), i && i.replaceChild(e, this));
                        },
                        t
                    );
                },
            }),
            x.each({ appendTo: "append", prependTo: "prepend", insertBefore: "before", insertAfter: "after", replaceAll: "replaceWith" }, function (t, e) {
                x.fn[t] = function (t) {
                    for (var i, n = [], s = x(t), o = s.length - 1, a = 0; a <= o; a++) (i = a === o ? this : this.clone(!0)), x(s[a])[e](i), r.apply(n, i.get());
                    return this.pushStack(n);
                };
            });
        var Yt = new RegExp("^(" + ht + ")(?!px)[a-z%]+$", "i"),
            Bt = /^--/,
            $t = function (e) {
                var i = e.ownerDocument.defaultView;
                return (i && i.opener) || (i = t), i.getComputedStyle(e);
            },
            Xt = function (t, e, i) {
                var n,
                    s,
                    o = {};
                for (s in e) (o[s] = t.style[s]), (t.style[s] = e[s]);
                for (s in ((n = i.call(t)), e)) t.style[s] = o[s];
                return n;
            },
            Ut = new RegExp(ut.join("|"), "i");
        function Kt(t, e, i) {
            var n,
                s,
                o,
                r,
                a = Bt.test(e),
                l = t.style;
            return (
                (i = i || $t(t)) &&
                    ((r = i.getPropertyValue(e) || i[e]),
                    a && r && (r = r.replace(E, "$1") || void 0),
                    "" !== r || pt(t) || (r = x.style(t, e)),
                    !p.pixelBoxStyles() && Yt.test(r) && Ut.test(e) && ((n = l.width), (s = l.minWidth), (o = l.maxWidth), (l.minWidth = l.maxWidth = l.width = r), (r = i.width), (l.width = n), (l.minWidth = s), (l.maxWidth = o))),
                void 0 !== r ? r + "" : r
            );
        }
        function Vt(t, e) {
            return {
                get: function () {
                    if (!t()) return (this.get = e).apply(this, arguments);
                    delete this.get;
                },
            };
        }
        !(function () {
            function e() {
                if (c) {
                    (h.style.cssText = "position:absolute;left:-11111px;width:60px;margin-top:1px;padding:0;border:0"),
                        (c.style.cssText = "position:relative;display:block;box-sizing:border-box;overflow:scroll;margin:auto;border:1px;padding:1px;width:60%;top:1%"),
                        dt.appendChild(h).appendChild(c);
                    var e = t.getComputedStyle(c);
                    (n = "1%" !== e.top),
                        (l = 12 === i(e.marginLeft)),
                        (c.style.right = "60%"),
                        (r = 36 === i(e.right)),
                        (s = 36 === i(e.width)),
                        (c.style.position = "absolute"),
                        (o = 12 === i(c.offsetWidth / 3)),
                        dt.removeChild(h),
                        (c = null);
                }
            }
            function i(t) {
                return Math.round(parseFloat(t));
            }
            var n,
                s,
                o,
                r,
                a,
                l,
                h = m.createElement("div"),
                c = m.createElement("div");
            c.style &&
                ((c.style.backgroundClip = "content-box"),
                (c.cloneNode(!0).style.backgroundClip = ""),
                (p.clearCloneStyle = "content-box" === c.style.backgroundClip),
                x.extend(p, {
                    boxSizingReliable: function () {
                        return e(), s;
                    },
                    pixelBoxStyles: function () {
                        return e(), r;
                    },
                    pixelPosition: function () {
                        return e(), n;
                    },
                    reliableMarginLeft: function () {
                        return e(), l;
                    },
                    scrollboxSize: function () {
                        return e(), o;
                    },
                    reliableTrDimensions: function () {
                        var e, i, n, s;
                        return (
                            null == a &&
                                ((e = m.createElement("table")),
                                (i = m.createElement("tr")),
                                (n = m.createElement("div")),
                                (e.style.cssText = "position:absolute;left:-11111px;border-collapse:separate"),
                                (i.style.cssText = "box-sizing:content-box;border:1px solid"),
                                (i.style.height = "1px"),
                                (n.style.height = "9px"),
                                (n.style.display = "block"),
                                dt.appendChild(e).appendChild(i).appendChild(n),
                                (s = t.getComputedStyle(i)),
                                (a = parseInt(s.height, 10) + parseInt(s.borderTopWidth, 10) + parseInt(s.borderBottomWidth, 10) === i.offsetHeight),
                                dt.removeChild(e)),
                            a
                        );
                    },
                }));
        })();
        var Gt = ["Webkit", "Moz", "ms"],
            Qt = m.createElement("div").style,
            Jt = {};
        function Zt(t) {
            return (
                x.cssProps[t] ||
                Jt[t] ||
                (t in Qt
                    ? t
                    : (Jt[t] =
                          (function (t) {
                              for (var e = t[0].toUpperCase() + t.slice(1), i = Gt.length; i--; ) if ((t = Gt[i] + e) in Qt) return t;
                          })(t) || t))
            );
        }
        var te = /^(none|table(?!-c[ea]).+)/,
            ee = { position: "absolute", visibility: "hidden", display: "block" },
            ie = { letterSpacing: "0", fontWeight: "400" };
        function ne(t, e, i) {
            var n = ct.exec(e);
            return n ? Math.max(0, n[2] - (i || 0)) + (n[3] || "px") : e;
        }
        function se(t, e, i, n, s, o) {
            var r = "width" === e ? 1 : 0,
                a = 0,
                l = 0,
                h = 0;
            if (i === (n ? "border" : "content")) return 0;
            for (; r < 4; r += 2)
                "margin" === i && (h += x.css(t, i + ut[r], !0, s)),
                    n
                        ? ("content" === i && (l -= x.css(t, "padding" + ut[r], !0, s)), "margin" !== i && (l -= x.css(t, "border" + ut[r] + "Width", !0, s)))
                        : ((l += x.css(t, "padding" + ut[r], !0, s)), "padding" !== i ? (l += x.css(t, "border" + ut[r] + "Width", !0, s)) : (a += x.css(t, "border" + ut[r] + "Width", !0, s)));
            return !n && 0 <= o && (l += Math.max(0, Math.ceil(t["offset" + e[0].toUpperCase() + e.slice(1)] - o - l - a - 0.5)) || 0), l + h;
        }
        function oe(t, e, i) {
            var n = $t(t),
                s = (!p.boxSizingReliable() || i) && "border-box" === x.css(t, "boxSizing", !1, n),
                o = s,
                r = Kt(t, e, n),
                a = "offset" + e[0].toUpperCase() + e.slice(1);
            if (Yt.test(r)) {
                if (!i) return r;
                r = "auto";
            }
            return (
                ((!p.boxSizingReliable() && s) || (!p.reliableTrDimensions() && C(t, "tr")) || "auto" === r || (!parseFloat(r) && "inline" === x.css(t, "display", !1, n))) &&
                    t.getClientRects().length &&
                    ((s = "border-box" === x.css(t, "boxSizing", !1, n)), (o = a in t) && (r = t[a])),
                (r = parseFloat(r) || 0) + se(t, e, i || (s ? "border" : "content"), o, n, r) + "px"
            );
        }
        function re(t, e, i, n, s) {
            return new re.prototype.init(t, e, i, n, s);
        }
        x.extend({
            cssHooks: {
                opacity: {
                    get: function (t, e) {
                        if (e) {
                            var i = Kt(t, "opacity");
                            return "" === i ? "1" : i;
                        }
                    },
                },
            },
            cssNumber: {
                animationIterationCount: !0,
                aspectRatio: !0,
                borderImageSlice: !0,
                columnCount: !0,
                flexGrow: !0,
                flexShrink: !0,
                fontWeight: !0,
                gridArea: !0,
                gridColumn: !0,
                gridColumnEnd: !0,
                gridColumnStart: !0,
                gridRow: !0,
                gridRowEnd: !0,
                gridRowStart: !0,
                lineHeight: !0,
                opacity: !0,
                order: !0,
                orphans: !0,
                scale: !0,
                widows: !0,
                zIndex: !0,
                zoom: !0,
                fillOpacity: !0,
                floodOpacity: !0,
                stopOpacity: !0,
                strokeMiterlimit: !0,
                strokeOpacity: !0,
            },
            cssProps: {},
            style: function (t, e, i, n) {
                if (t && 3 !== t.nodeType && 8 !== t.nodeType && t.style) {
                    var s,
                        o,
                        r,
                        a = et(e),
                        l = Bt.test(e),
                        h = t.style;
                    if ((l || (e = Zt(a)), (r = x.cssHooks[e] || x.cssHooks[a]), void 0 === i)) return r && "get" in r && void 0 !== (s = r.get(t, !1, n)) ? s : h[e];
                    "string" == (o = typeof i) && (s = ct.exec(i)) && s[1] && ((i = mt(t, e, s)), (o = "number")),
                        null != i &&
                            i == i &&
                            ("number" !== o || l || (i += (s && s[3]) || (x.cssNumber[a] ? "" : "px")),
                            p.clearCloneStyle || "" !== i || 0 !== e.indexOf("background") || (h[e] = "inherit"),
                            (r && "set" in r && void 0 === (i = r.set(t, i, n))) || (l ? h.setProperty(e, i) : (h[e] = i)));
                }
            },
            css: function (t, e, i, n) {
                var s,
                    o,
                    r,
                    a = et(e);
                return (
                    Bt.test(e) || (e = Zt(a)),
                    (r = x.cssHooks[e] || x.cssHooks[a]) && "get" in r && (s = r.get(t, !0, i)),
                    void 0 === s && (s = Kt(t, e, n)),
                    "normal" === s && e in ie && (s = ie[e]),
                    "" === i || i ? ((o = parseFloat(s)), !0 === i || isFinite(o) ? o || 0 : s) : s
                );
            },
        }),
            x.each(["height", "width"], function (t, e) {
                x.cssHooks[e] = {
                    get: function (t, i, n) {
                        if (i)
                            return !te.test(x.css(t, "display")) || (t.getClientRects().length && t.getBoundingClientRect().width)
                                ? oe(t, e, n)
                                : Xt(t, ee, function () {
                                      return oe(t, e, n);
                                  });
                    },
                    set: function (t, i, n) {
                        var s,
                            o = $t(t),
                            r = !p.scrollboxSize() && "absolute" === o.position,
                            a = (r || n) && "border-box" === x.css(t, "boxSizing", !1, o),
                            l = n ? se(t, e, n, a, o) : 0;
                        return (
                            a && r && (l -= Math.ceil(t["offset" + e[0].toUpperCase() + e.slice(1)] - parseFloat(o[e]) - se(t, e, "border", !1, o) - 0.5)),
                            l && (s = ct.exec(i)) && "px" !== (s[3] || "px") && ((t.style[e] = i), (i = x.css(t, e))),
                            ne(0, i, l)
                        );
                    },
                };
            }),
            (x.cssHooks.marginLeft = Vt(p.reliableMarginLeft, function (t, e) {
                if (e)
                    return (
                        (parseFloat(Kt(t, "marginLeft")) ||
                            t.getBoundingClientRect().left -
                                Xt(t, { marginLeft: 0 }, function () {
                                    return t.getBoundingClientRect().left;
                                })) + "px"
                    );
            })),
            x.each({ margin: "", padding: "", border: "Width" }, function (t, e) {
                (x.cssHooks[t + e] = {
                    expand: function (i) {
                        for (var n = 0, s = {}, o = "string" == typeof i ? i.split(" ") : [i]; n < 4; n++) s[t + ut[n] + e] = o[n] || o[n - 2] || o[0];
                        return s;
                    },
                }),
                    "margin" !== t && (x.cssHooks[t + e].set = ne);
            }),
            x.fn.extend({
                css: function (t, e) {
                    return Q(
                        this,
                        function (t, e, i) {
                            var n,
                                s,
                                o = {},
                                r = 0;
                            if (Array.isArray(e)) {
                                for (n = $t(t), s = e.length; r < s; r++) o[e[r]] = x.css(t, e[r], !1, n);
                                return o;
                            }
                            return void 0 !== i ? x.style(t, e, i) : x.css(t, e);
                        },
                        t,
                        e,
                        1 < arguments.length
                    );
                },
            }),
            (((x.Tween = re).prototype = {
                constructor: re,
                init: function (t, e, i, n, s, o) {
                    (this.elem = t), (this.prop = i), (this.easing = s || x.easing._default), (this.options = e), (this.start = this.now = this.cur()), (this.end = n), (this.unit = o || (x.cssNumber[i] ? "" : "px"));
                },
                cur: function () {
                    var t = re.propHooks[this.prop];
                    return t && t.get ? t.get(this) : re.propHooks._default.get(this);
                },
                run: function (t) {
                    var e,
                        i = re.propHooks[this.prop];
                    return (
                        this.options.duration ? (this.pos = e = x.easing[this.easing](t, this.options.duration * t, 0, 1, this.options.duration)) : (this.pos = e = t),
                        (this.now = (this.end - this.start) * e + this.start),
                        this.options.step && this.options.step.call(this.elem, this.now, this),
                        i && i.set ? i.set(this) : re.propHooks._default.set(this),
                        this
                    );
                },
            }).init.prototype = re.prototype),
            ((re.propHooks = {
                _default: {
                    get: function (t) {
                        var e;
                        return 1 !== t.elem.nodeType || (null != t.elem[t.prop] && null == t.elem.style[t.prop]) ? t.elem[t.prop] : (e = x.css(t.elem, t.prop, "")) && "auto" !== e ? e : 0;
                    },
                    set: function (t) {
                        x.fx.step[t.prop] ? x.fx.step[t.prop](t) : 1 !== t.elem.nodeType || (!x.cssHooks[t.prop] && null == t.elem.style[Zt(t.prop)]) ? (t.elem[t.prop] = t.now) : x.style(t.elem, t.prop, t.now + t.unit);
                    },
                },
            }).scrollTop = re.propHooks.scrollLeft = {
                set: function (t) {
                    t.elem.nodeType && t.elem.parentNode && (t.elem[t.prop] = t.now);
                },
            }),
            (x.easing = {
                linear: function (t) {
                    return t;
                },
                swing: function (t) {
                    return 0.5 - Math.cos(t * Math.PI) / 2;
                },
                _default: "swing",
            }),
            (x.fx = re.prototype.init),
            (x.fx.step = {});
        var ae,
            le,
            he,
            ce,
            ue = /^(?:toggle|show|hide)$/,
            de = /queueHooks$/;
        function pe() {
            le && (!1 === m.hidden && t.requestAnimationFrame ? t.requestAnimationFrame(pe) : t.setTimeout(pe, x.fx.interval), x.fx.tick());
        }
        function fe() {
            return (
                t.setTimeout(function () {
                    ae = void 0;
                }),
                (ae = Date.now())
            );
        }
        function ge(t, e) {
            var i,
                n = 0,
                s = { height: t };
            for (e = e ? 1 : 0; n < 4; n += 2 - e) s["margin" + (i = ut[n])] = s["padding" + i] = t;
            return e && (s.opacity = s.width = t), s;
        }
        function me(t, e, i) {
            for (var n, s = (ve.tweeners[e] || []).concat(ve.tweeners["*"]), o = 0, r = s.length; o < r; o++) if ((n = s[o].call(i, e, t))) return n;
        }
        function ve(t, e, i) {
            var n,
                s,
                o = 0,
                r = ve.prefilters.length,
                a = x.Deferred().always(function () {
                    delete l.elem;
                }),
                l = function () {
                    if (s) return !1;
                    for (var e = ae || fe(), i = Math.max(0, h.startTime + h.duration - e), n = 1 - (i / h.duration || 0), o = 0, r = h.tweens.length; o < r; o++) h.tweens[o].run(n);
                    return a.notifyWith(t, [h, n, i]), n < 1 && r ? i : (r || a.notifyWith(t, [h, 1, 0]), a.resolveWith(t, [h]), !1);
                },
                h = a.promise({
                    elem: t,
                    props: x.extend({}, e),
                    opts: x.extend(!0, { specialEasing: {}, easing: x.easing._default }, i),
                    originalProperties: e,
                    originalOptions: i,
                    startTime: ae || fe(),
                    duration: i.duration,
                    tweens: [],
                    createTween: function (e, i) {
                        var n = x.Tween(t, h.opts, e, i, h.opts.specialEasing[e] || h.opts.easing);
                        return h.tweens.push(n), n;
                    },
                    stop: function (e) {
                        var i = 0,
                            n = e ? h.tweens.length : 0;
                        if (s) return this;
                        for (s = !0; i < n; i++) h.tweens[i].run(1);
                        return e ? (a.notifyWith(t, [h, 1, 0]), a.resolveWith(t, [h, e])) : a.rejectWith(t, [h, e]), this;
                    },
                }),
                c = h.props;
            for (
                (function (t, e) {
                    var i, n, s, o, r;
                    for (i in t)
                        if (((s = e[(n = et(i))]), (o = t[i]), Array.isArray(o) && ((s = o[1]), (o = t[i] = o[0])), i !== n && ((t[n] = o), delete t[i]), (r = x.cssHooks[n]) && ("expand" in r)))
                            for (i in ((o = r.expand(o)), delete t[n], o)) (i in t) || ((t[i] = o[i]), (e[i] = s));
                        else e[n] = s;
                })(c, h.opts.specialEasing);
                o < r;
                o++
            )
                if ((n = ve.prefilters[o].call(h, t, c, h.opts))) return f(n.stop) && (x._queueHooks(h.elem, h.opts.queue).stop = n.stop.bind(n)), n;
            return (
                x.map(c, me, h),
                f(h.opts.start) && h.opts.start.call(t, h),
                h.progress(h.opts.progress).done(h.opts.done, h.opts.complete).fail(h.opts.fail).always(h.opts.always),
                x.fx.timer(x.extend(l, { elem: t, anim: h, queue: h.opts.queue })),
                h
            );
        }
        (x.Animation = x.extend(ve, {
            tweeners: {
                "*": [
                    function (t, e) {
                        var i = this.createTween(t, e);
                        return mt(i.elem, t, ct.exec(e), i), i;
                    },
                ],
            },
            tweener: function (t, e) {
                f(t) ? ((e = t), (t = ["*"])) : (t = t.match(B));
                for (var i, n = 0, s = t.length; n < s; n++) (i = t[n]), (ve.tweeners[i] = ve.tweeners[i] || []), ve.tweeners[i].unshift(e);
            },
            prefilters: [
                function (t, e, i) {
                    var n,
                        s,
                        o,
                        r,
                        a,
                        l,
                        h,
                        c,
                        u = "width" in e || "height" in e,
                        d = this,
                        p = {},
                        f = t.style,
                        g = t.nodeType && gt(t),
                        m = st.get(t, "fxshow");
                    for (n in (i.queue ||
                        (null == (r = x._queueHooks(t, "fx")).unqueued &&
                            ((r.unqueued = 0),
                            (a = r.empty.fire),
                            (r.empty.fire = function () {
                                r.unqueued || a();
                            })),
                        r.unqueued++,
                        d.always(function () {
                            d.always(function () {
                                r.unqueued--, x.queue(t, "fx").length || r.empty.fire();
                            });
                        })),
                    e))
                        if (((s = e[n]), ue.test(s))) {
                            if ((delete e[n], (o = o || "toggle" === s), s === (g ? "hide" : "show"))) {
                                if ("show" !== s || !m || void 0 === m[n]) continue;
                                g = !0;
                            }
                            p[n] = (m && m[n]) || x.style(t, n);
                        }
                    if ((l = !x.isEmptyObject(e)) || !x.isEmptyObject(p))
                        for (n in (u &&
                            1 === t.nodeType &&
                            ((i.overflow = [f.overflow, f.overflowX, f.overflowY]),
                            null == (h = m && m.display) && (h = st.get(t, "display")),
                            "none" === (c = x.css(t, "display")) && (h ? (c = h) : (_t([t], !0), (h = t.style.display || h), (c = x.css(t, "display")), _t([t]))),
                            ("inline" === c || ("inline-block" === c && null != h)) &&
                                "none" === x.css(t, "float") &&
                                (l ||
                                    (d.done(function () {
                                        f.display = h;
                                    }),
                                    null == h && ((c = f.display), (h = "none" === c ? "" : c))),
                                (f.display = "inline-block"))),
                        i.overflow &&
                            ((f.overflow = "hidden"),
                            d.always(function () {
                                (f.overflow = i.overflow[0]), (f.overflowX = i.overflow[1]), (f.overflowY = i.overflow[2]);
                            })),
                        (l = !1),
                        p))
                            l ||
                                (m ? "hidden" in m && (g = m.hidden) : (m = st.access(t, "fxshow", { display: h })),
                                o && (m.hidden = !g),
                                g && _t([t], !0),
                                d.done(function () {
                                    for (n in (g || _t([t]), st.remove(t, "fxshow"), p)) x.style(t, n, p[n]);
                                })),
                                (l = me(g ? m[n] : 0, n, d)),
                                n in m || ((m[n] = l.start), g && ((l.end = l.start), (l.start = 0)));
                },
            ],
            prefilter: function (t, e) {
                e ? ve.prefilters.unshift(t) : ve.prefilters.push(t);
            },
        })),
            (x.speed = function (t, e, i) {
                var n = t && "object" == typeof t ? x.extend({}, t) : { complete: i || (!i && e) || (f(t) && t), duration: t, easing: (i && e) || (e && !f(e) && e) };
                return (
                    x.fx.off ? (n.duration = 0) : "number" != typeof n.duration && (n.duration in x.fx.speeds ? (n.duration = x.fx.speeds[n.duration]) : (n.duration = x.fx.speeds._default)),
                    (null != n.queue && !0 !== n.queue) || (n.queue = "fx"),
                    (n.old = n.complete),
                    (n.complete = function () {
                        f(n.old) && n.old.call(this), n.queue && x.dequeue(this, n.queue);
                    }),
                    n
                );
            }),
            x.fn.extend({
                fadeTo: function (t, e, i, n) {
                    return this.filter(gt).css("opacity", 0).show().end().animate({ opacity: e }, t, i, n);
                },
                animate: function (t, e, i, n) {
                    var s = x.isEmptyObject(t),
                        o = x.speed(e, i, n),
                        r = function () {
                            var e = ve(this, x.extend({}, t), o);
                            (s || st.get(this, "finish")) && e.stop(!0);
                        };
                    return (r.finish = r), s || !1 === o.queue ? this.each(r) : this.queue(o.queue, r);
                },
                stop: function (t, e, i) {
                    var n = function (t) {
                        var e = t.stop;
                        delete t.stop, e(i);
                    };
                    return (
                        "string" != typeof t && ((i = e), (e = t), (t = void 0)),
                        e && this.queue(t || "fx", []),
                        this.each(function () {
                            var e = !0,
                                s = null != t && t + "queueHooks",
                                o = x.timers,
                                r = st.get(this);
                            if (s) r[s] && r[s].stop && n(r[s]);
                            else for (s in r) r[s] && r[s].stop && de.test(s) && n(r[s]);
                            for (s = o.length; s--; ) o[s].elem !== this || (null != t && o[s].queue !== t) || (o[s].anim.stop(i), (e = !1), o.splice(s, 1));
                            (!e && i) || x.dequeue(this, t);
                        })
                    );
                },
                finish: function (t) {
                    return (
                        !1 !== t && (t = t || "fx"),
                        this.each(function () {
                            var e,
                                i = st.get(this),
                                n = i[t + "queue"],
                                s = i[t + "queueHooks"],
                                o = x.timers,
                                r = n ? n.length : 0;
                            for (i.finish = !0, x.queue(this, t, []), s && s.stop && s.stop.call(this, !0), e = o.length; e--; ) o[e].elem === this && o[e].queue === t && (o[e].anim.stop(!0), o.splice(e, 1));
                            for (e = 0; e < r; e++) n[e] && n[e].finish && n[e].finish.call(this);
                            delete i.finish;
                        })
                    );
                },
            }),
            x.each(["toggle", "show", "hide"], function (t, e) {
                var i = x.fn[e];
                x.fn[e] = function (t, n, s) {
                    return null == t || "boolean" == typeof t ? i.apply(this, arguments) : this.animate(ge(e, !0), t, n, s);
                };
            }),
            x.each({ slideDown: ge("show"), slideUp: ge("hide"), slideToggle: ge("toggle"), fadeIn: { opacity: "show" }, fadeOut: { opacity: "hide" }, fadeToggle: { opacity: "toggle" } }, function (t, e) {
                x.fn[t] = function (t, i, n) {
                    return this.animate(e, t, i, n);
                };
            }),
            (x.timers = []),
            (x.fx.tick = function () {
                var t,
                    e = 0,
                    i = x.timers;
                for (ae = Date.now(); e < i.length; e++) (t = i[e])() || i[e] !== t || i.splice(e--, 1);
                i.length || x.fx.stop(), (ae = void 0);
            }),
            (x.fx.timer = function (t) {
                x.timers.push(t), x.fx.start();
            }),
            (x.fx.interval = 13),
            (x.fx.start = function () {
                le || ((le = !0), pe());
            }),
            (x.fx.stop = function () {
                le = null;
            }),
            (x.fx.speeds = { slow: 600, fast: 200, _default: 400 }),
            (x.fn.delay = function (e, i) {
                return (
                    (e = (x.fx && x.fx.speeds[e]) || e),
                    (i = i || "fx"),
                    this.queue(i, function (i, n) {
                        var s = t.setTimeout(i, e);
                        n.stop = function () {
                            t.clearTimeout(s);
                        };
                    })
                );
            }),
            (he = m.createElement("input")),
            (ce = m.createElement("select").appendChild(m.createElement("option"))),
            (he.type = "checkbox"),
            (p.checkOn = "" !== he.value),
            (p.optSelected = ce.selected),
            ((he = m.createElement("input")).value = "t"),
            (he.type = "radio"),
            (p.radioValue = "t" === he.value);
        var _e,
            be = x.expr.attrHandle;
        x.fn.extend({
            attr: function (t, e) {
                return Q(this, x.attr, t, e, 1 < arguments.length);
            },
            removeAttr: function (t) {
                return this.each(function () {
                    x.removeAttr(this, t);
                });
            },
        }),
            x.extend({
                attr: function (t, e, i) {
                    var n,
                        s,
                        o = t.nodeType;
                    if (3 !== o && 8 !== o && 2 !== o)
                        return void 0 === t.getAttribute
                            ? x.prop(t, e, i)
                            : ((1 === o && x.isXMLDoc(t)) || (s = x.attrHooks[e.toLowerCase()] || (x.expr.match.bool.test(e) ? _e : void 0)),
                              void 0 !== i
                                  ? null === i
                                      ? void x.removeAttr(t, e)
                                      : s && "set" in s && void 0 !== (n = s.set(t, i, e))
                                      ? n
                                      : (t.setAttribute(e, i + ""), i)
                                  : s && "get" in s && null !== (n = s.get(t, e))
                                  ? n
                                  : null == (n = x.find.attr(t, e))
                                  ? void 0
                                  : n);
                },
                attrHooks: {
                    type: {
                        set: function (t, e) {
                            if (!p.radioValue && "radio" === e && C(t, "input")) {
                                var i = t.value;
                                return t.setAttribute("type", e), i && (t.value = i), e;
                            }
                        },
                    },
                },
                removeAttr: function (t, e) {
                    var i,
                        n = 0,
                        s = e && e.match(B);
                    if (s && 1 === t.nodeType) for (; (i = s[n++]); ) t.removeAttribute(i);
                },
            }),
            (_e = {
                set: function (t, e, i) {
                    return !1 === e ? x.removeAttr(t, i) : t.setAttribute(i, i), i;
                },
            }),
            x.each(x.expr.match.bool.source.match(/\w+/g), function (t, e) {
                var i = be[e] || x.find.attr;
                be[e] = function (t, e, n) {
                    var s,
                        o,
                        r = e.toLowerCase();
                    return n || ((o = be[r]), (be[r] = s), (s = null != i(t, e, n) ? r : null), (be[r] = o)), s;
                };
            });
        var ye = /^(?:input|select|textarea|button)$/i,
            we = /^(?:a|area)$/i;
        function xe(t) {
            return (t.match(B) || []).join(" ");
        }
        function ke(t) {
            return (t.getAttribute && t.getAttribute("class")) || "";
        }
        function Ce(t) {
            return Array.isArray(t) ? t : ("string" == typeof t && t.match(B)) || [];
        }
        x.fn.extend({
            prop: function (t, e) {
                return Q(this, x.prop, t, e, 1 < arguments.length);
            },
            removeProp: function (t) {
                return this.each(function () {
                    delete this[x.propFix[t] || t];
                });
            },
        }),
            x.extend({
                prop: function (t, e, i) {
                    var n,
                        s,
                        o = t.nodeType;
                    if (3 !== o && 8 !== o && 2 !== o)
                        return (
                            (1 === o && x.isXMLDoc(t)) || ((e = x.propFix[e] || e), (s = x.propHooks[e])),
                            void 0 !== i ? (s && "set" in s && void 0 !== (n = s.set(t, i, e)) ? n : (t[e] = i)) : s && "get" in s && null !== (n = s.get(t, e)) ? n : t[e]
                        );
                },
                propHooks: {
                    tabIndex: {
                        get: function (t) {
                            var e = x.find.attr(t, "tabindex");
                            return e ? parseInt(e, 10) : ye.test(t.nodeName) || (we.test(t.nodeName) && t.href) ? 0 : -1;
                        },
                    },
                },
                propFix: { for: "htmlFor", class: "className" },
            }),
            p.optSelected ||
                (x.propHooks.selected = {
                    get: function (t) {
                        var e = t.parentNode;
                        return e && e.parentNode && e.parentNode.selectedIndex, null;
                    },
                    set: function (t) {
                        var e = t.parentNode;
                        e && (e.selectedIndex, e.parentNode && e.parentNode.selectedIndex);
                    },
                }),
            x.each(["tabIndex", "readOnly", "maxLength", "cellSpacing", "cellPadding", "rowSpan", "colSpan", "useMap", "frameBorder", "contentEditable"], function () {
                x.propFix[this.toLowerCase()] = this;
            }),
            x.fn.extend({
                addClass: function (t) {
                    var e, i, n, s, o, r;
                    return f(t)
                        ? this.each(function (e) {
                              x(this).addClass(t.call(this, e, ke(this)));
                          })
                        : (e = Ce(t)).length
                        ? this.each(function () {
                              if (((n = ke(this)), (i = 1 === this.nodeType && " " + xe(n) + " "))) {
                                  for (o = 0; o < e.length; o++) (s = e[o]), i.indexOf(" " + s + " ") < 0 && (i += s + " ");
                                  (r = xe(i)), n !== r && this.setAttribute("class", r);
                              }
                          })
                        : this;
                },
                removeClass: function (t) {
                    var e, i, n, s, o, r;
                    return f(t)
                        ? this.each(function (e) {
                              x(this).removeClass(t.call(this, e, ke(this)));
                          })
                        : arguments.length
                        ? (e = Ce(t)).length
                            ? this.each(function () {
                                  if (((n = ke(this)), (i = 1 === this.nodeType && " " + xe(n) + " "))) {
                                      for (o = 0; o < e.length; o++) for (s = e[o]; -1 < i.indexOf(" " + s + " "); ) i = i.replace(" " + s + " ", " ");
                                      (r = xe(i)), n !== r && this.setAttribute("class", r);
                                  }
                              })
                            : this
                        : this.attr("class", "");
                },
                toggleClass: function (t, e) {
                    var i,
                        n,
                        s,
                        o,
                        r = typeof t,
                        a = "string" === r || Array.isArray(t);
                    return f(t)
                        ? this.each(function (i) {
                              x(this).toggleClass(t.call(this, i, ke(this), e), e);
                          })
                        : "boolean" == typeof e && a
                        ? e
                            ? this.addClass(t)
                            : this.removeClass(t)
                        : ((i = Ce(t)),
                          this.each(function () {
                              if (a) for (o = x(this), s = 0; s < i.length; s++) (n = i[s]), o.hasClass(n) ? o.removeClass(n) : o.addClass(n);
                              else (void 0 !== t && "boolean" !== r) || ((n = ke(this)) && st.set(this, "__className__", n), this.setAttribute && this.setAttribute("class", n || !1 === t ? "" : st.get(this, "__className__") || ""));
                          }));
                },
                hasClass: function (t) {
                    var e,
                        i,
                        n = 0;
                    for (e = " " + t + " "; (i = this[n++]); ) if (1 === i.nodeType && -1 < (" " + xe(ke(i)) + " ").indexOf(e)) return !0;
                    return !1;
                },
            });
        var Te = /\r/g;
        x.fn.extend({
            val: function (t) {
                var e,
                    i,
                    n,
                    s = this[0];
                return arguments.length
                    ? ((n = f(t)),
                      this.each(function (i) {
                          var s;
                          1 === this.nodeType &&
                              (null == (s = n ? t.call(this, i, x(this).val()) : t)
                                  ? (s = "")
                                  : "number" == typeof s
                                  ? (s += "")
                                  : Array.isArray(s) &&
                                    (s = x.map(s, function (t) {
                                        return null == t ? "" : t + "";
                                    })),
                              ((e = x.valHooks[this.type] || x.valHooks[this.nodeName.toLowerCase()]) && "set" in e && void 0 !== e.set(this, s, "value")) || (this.value = s));
                      }))
                    : s
                    ? (e = x.valHooks[s.type] || x.valHooks[s.nodeName.toLowerCase()]) && "get" in e && void 0 !== (i = e.get(s, "value"))
                        ? i
                        : "string" == typeof (i = s.value)
                        ? i.replace(Te, "")
                        : null == i
                        ? ""
                        : i
                    : void 0;
            },
        }),
            x.extend({
                valHooks: {
                    option: {
                        get: function (t) {
                            var e = x.find.attr(t, "value");
                            return null != e ? e : xe(x.text(t));
                        },
                    },
                    select: {
                        get: function (t) {
                            var e,
                                i,
                                n,
                                s = t.options,
                                o = t.selectedIndex,
                                r = "select-one" === t.type,
                                a = r ? null : [],
                                l = r ? o + 1 : s.length;
                            for (n = o < 0 ? l : r ? o : 0; n < l; n++)
                                if (((i = s[n]).selected || n === o) && !i.disabled && (!i.parentNode.disabled || !C(i.parentNode, "optgroup"))) {
                                    if (((e = x(i).val()), r)) return e;
                                    a.push(e);
                                }
                            return a;
                        },
                        set: function (t, e) {
                            for (var i, n, s = t.options, o = x.makeArray(e), r = s.length; r--; ) ((n = s[r]).selected = -1 < x.inArray(x.valHooks.option.get(n), o)) && (i = !0);
                            return i || (t.selectedIndex = -1), o;
                        },
                    },
                },
            }),
            x.each(["radio", "checkbox"], function () {
                (x.valHooks[this] = {
                    set: function (t, e) {
                        if (Array.isArray(e)) return (t.checked = -1 < x.inArray(x(t).val(), e));
                    },
                }),
                    p.checkOn ||
                        (x.valHooks[this].get = function (t) {
                            return null === t.getAttribute("value") ? "on" : t.value;
                        });
            });
        var De = t.location,
            Se = { guid: Date.now() },
            Ae = /\?/;
        x.parseXML = function (e) {
            var i, n;
            if (!e || "string" != typeof e) return null;
            try {
                i = new t.DOMParser().parseFromString(e, "text/xml");
            } catch (e) {}
            return (
                (n = i && i.getElementsByTagName("parsererror")[0]),
                (i && !n) ||
                    x.error(
                        "Invalid XML: " +
                            (n
                                ? x
                                      .map(n.childNodes, function (t) {
                                          return t.textContent;
                                      })
                                      .join("\n")
                                : e)
                    ),
                i
            );
        };
        var Ee = /^(?:focusinfocus|focusoutblur)$/,
            Ie = function (t) {
                t.stopPropagation();
            };
        x.extend(x.event, {
            trigger: function (e, i, n, s) {
                var o,
                    r,
                    a,
                    l,
                    h,
                    u,
                    d,
                    p,
                    v = [n || m],
                    _ = c.call(e, "type") ? e.type : e,
                    b = c.call(e, "namespace") ? e.namespace.split(".") : [];
                if (
                    ((r = p = a = n = n || m),
                    3 !== n.nodeType &&
                        8 !== n.nodeType &&
                        !Ee.test(_ + x.event.triggered) &&
                        (-1 < _.indexOf(".") && ((_ = (b = _.split(".")).shift()), b.sort()),
                        (h = _.indexOf(":") < 0 && "on" + _),
                        ((e = e[x.expando] ? e : new x.Event(_, "object" == typeof e && e)).isTrigger = s ? 2 : 3),
                        (e.namespace = b.join(".")),
                        (e.rnamespace = e.namespace ? new RegExp("(^|\\.)" + b.join("\\.(?:.*\\.|)") + "(\\.|$)") : null),
                        (e.result = void 0),
                        e.target || (e.target = n),
                        (i = null == i ? [e] : x.makeArray(i, [e])),
                        (d = x.event.special[_] || {}),
                        s || !d.trigger || !1 !== d.trigger.apply(n, i)))
                ) {
                    if (!s && !d.noBubble && !g(n)) {
                        for (l = d.delegateType || _, Ee.test(l + _) || (r = r.parentNode); r; r = r.parentNode) v.push(r), (a = r);
                        a === (n.ownerDocument || m) && v.push(a.defaultView || a.parentWindow || t);
                    }
                    for (o = 0; (r = v[o++]) && !e.isPropagationStopped(); )
                        (p = r),
                            (e.type = 1 < o ? l : d.bindType || _),
                            (u = (st.get(r, "events") || Object.create(null))[e.type] && st.get(r, "handle")) && u.apply(r, i),
                            (u = h && r[h]) && u.apply && it(r) && ((e.result = u.apply(r, i)), !1 === e.result && e.preventDefault());
                    return (
                        (e.type = _),
                        s ||
                            e.isDefaultPrevented() ||
                            (d._default && !1 !== d._default.apply(v.pop(), i)) ||
                            !it(n) ||
                            (h &&
                                f(n[_]) &&
                                !g(n) &&
                                ((a = n[h]) && (n[h] = null),
                                (x.event.triggered = _),
                                e.isPropagationStopped() && p.addEventListener(_, Ie),
                                n[_](),
                                e.isPropagationStopped() && p.removeEventListener(_, Ie),
                                (x.event.triggered = void 0),
                                a && (n[h] = a))),
                        e.result
                    );
                }
            },
            simulate: function (t, e, i) {
                var n = x.extend(new x.Event(), i, { type: t, isSimulated: !0 });
                x.event.trigger(n, null, e);
            },
        }),
            x.fn.extend({
                trigger: function (t, e) {
                    return this.each(function () {
                        x.event.trigger(t, e, this);
                    });
                },
                triggerHandler: function (t, e) {
                    var i = this[0];
                    if (i) return x.event.trigger(t, e, i, !0);
                },
            });
        var Pe = /\[\]$/,
            Me = /\r?\n/g,
            Oe = /^(?:submit|button|image|reset|file)$/i,
            He = /^(?:input|select|textarea|keygen)/i;
        function Le(t, e, i, n) {
            var s;
            if (Array.isArray(e))
                x.each(e, function (e, s) {
                    i || Pe.test(t) ? n(t, s) : Le(t + "[" + ("object" == typeof s && null != s ? e : "") + "]", s, i, n);
                });
            else if (i || "object" !== b(e)) n(t, e);
            else for (s in e) Le(t + "[" + s + "]", e[s], i, n);
        }
        (x.param = function (t, e) {
            var i,
                n = [],
                s = function (t, e) {
                    var i = f(e) ? e() : e;
                    n[n.length] = encodeURIComponent(t) + "=" + encodeURIComponent(null == i ? "" : i);
                };
            if (null == t) return "";
            if (Array.isArray(t) || (t.jquery && !x.isPlainObject(t)))
                x.each(t, function () {
                    s(this.name, this.value);
                });
            else for (i in t) Le(i, t[i], e, s);
            return n.join("&");
        }),
            x.fn.extend({
                serialize: function () {
                    return x.param(this.serializeArray());
                },
                serializeArray: function () {
                    return this.map(function () {
                        var t = x.prop(this, "elements");
                        return t ? x.makeArray(t) : this;
                    })
                        .filter(function () {
                            var t = this.type;
                            return this.name && !x(this).is(":disabled") && He.test(this.nodeName) && !Oe.test(t) && (this.checked || !wt.test(t));
                        })
                        .map(function (t, e) {
                            var i = x(this).val();
                            return null == i
                                ? null
                                : Array.isArray(i)
                                ? x.map(i, function (t) {
                                      return { name: e.name, value: t.replace(Me, "\r\n") };
                                  })
                                : { name: e.name, value: i.replace(Me, "\r\n") };
                        })
                        .get();
                },
            });
        var Ne = /%20/g,
            We = /#.*$/,
            Re = /([?&])_=[^&]*/,
            ze = /^(.*?):[ \t]*([^\r\n]*)$/gm,
            je = /^(?:GET|HEAD)$/,
            Fe = /^\/\//,
            qe = {},
            Ye = {},
            Be = "*/".concat("*"),
            $e = m.createElement("a");
        function Xe(t) {
            return function (e, i) {
                "string" != typeof e && ((i = e), (e = "*"));
                var n,
                    s = 0,
                    o = e.toLowerCase().match(B) || [];
                if (f(i)) for (; (n = o[s++]); ) "+" === n[0] ? ((n = n.slice(1) || "*"), (t[n] = t[n] || []).unshift(i)) : (t[n] = t[n] || []).push(i);
            };
        }
        function Ue(t, e, i, n) {
            var s = {},
                o = t === Ye;
            function r(a) {
                var l;
                return (
                    (s[a] = !0),
                    x.each(t[a] || [], function (t, a) {
                        var h = a(e, i, n);
                        return "string" != typeof h || o || s[h] ? (o ? !(l = h) : void 0) : (e.dataTypes.unshift(h), r(h), !1);
                    }),
                    l
                );
            }
            return r(e.dataTypes[0]) || (!s["*"] && r("*"));
        }
        function Ke(t, e) {
            var i,
                n,
                s = x.ajaxSettings.flatOptions || {};
            for (i in e) void 0 !== e[i] && ((s[i] ? t : n || (n = {}))[i] = e[i]);
            return n && x.extend(!0, t, n), t;
        }
        ($e.href = De.href),
            x.extend({
                active: 0,
                lastModified: {},
                etag: {},
                ajaxSettings: {
                    url: De.href,
                    type: "GET",
                    isLocal: /^(?:about|app|app-storage|.+-extension|file|res|widget):$/.test(De.protocol),
                    global: !0,
                    processData: !0,
                    async: !0,
                    contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                    accepts: { "*": Be, text: "text/plain", html: "text/html", xml: "application/xml, text/xml", json: "application/json, text/javascript" },
                    contents: { xml: /\bxml\b/, html: /\bhtml/, json: /\bjson\b/ },
                    responseFields: { xml: "responseXML", text: "responseText", json: "responseJSON" },
                    converters: { "* text": String, "text html": !0, "text json": JSON.parse, "text xml": x.parseXML },
                    flatOptions: { url: !0, context: !0 },
                },
                ajaxSetup: function (t, e) {
                    return e ? Ke(Ke(t, x.ajaxSettings), e) : Ke(x.ajaxSettings, t);
                },
                ajaxPrefilter: Xe(qe),
                ajaxTransport: Xe(Ye),
                ajax: function (e, i) {
                    "object" == typeof e && ((i = e), (e = void 0)), (i = i || {});
                    var n,
                        s,
                        o,
                        r,
                        a,
                        l,
                        h,
                        c,
                        u,
                        d,
                        p = x.ajaxSetup({}, i),
                        f = p.context || p,
                        g = p.context && (f.nodeType || f.jquery) ? x(f) : x.event,
                        v = x.Deferred(),
                        _ = x.Callbacks("once memory"),
                        b = p.statusCode || {},
                        y = {},
                        w = {},
                        k = "canceled",
                        C = {
                            readyState: 0,
                            getResponseHeader: function (t) {
                                var e;
                                if (h) {
                                    if (!r) for (r = {}; (e = ze.exec(o)); ) r[e[1].toLowerCase() + " "] = (r[e[1].toLowerCase() + " "] || []).concat(e[2]);
                                    e = r[t.toLowerCase() + " "];
                                }
                                return null == e ? null : e.join(", ");
                            },
                            getAllResponseHeaders: function () {
                                return h ? o : null;
                            },
                            setRequestHeader: function (t, e) {
                                return null == h && ((t = w[t.toLowerCase()] = w[t.toLowerCase()] || t), (y[t] = e)), this;
                            },
                            overrideMimeType: function (t) {
                                return null == h && (p.mimeType = t), this;
                            },
                            statusCode: function (t) {
                                var e;
                                if (t)
                                    if (h) C.always(t[C.status]);
                                    else for (e in t) b[e] = [b[e], t[e]];
                                return this;
                            },
                            abort: function (t) {
                                var e = t || k;
                                return n && n.abort(e), T(0, e), this;
                            },
                        };
                    if (
                        (v.promise(C),
                        (p.url = ((e || p.url || De.href) + "").replace(Fe, De.protocol + "//")),
                        (p.type = i.method || i.type || p.method || p.type),
                        (p.dataTypes = (p.dataType || "*").toLowerCase().match(B) || [""]),
                        null == p.crossDomain)
                    ) {
                        l = m.createElement("a");
                        try {
                            (l.href = p.url), (l.href = l.href), (p.crossDomain = $e.protocol + "//" + $e.host != l.protocol + "//" + l.host);
                        } catch (e) {
                            p.crossDomain = !0;
                        }
                    }
                    if ((p.data && p.processData && "string" != typeof p.data && (p.data = x.param(p.data, p.traditional)), Ue(qe, p, i, C), h)) return C;
                    for (u in ((c = x.event && p.global) && 0 == x.active++ && x.event.trigger("ajaxStart"),
                    (p.type = p.type.toUpperCase()),
                    (p.hasContent = !je.test(p.type)),
                    (s = p.url.replace(We, "")),
                    p.hasContent
                        ? p.data && p.processData && 0 === (p.contentType || "").indexOf("application/x-www-form-urlencoded") && (p.data = p.data.replace(Ne, "+"))
                        : ((d = p.url.slice(s.length)),
                          p.data && (p.processData || "string" == typeof p.data) && ((s += (Ae.test(s) ? "&" : "?") + p.data), delete p.data),
                          !1 === p.cache && ((s = s.replace(Re, "$1")), (d = (Ae.test(s) ? "&" : "?") + "_=" + Se.guid++ + d)),
                          (p.url = s + d)),
                    p.ifModified && (x.lastModified[s] && C.setRequestHeader("If-Modified-Since", x.lastModified[s]), x.etag[s] && C.setRequestHeader("If-None-Match", x.etag[s])),
                    ((p.data && p.hasContent && !1 !== p.contentType) || i.contentType) && C.setRequestHeader("Content-Type", p.contentType),
                    C.setRequestHeader("Accept", p.dataTypes[0] && p.accepts[p.dataTypes[0]] ? p.accepts[p.dataTypes[0]] + ("*" !== p.dataTypes[0] ? ", " + Be + "; q=0.01" : "") : p.accepts["*"]),
                    p.headers))
                        C.setRequestHeader(u, p.headers[u]);
                    if (p.beforeSend && (!1 === p.beforeSend.call(f, C, p) || h)) return C.abort();
                    if (((k = "abort"), _.add(p.complete), C.done(p.success), C.fail(p.error), (n = Ue(Ye, p, i, C)))) {
                        if (((C.readyState = 1), c && g.trigger("ajaxSend", [C, p]), h)) return C;
                        p.async &&
                            0 < p.timeout &&
                            (a = t.setTimeout(function () {
                                C.abort("timeout");
                            }, p.timeout));
                        try {
                            (h = !1), n.send(y, T);
                        } catch (e) {
                            if (h) throw e;
                            T(-1, e);
                        }
                    } else T(-1, "No Transport");
                    function T(e, i, r, l) {
                        var u,
                            d,
                            m,
                            y,
                            w,
                            k = i;
                        h ||
                            ((h = !0),
                            a && t.clearTimeout(a),
                            (n = void 0),
                            (o = l || ""),
                            (C.readyState = 0 < e ? 4 : 0),
                            (u = (200 <= e && e < 300) || 304 === e),
                            r &&
                                (y = (function (t, e, i) {
                                    for (var n, s, o, r, a = t.contents, l = t.dataTypes; "*" === l[0]; ) l.shift(), void 0 === n && (n = t.mimeType || e.getResponseHeader("Content-Type"));
                                    if (n)
                                        for (s in a)
                                            if (a[s] && a[s].test(n)) {
                                                l.unshift(s);
                                                break;
                                            }
                                    if (l[0] in i) o = l[0];
                                    else {
                                        for (s in i) {
                                            if (!l[0] || t.converters[s + " " + l[0]]) {
                                                o = s;
                                                break;
                                            }
                                            r || (r = s);
                                        }
                                        o = o || r;
                                    }
                                    if (o) return o !== l[0] && l.unshift(o), i[o];
                                })(p, C, r)),
                            !u && -1 < x.inArray("script", p.dataTypes) && x.inArray("json", p.dataTypes) < 0 && (p.converters["text script"] = function () {}),
                            (y = (function (t, e, i, n) {
                                var s,
                                    o,
                                    r,
                                    a,
                                    l,
                                    h = {},
                                    c = t.dataTypes.slice();
                                if (c[1]) for (r in t.converters) h[r.toLowerCase()] = t.converters[r];
                                for (o = c.shift(); o; )
                                    if ((t.responseFields[o] && (i[t.responseFields[o]] = e), !l && n && t.dataFilter && (e = t.dataFilter(e, t.dataType)), (l = o), (o = c.shift())))
                                        if ("*" === o) o = l;
                                        else if ("*" !== l && l !== o) {
                                            if (!(r = h[l + " " + o] || h["* " + o]))
                                                for (s in h)
                                                    if ((a = s.split(" "))[1] === o && (r = h[l + " " + a[0]] || h["* " + a[0]])) {
                                                        !0 === r ? (r = h[s]) : !0 !== h[s] && ((o = a[0]), c.unshift(a[1]));
                                                        break;
                                                    }
                                            if (!0 !== r)
                                                if (r && t.throws) e = r(e);
                                                else
                                                    try {
                                                        e = r(e);
                                                    } catch (t) {
                                                        return { state: "parsererror", error: r ? t : "No conversion from " + l + " to " + o };
                                                    }
                                        }
                                return { state: "success", data: e };
                            })(p, y, C, u)),
                            u
                                ? (p.ifModified && ((w = C.getResponseHeader("Last-Modified")) && (x.lastModified[s] = w), (w = C.getResponseHeader("etag")) && (x.etag[s] = w)),
                                  204 === e || "HEAD" === p.type ? (k = "nocontent") : 304 === e ? (k = "notmodified") : ((k = y.state), (d = y.data), (u = !(m = y.error))))
                                : ((m = k), (!e && k) || ((k = "error"), e < 0 && (e = 0))),
                            (C.status = e),
                            (C.statusText = (i || k) + ""),
                            u ? v.resolveWith(f, [d, k, C]) : v.rejectWith(f, [C, k, m]),
                            C.statusCode(b),
                            (b = void 0),
                            c && g.trigger(u ? "ajaxSuccess" : "ajaxError", [C, p, u ? d : m]),
                            _.fireWith(f, [C, k]),
                            c && (g.trigger("ajaxComplete", [C, p]), --x.active || x.event.trigger("ajaxStop")));
                    }
                    return C;
                },
                getJSON: function (t, e, i) {
                    return x.get(t, e, i, "json");
                },
                getScript: function (t, e) {
                    return x.get(t, void 0, e, "script");
                },
            }),
            x.each(["get", "post"], function (t, e) {
                x[e] = function (t, i, n, s) {
                    return f(i) && ((s = s || n), (n = i), (i = void 0)), x.ajax(x.extend({ url: t, type: e, dataType: s, data: i, success: n }, x.isPlainObject(t) && t));
                };
            }),
            x.ajaxPrefilter(function (t) {
                var e;
                for (e in t.headers) "content-type" === e.toLowerCase() && (t.contentType = t.headers[e] || "");
            }),
            (x._evalUrl = function (t, e, i) {
                return x.ajax({
                    url: t,
                    type: "GET",
                    dataType: "script",
                    cache: !0,
                    async: !1,
                    global: !1,
                    converters: { "text script": function () {} },
                    dataFilter: function (t) {
                        x.globalEval(t, e, i);
                    },
                });
            }),
            x.fn.extend({
                wrapAll: function (t) {
                    var e;
                    return (
                        this[0] &&
                            (f(t) && (t = t.call(this[0])),
                            (e = x(t, this[0].ownerDocument).eq(0).clone(!0)),
                            this[0].parentNode && e.insertBefore(this[0]),
                            e
                                .map(function () {
                                    for (var t = this; t.firstElementChild; ) t = t.firstElementChild;
                                    return t;
                                })
                                .append(this)),
                        this
                    );
                },
                wrapInner: function (t) {
                    return f(t)
                        ? this.each(function (e) {
                              x(this).wrapInner(t.call(this, e));
                          })
                        : this.each(function () {
                              var e = x(this),
                                  i = e.contents();
                              i.length ? i.wrapAll(t) : e.append(t);
                          });
                },
                wrap: function (t) {
                    var e = f(t);
                    return this.each(function (i) {
                        x(this).wrapAll(e ? t.call(this, i) : t);
                    });
                },
                unwrap: function (t) {
                    return (
                        this.parent(t)
                            .not("body")
                            .each(function () {
                                x(this).replaceWith(this.childNodes);
                            }),
                        this
                    );
                },
            }),
            (x.expr.pseudos.hidden = function (t) {
                return !x.expr.pseudos.visible(t);
            }),
            (x.expr.pseudos.visible = function (t) {
                return !!(t.offsetWidth || t.offsetHeight || t.getClientRects().length);
            }),
            (x.ajaxSettings.xhr = function () {
                try {
                    return new t.XMLHttpRequest();
                } catch (t) {}
            });
        var Ve = { 0: 200, 1223: 204 },
            Ge = x.ajaxSettings.xhr();
        (p.cors = !!Ge && "withCredentials" in Ge),
            (p.ajax = Ge = !!Ge),
            x.ajaxTransport(function (e) {
                var i, n;
                if (p.cors || (Ge && !e.crossDomain))
                    return {
                        send: function (s, o) {
                            var r,
                                a = e.xhr();
                            if ((a.open(e.type, e.url, e.async, e.username, e.password), e.xhrFields)) for (r in e.xhrFields) a[r] = e.xhrFields[r];
                            for (r in (e.mimeType && a.overrideMimeType && a.overrideMimeType(e.mimeType), e.crossDomain || s["X-Requested-With"] || (s["X-Requested-With"] = "XMLHttpRequest"), s)) a.setRequestHeader(r, s[r]);
                            (i = function (t) {
                                return function () {
                                    i &&
                                        ((i = n = a.onload = a.onerror = a.onabort = a.ontimeout = a.onreadystatechange = null),
                                        "abort" === t
                                            ? a.abort()
                                            : "error" === t
                                            ? "number" != typeof a.status
                                                ? o(0, "error")
                                                : o(a.status, a.statusText)
                                            : o(
                                                  Ve[a.status] || a.status,
                                                  a.statusText,
                                                  "text" !== (a.responseType || "text") || "string" != typeof a.responseText ? { binary: a.response } : { text: a.responseText },
                                                  a.getAllResponseHeaders()
                                              ));
                                };
                            }),
                                (a.onload = i()),
                                (n = a.onerror = a.ontimeout = i("error")),
                                void 0 !== a.onabort
                                    ? (a.onabort = n)
                                    : (a.onreadystatechange = function () {
                                          4 === a.readyState &&
                                              t.setTimeout(function () {
                                                  i && n();
                                              });
                                      }),
                                (i = i("abort"));
                            try {
                                a.send((e.hasContent && e.data) || null);
                            } catch (s) {
                                if (i) throw s;
                            }
                        },
                        abort: function () {
                            i && i();
                        },
                    };
            }),
            x.ajaxPrefilter(function (t) {
                t.crossDomain && (t.contents.script = !1);
            }),
            x.ajaxSetup({
                accepts: { script: "text/javascript, application/javascript, application/ecmascript, application/x-ecmascript" },
                contents: { script: /\b(?:java|ecma)script\b/ },
                converters: {
                    "text script": function (t) {
                        return x.globalEval(t), t;
                    },
                },
            }),
            x.ajaxPrefilter("script", function (t) {
                void 0 === t.cache && (t.cache = !1), t.crossDomain && (t.type = "GET");
            }),
            x.ajaxTransport("script", function (t) {
                var e, i;
                if (t.crossDomain || t.scriptAttrs)
                    return {
                        send: function (n, s) {
                            (e = x("<script>")
                                .attr(t.scriptAttrs || {})
                                .prop({ charset: t.scriptCharset, src: t.url })
                                .on(
                                    "load error",
                                    (i = function (t) {
                                        e.remove(), (i = null), t && s("error" === t.type ? 404 : 200, t.type);
                                    })
                                )),
                                m.head.appendChild(e[0]);
                        },
                        abort: function () {
                            i && i();
                        },
                    };
            });
        var Qe,
            Je = [],
            Ze = /(=)\?(?=&|$)|\?\?/;
        x.ajaxSetup({
            jsonp: "callback",
            jsonpCallback: function () {
                var t = Je.pop() || x.expando + "_" + Se.guid++;
                return (this[t] = !0), t;
            },
        }),
            x.ajaxPrefilter("json jsonp", function (e, i, n) {
                var s,
                    o,
                    r,
                    a = !1 !== e.jsonp && (Ze.test(e.url) ? "url" : "string" == typeof e.data && 0 === (e.contentType || "").indexOf("application/x-www-form-urlencoded") && Ze.test(e.data) && "data");
                if (a || "jsonp" === e.dataTypes[0])
                    return (
                        (s = e.jsonpCallback = f(e.jsonpCallback) ? e.jsonpCallback() : e.jsonpCallback),
                        a ? (e[a] = e[a].replace(Ze, "$1" + s)) : !1 !== e.jsonp && (e.url += (Ae.test(e.url) ? "&" : "?") + e.jsonp + "=" + s),
                        (e.converters["script json"] = function () {
                            return r || x.error(s + " was not called"), r[0];
                        }),
                        (e.dataTypes[0] = "json"),
                        (o = t[s]),
                        (t[s] = function () {
                            r = arguments;
                        }),
                        n.always(function () {
                            void 0 === o ? x(t).removeProp(s) : (t[s] = o), e[s] && ((e.jsonpCallback = i.jsonpCallback), Je.push(s)), r && f(o) && o(r[0]), (r = o = void 0);
                        }),
                        "script"
                    );
            }),
            (p.createHTMLDocument = (((Qe = m.implementation.createHTMLDocument("").body).innerHTML = "<form></form><form></form>"), 2 === Qe.childNodes.length)),
            (x.parseHTML = function (t, e, i) {
                return "string" != typeof t
                    ? []
                    : ("boolean" == typeof e && ((i = e), (e = !1)),
                      e || (p.createHTMLDocument ? (((n = (e = m.implementation.createHTMLDocument("")).createElement("base")).href = m.location.href), e.head.appendChild(n)) : (e = m)),
                      (o = !i && []),
                      (s = W.exec(t)) ? [e.createElement(s[1])] : ((s = At([t], e, o)), o && o.length && x(o).remove(), x.merge([], s.childNodes)));
                var n, s, o;
            }),
            (x.fn.load = function (t, e, i) {
                var n,
                    s,
                    o,
                    r = this,
                    a = t.indexOf(" ");
                return (
                    -1 < a && ((n = xe(t.slice(a))), (t = t.slice(0, a))),
                    f(e) ? ((i = e), (e = void 0)) : e && "object" == typeof e && (s = "POST"),
                    0 < r.length &&
                        x
                            .ajax({ url: t, type: s || "GET", dataType: "html", data: e })
                            .done(function (t) {
                                (o = arguments), r.html(n ? x("<div>").append(x.parseHTML(t)).find(n) : t);
                            })
                            .always(
                                i &&
                                    function (t, e) {
                                        r.each(function () {
                                            i.apply(this, o || [t.responseText, e, t]);
                                        });
                                    }
                            ),
                    this
                );
            }),
            (x.expr.pseudos.animated = function (t) {
                return x.grep(x.timers, function (e) {
                    return t === e.elem;
                }).length;
            }),
            (x.offset = {
                setOffset: function (t, e, i) {
                    var n,
                        s,
                        o,
                        r,
                        a,
                        l,
                        h = x.css(t, "position"),
                        c = x(t),
                        u = {};
                    "static" === h && (t.style.position = "relative"),
                        (a = c.offset()),
                        (o = x.css(t, "top")),
                        (l = x.css(t, "left")),
                        ("absolute" === h || "fixed" === h) && -1 < (o + l).indexOf("auto") ? ((r = (n = c.position()).top), (s = n.left)) : ((r = parseFloat(o) || 0), (s = parseFloat(l) || 0)),
                        f(e) && (e = e.call(t, i, x.extend({}, a))),
                        null != e.top && (u.top = e.top - a.top + r),
                        null != e.left && (u.left = e.left - a.left + s),
                        "using" in e ? e.using.call(t, u) : c.css(u);
                },
            }),
            x.fn.extend({
                offset: function (t) {
                    if (arguments.length)
                        return void 0 === t
                            ? this
                            : this.each(function (e) {
                                  x.offset.setOffset(this, t, e);
                              });
                    var e,
                        i,
                        n = this[0];
                    return n ? (n.getClientRects().length ? ((e = n.getBoundingClientRect()), (i = n.ownerDocument.defaultView), { top: e.top + i.pageYOffset, left: e.left + i.pageXOffset }) : { top: 0, left: 0 }) : void 0;
                },
                position: function () {
                    if (this[0]) {
                        var t,
                            e,
                            i,
                            n = this[0],
                            s = { top: 0, left: 0 };
                        if ("fixed" === x.css(n, "position")) e = n.getBoundingClientRect();
                        else {
                            for (e = this.offset(), i = n.ownerDocument, t = n.offsetParent || i.documentElement; t && (t === i.body || t === i.documentElement) && "static" === x.css(t, "position"); ) t = t.parentNode;
                            t && t !== n && 1 === t.nodeType && (((s = x(t).offset()).top += x.css(t, "borderTopWidth", !0)), (s.left += x.css(t, "borderLeftWidth", !0)));
                        }
                        return { top: e.top - s.top - x.css(n, "marginTop", !0), left: e.left - s.left - x.css(n, "marginLeft", !0) };
                    }
                },
                offsetParent: function () {
                    return this.map(function () {
                        for (var t = this.offsetParent; t && "static" === x.css(t, "position"); ) t = t.offsetParent;
                        return t || dt;
                    });
                },
            }),
            x.each({ scrollLeft: "pageXOffset", scrollTop: "pageYOffset" }, function (t, e) {
                var i = "pageYOffset" === e;
                x.fn[t] = function (n) {
                    return Q(
                        this,
                        function (t, n, s) {
                            var o;
                            if ((g(t) ? (o = t) : 9 === t.nodeType && (o = t.defaultView), void 0 === s)) return o ? o[e] : t[n];
                            o ? o.scrollTo(i ? o.pageXOffset : s, i ? s : o.pageYOffset) : (t[n] = s);
                        },
                        t,
                        n,
                        arguments.length
                    );
                };
            }),
            x.each(["top", "left"], function (t, e) {
                x.cssHooks[e] = Vt(p.pixelPosition, function (t, i) {
                    if (i) return (i = Kt(t, e)), Yt.test(i) ? x(t).position()[e] + "px" : i;
                });
            }),
            x.each({ Height: "height", Width: "width" }, function (t, e) {
                x.each({ padding: "inner" + t, content: e, "": "outer" + t }, function (i, n) {
                    x.fn[n] = function (s, o) {
                        var r = arguments.length && (i || "boolean" != typeof s),
                            a = i || (!0 === s || !0 === o ? "margin" : "border");
                        return Q(
                            this,
                            function (e, i, s) {
                                var o;
                                return g(e)
                                    ? 0 === n.indexOf("outer")
                                        ? e["inner" + t]
                                        : e.document.documentElement["client" + t]
                                    : 9 === e.nodeType
                                    ? ((o = e.documentElement), Math.max(e.body["scroll" + t], o["scroll" + t], e.body["offset" + t], o["offset" + t], o["client" + t]))
                                    : void 0 === s
                                    ? x.css(e, i, a)
                                    : x.style(e, i, s, a);
                            },
                            e,
                            r ? s : void 0,
                            r
                        );
                    };
                });
            }),
            x.each(["ajaxStart", "ajaxStop", "ajaxComplete", "ajaxError", "ajaxSuccess", "ajaxSend"], function (t, e) {
                x.fn[e] = function (t) {
                    return this.on(e, t);
                };
            }),
            x.fn.extend({
                bind: function (t, e, i) {
                    return this.on(t, null, e, i);
                },
                unbind: function (t, e) {
                    return this.off(t, null, e);
                },
                delegate: function (t, e, i, n) {
                    return this.on(e, t, i, n);
                },
                undelegate: function (t, e, i) {
                    return 1 === arguments.length ? this.off(t, "**") : this.off(e, t || "**", i);
                },
                hover: function (t, e) {
                    return this.on("mouseenter", t).on("mouseleave", e || t);
                },
            }),
            x.each("blur focus focusin focusout resize scroll click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup contextmenu".split(" "), function (t, e) {
                x.fn[e] = function (t, i) {
                    return 0 < arguments.length ? this.on(e, null, t, i) : this.trigger(e);
                };
            });
        var ti = /^[\s\uFEFF\xA0]+|([^\s\uFEFF\xA0])[\s\uFEFF\xA0]+$/g;
        (x.proxy = function (t, e) {
            var i, n, o;
            if (("string" == typeof e && ((i = t[e]), (e = t), (t = i)), f(t)))
                return (
                    (n = s.call(arguments, 2)),
                    ((o = function () {
                        return t.apply(e || this, n.concat(s.call(arguments)));
                    }).guid = t.guid = t.guid || x.guid++),
                    o
                );
        }),
            (x.holdReady = function (t) {
                t ? x.readyWait++ : x.ready(!0);
            }),
            (x.isArray = Array.isArray),
            (x.parseJSON = JSON.parse),
            (x.nodeName = C),
            (x.isFunction = f),
            (x.isWindow = g),
            (x.camelCase = et),
            (x.type = b),
            (x.now = Date.now),
            (x.isNumeric = function (t) {
                var e = x.type(t);
                return ("number" === e || "string" === e) && !isNaN(t - parseFloat(t));
            }),
            (x.trim = function (t) {
                return null == t ? "" : (t + "").replace(ti, "$1");
            }),
            "function" == typeof define &&
                define.amd &&
                define("jquery", [], function () {
                    return x;
                });
        var ei = t.jQuery,
            ii = t.$;
        return (
            (x.noConflict = function (e) {
                return t.$ === x && (t.$ = ii), e && t.jQuery === x && (t.jQuery = ei), x;
            }),
            void 0 === e && (t.jQuery = t.$ = x),
            x
        );
    }),
    (function (t) {
        "use strict";
        "function" == typeof define && define.amd ? define(["jquery"], t) : t(jQuery);
    })(function (t) {
        "use strict";
        (t.ui = t.ui || {}), (t.ui.version = "1.13.2");
        var e,
            i,
            n,
            s,
            o,
            r,
            a,
            l,
            h,
            c,
            u = 0,
            d = Array.prototype.hasOwnProperty,
            p = Array.prototype.slice;
        function f(t, e, i) {
            return [parseFloat(t[0]) * (h.test(t[0]) ? e / 100 : 1), parseFloat(t[1]) * (h.test(t[1]) ? i / 100 : 1)];
        }
        function g(e, i) {
            return parseInt(t.css(e, i), 10) || 0;
        }
        function m(t) {
            return null != t && t === t.window;
        }
        (t.cleanData =
            ((e = t.cleanData),
            function (i) {
                for (var n, s, o = 0; null != (s = i[o]); o++) (n = t._data(s, "events")) && n.remove && t(s).triggerHandler("remove");
                e(i);
            })),
            (t.widget = function (e, i, n) {
                var s,
                    o,
                    r,
                    a = {},
                    l = e.split(".")[0],
                    h = l + "-" + (e = e.split(".")[1]);
                return (
                    n || ((n = i), (i = t.Widget)),
                    Array.isArray(n) && (n = t.extend.apply(null, [{}].concat(n))),
                    (t.expr.pseudos[h.toLowerCase()] = function (e) {
                        return !!t.data(e, h);
                    }),
                    (t[l] = t[l] || {}),
                    (s = t[l][e]),
                    (o = t[l][e] = function (t, e) {
                        if (!this || !this._createWidget) return new o(t, e);
                        arguments.length && this._createWidget(t, e);
                    }),
                    t.extend(o, s, { version: n.version, _proto: t.extend({}, n), _childConstructors: [] }),
                    ((r = new i()).options = t.widget.extend({}, r.options)),
                    t.each(n, function (t, e) {
                        function n() {
                            return i.prototype[t].apply(this, arguments);
                        }
                        function s(e) {
                            return i.prototype[t].apply(this, e);
                        }
                        a[t] =
                            "function" == typeof e
                                ? function () {
                                      var t,
                                          i = this._super,
                                          o = this._superApply;
                                      return (this._super = n), (this._superApply = s), (t = e.apply(this, arguments)), (this._super = i), (this._superApply = o), t;
                                  }
                                : e;
                    }),
                    (o.prototype = t.widget.extend(r, { widgetEventPrefix: (s && r.widgetEventPrefix) || e }, a, { constructor: o, namespace: l, widgetName: e, widgetFullName: h })),
                    s
                        ? (t.each(s._childConstructors, function (e, i) {
                              var n = i.prototype;
                              t.widget(n.namespace + "." + n.widgetName, o, i._proto);
                          }),
                          delete s._childConstructors)
                        : i._childConstructors.push(o),
                    t.widget.bridge(e, o),
                    o
                );
            }),
            (t.widget.extend = function (e) {
                for (var i, n, s = p.call(arguments, 1), o = 0, r = s.length; o < r; o++)
                    for (i in s[o]) (n = s[o][i]), d.call(s[o], i) && void 0 !== n && (t.isPlainObject(n) ? (e[i] = t.isPlainObject(e[i]) ? t.widget.extend({}, e[i], n) : t.widget.extend({}, n)) : (e[i] = n));
                return e;
            }),
            (t.widget.bridge = function (e, i) {
                var n = i.prototype.widgetFullName || e;
                t.fn[e] = function (s) {
                    var o = "string" == typeof s,
                        r = p.call(arguments, 1),
                        a = this;
                    return (
                        o
                            ? this.length || "instance" !== s
                                ? this.each(function () {
                                      var i,
                                          o = t.data(this, n);
                                      return "instance" === s
                                          ? ((a = o), !1)
                                          : o
                                          ? "function" != typeof o[s] || "_" === s.charAt(0)
                                              ? t.error("no such method '" + s + "' for " + e + " widget instance")
                                              : (i = o[s].apply(o, r)) !== o && void 0 !== i
                                              ? ((a = i && i.jquery ? a.pushStack(i.get()) : i), !1)
                                              : void 0
                                          : t.error("cannot call methods on " + e + " prior to initialization; attempted to call method '" + s + "'");
                                  })
                                : (a = void 0)
                            : (r.length && (s = t.widget.extend.apply(null, [s].concat(r))),
                              this.each(function () {
                                  var e = t.data(this, n);
                                  e ? (e.option(s || {}), e._init && e._init()) : t.data(this, n, new i(s, this));
                              })),
                        a
                    );
                };
            }),
            (t.Widget = function () {}),
            (t.Widget._childConstructors = []),
            (t.Widget.prototype = {
                widgetName: "widget",
                widgetEventPrefix: "",
                defaultElement: "<div>",
                options: { classes: {}, disabled: !1, create: null },
                _createWidget: function (e, i) {
                    (i = t(i || this.defaultElement || this)[0]),
                        (this.element = t(i)),
                        (this.uuid = u++),
                        (this.eventNamespace = "." + this.widgetName + this.uuid),
                        (this.bindings = t()),
                        (this.hoverable = t()),
                        (this.focusable = t()),
                        (this.classesElementLookup = {}),
                        i !== this &&
                            (t.data(i, this.widgetFullName, this),
                            this._on(!0, this.element, {
                                remove: function (t) {
                                    t.target === i && this.destroy();
                                },
                            }),
                            (this.document = t(i.style ? i.ownerDocument : i.document || i)),
                            (this.window = t(this.document[0].defaultView || this.document[0].parentWindow))),
                        (this.options = t.widget.extend({}, this.options, this._getCreateOptions(), e)),
                        this._create(),
                        this.options.disabled && this._setOptionDisabled(this.options.disabled),
                        this._trigger("create", null, this._getCreateEventData()),
                        this._init();
                },
                _getCreateOptions: function () {
                    return {};
                },
                _getCreateEventData: t.noop,
                _create: t.noop,
                _init: t.noop,
                destroy: function () {
                    var e = this;
                    this._destroy(),
                        t.each(this.classesElementLookup, function (t, i) {
                            e._removeClass(i, t);
                        }),
                        this.element.off(this.eventNamespace).removeData(this.widgetFullName),
                        this.widget().off(this.eventNamespace).removeAttr("aria-disabled"),
                        this.bindings.off(this.eventNamespace);
                },
                _destroy: t.noop,
                widget: function () {
                    return this.element;
                },
                option: function (e, i) {
                    var n,
                        s,
                        o,
                        r = e;
                    if (0 === arguments.length) return t.widget.extend({}, this.options);
                    if ("string" == typeof e)
                        if (((r = {}), (e = (n = e.split(".")).shift()), n.length)) {
                            for (s = r[e] = t.widget.extend({}, this.options[e]), o = 0; o < n.length - 1; o++) (s[n[o]] = s[n[o]] || {}), (s = s[n[o]]);
                            if (((e = n.pop()), 1 === arguments.length)) return void 0 === s[e] ? null : s[e];
                            s[e] = i;
                        } else {
                            if (1 === arguments.length) return void 0 === this.options[e] ? null : this.options[e];
                            r[e] = i;
                        }
                    return this._setOptions(r), this;
                },
                _setOptions: function (t) {
                    for (var e in t) this._setOption(e, t[e]);
                    return this;
                },
                _setOption: function (t, e) {
                    return "classes" === t && this._setOptionClasses(e), (this.options[t] = e), "disabled" === t && this._setOptionDisabled(e), this;
                },
                _setOptionClasses: function (e) {
                    var i, n, s;
                    for (i in e) (s = this.classesElementLookup[i]), e[i] !== this.options.classes[i] && s && s.length && ((n = t(s.get())), this._removeClass(s, i), n.addClass(this._classes({ element: n, keys: i, classes: e, add: !0 })));
                },
                _setOptionDisabled: function (t) {
                    this._toggleClass(this.widget(), this.widgetFullName + "-disabled", null, !!t), t && (this._removeClass(this.hoverable, null, "ui-state-hover"), this._removeClass(this.focusable, null, "ui-state-focus"));
                },
                enable: function () {
                    return this._setOptions({ disabled: !1 });
                },
                disable: function () {
                    return this._setOptions({ disabled: !0 });
                },
                _classes: function (e) {
                    var i = [],
                        n = this;
                    function s(s, o) {
                        for (var r, a = 0; a < s.length; a++)
                            (r = n.classesElementLookup[s[a]] || t()),
                                (r = e.add
                                    ? ((function () {
                                          var i = [];
                                          e.element.each(function (e, s) {
                                              t
                                                  .map(n.classesElementLookup, function (t) {
                                                      return t;
                                                  })
                                                  .some(function (t) {
                                                      return t.is(s);
                                                  }) || i.push(s);
                                          }),
                                              n._on(t(i), { remove: "_untrackClassesElement" });
                                      })(),
                                      t(t.uniqueSort(r.get().concat(e.element.get()))))
                                    : t(r.not(e.element).get())),
                                (n.classesElementLookup[s[a]] = r),
                                i.push(s[a]),
                                o && e.classes[s[a]] && i.push(e.classes[s[a]]);
                    }
                    return (e = t.extend({ element: this.element, classes: this.options.classes || {} }, e)).keys && s(e.keys.match(/\S+/g) || [], !0), e.extra && s(e.extra.match(/\S+/g) || []), i.join(" ");
                },
                _untrackClassesElement: function (e) {
                    var i = this;
                    t.each(i.classesElementLookup, function (n, s) {
                        -1 !== t.inArray(e.target, s) && (i.classesElementLookup[n] = t(s.not(e.target).get()));
                    }),
                        this._off(t(e.target));
                },
                _removeClass: function (t, e, i) {
                    return this._toggleClass(t, e, i, !1);
                },
                _addClass: function (t, e, i) {
                    return this._toggleClass(t, e, i, !0);
                },
                _toggleClass: function (t, e, i, n) {
                    var s = "string" == typeof t || null === t;
                    return (i = { extra: s ? e : i, keys: s ? t : e, element: s ? this.element : t, add: (n = "boolean" == typeof n ? n : i) }).element.toggleClass(this._classes(i), n), this;
                },
                _on: function (e, i, n) {
                    var s,
                        o = this;
                    "boolean" != typeof e && ((n = i), (i = e), (e = !1)),
                        n ? ((i = s = t(i)), (this.bindings = this.bindings.add(i))) : ((n = i), (i = this.element), (s = this.widget())),
                        t.each(n, function (n, r) {
                            function a() {
                                if (e || (!0 !== o.options.disabled && !t(this).hasClass("ui-state-disabled"))) return ("string" == typeof r ? o[r] : r).apply(o, arguments);
                            }
                            "string" != typeof r && (a.guid = r.guid = r.guid || a.guid || t.guid++);
                            var l;
                            n = (l = n.match(/^([\w:-]*)\s*(.*)$/))[1] + o.eventNamespace;
                            (l = l[2]) ? s.on(n, l, a) : i.on(n, a);
                        });
                },
                _off: function (e, i) {
                    (i = (i || "").split(" ").join(this.eventNamespace + " ") + this.eventNamespace),
                        e.off(i),
                        (this.bindings = t(this.bindings.not(e).get())),
                        (this.focusable = t(this.focusable.not(e).get())),
                        (this.hoverable = t(this.hoverable.not(e).get()));
                },
                _delay: function (t, e) {
                    var i = this;
                    return setTimeout(function () {
                        return ("string" == typeof t ? i[t] : t).apply(i, arguments);
                    }, e || 0);
                },
                _hoverable: function (e) {
                    (this.hoverable = this.hoverable.add(e)),
                        this._on(e, {
                            mouseenter: function (e) {
                                this._addClass(t(e.currentTarget), null, "ui-state-hover");
                            },
                            mouseleave: function (e) {
                                this._removeClass(t(e.currentTarget), null, "ui-state-hover");
                            },
                        });
                },
                _focusable: function (e) {
                    (this.focusable = this.focusable.add(e)),
                        this._on(e, {
                            focusin: function (e) {
                                this._addClass(t(e.currentTarget), null, "ui-state-focus");
                            },
                            focusout: function (e) {
                                this._removeClass(t(e.currentTarget), null, "ui-state-focus");
                            },
                        });
                },
                _trigger: function (e, i, n) {
                    var s,
                        o,
                        r = this.options[e];
                    if (((n = n || {}), ((i = t.Event(i)).type = (e === this.widgetEventPrefix ? e : this.widgetEventPrefix + e).toLowerCase()), (i.target = this.element[0]), (o = i.originalEvent))) for (s in o) s in i || (i[s] = o[s]);
                    return this.element.trigger(i, n), !(("function" == typeof r && !1 === r.apply(this.element[0], [i].concat(n))) || i.isDefaultPrevented());
                },
            }),
            t.each({ show: "fadeIn", hide: "fadeOut" }, function (e, i) {
                t.Widget.prototype["_" + e] = function (n, s, o) {
                    var r,
                        a = (s = "string" == typeof s ? { effect: s } : s) ? (!0 !== s && "number" != typeof s && s.effect) || i : e;
                    "number" == typeof (s = s || {}) ? (s = { duration: s }) : !0 === s && (s = {}),
                        (r = !t.isEmptyObject(s)),
                        (s.complete = o),
                        s.delay && n.delay(s.delay),
                        r && t.effects && t.effects.effect[a]
                            ? n[e](s)
                            : a !== e && n[a]
                            ? n[a](s.duration, s.easing, o)
                            : n.queue(function (i) {
                                  t(this)[e](), o && o.call(n[0]), i();
                              });
                };
            }),
            t.widget,
            (n = Math.max),
            (s = Math.abs),
            (o = /left|center|right/),
            (r = /top|center|bottom/),
            (a = /[\+\-]\d+(\.[\d]+)?%?/),
            (l = /^\w+/),
            (h = /%$/),
            (c = t.fn.position),
            (t.position = {
                scrollbarWidth: function () {
                    if (void 0 !== i) return i;
                    var e,
                        n = t("<div style='display:block;position:absolute;width:200px;height:200px;overflow:hidden;'><div style='height:300px;width:auto;'></div></div>"),
                        s = n.children()[0];
                    return t("body").append(n), (e = s.offsetWidth), n.css("overflow", "scroll"), e === (s = s.offsetWidth) && (s = n[0].clientWidth), n.remove(), (i = e - s);
                },
                getScrollInfo: function (e) {
                    var i = e.isWindow || e.isDocument ? "" : e.element.css("overflow-x"),
                        n = e.isWindow || e.isDocument ? "" : e.element.css("overflow-y");
                    i = "scroll" === i || ("auto" === i && e.width < e.element[0].scrollWidth);
                    return { width: "scroll" === n || ("auto" === n && e.height < e.element[0].scrollHeight) ? t.position.scrollbarWidth() : 0, height: i ? t.position.scrollbarWidth() : 0 };
                },
                getWithinInfo: function (e) {
                    var i = t(e || window),
                        n = m(i[0]),
                        s = !!i[0] && 9 === i[0].nodeType;
                    return { element: i, isWindow: n, isDocument: s, offset: n || s ? { left: 0, top: 0 } : t(e).offset(), scrollLeft: i.scrollLeft(), scrollTop: i.scrollTop(), width: i.outerWidth(), height: i.outerHeight() };
                },
            }),
            (t.fn.position = function (e) {
                if (!e || !e.of) return c.apply(this, arguments);
                var i,
                    h,
                    u,
                    d,
                    p,
                    v,
                    _ = "string" == typeof (e = t.extend({}, e)).of ? t(document).find(e.of) : t(e.of),
                    b = t.position.getWithinInfo(e.within),
                    y = t.position.getScrollInfo(b),
                    w = (e.collision || "flip").split(" "),
                    x = {},
                    k =
                        9 === (v = (k = _)[0]).nodeType
                            ? { width: k.width(), height: k.height(), offset: { top: 0, left: 0 } }
                            : m(v)
                            ? { width: k.width(), height: k.height(), offset: { top: k.scrollTop(), left: k.scrollLeft() } }
                            : v.preventDefault
                            ? { width: 0, height: 0, offset: { top: v.pageY, left: v.pageX } }
                            : { width: k.outerWidth(), height: k.outerHeight(), offset: k.offset() };
                return (
                    _[0].preventDefault && (e.at = "left top"),
                    (h = k.width),
                    (u = k.height),
                    (p = t.extend({}, (d = k.offset))),
                    t.each(["my", "at"], function () {
                        var t,
                            i,
                            n = (e[this] || "").split(" ");
                        ((n = 1 === n.length ? (o.test(n[0]) ? n.concat(["center"]) : r.test(n[0]) ? ["center"].concat(n) : ["center", "center"]) : n)[0] = o.test(n[0]) ? n[0] : "center"),
                            (n[1] = r.test(n[1]) ? n[1] : "center"),
                            (t = a.exec(n[0])),
                            (i = a.exec(n[1])),
                            (x[this] = [t ? t[0] : 0, i ? i[0] : 0]),
                            (e[this] = [l.exec(n[0])[0], l.exec(n[1])[0]]);
                    }),
                    1 === w.length && (w[1] = w[0]),
                    "right" === e.at[0] ? (p.left += h) : "center" === e.at[0] && (p.left += h / 2),
                    "bottom" === e.at[1] ? (p.top += u) : "center" === e.at[1] && (p.top += u / 2),
                    (i = f(x.at, h, u)),
                    (p.left += i[0]),
                    (p.top += i[1]),
                    this.each(function () {
                        var o,
                            r,
                            a = t(this),
                            l = a.outerWidth(),
                            c = a.outerHeight(),
                            m = g(this, "marginLeft"),
                            v = g(this, "marginTop"),
                            k = l + m + g(this, "marginRight") + y.width,
                            C = c + v + g(this, "marginBottom") + y.height,
                            T = t.extend({}, p),
                            D = f(x.my, a.outerWidth(), a.outerHeight());
                        "right" === e.my[0] ? (T.left -= l) : "center" === e.my[0] && (T.left -= l / 2),
                            "bottom" === e.my[1] ? (T.top -= c) : "center" === e.my[1] && (T.top -= c / 2),
                            (T.left += D[0]),
                            (T.top += D[1]),
                            (o = { marginLeft: m, marginTop: v }),
                            t.each(["left", "top"], function (n, s) {
                                t.ui.position[w[n]] &&
                                    t.ui.position[w[n]][s](T, {
                                        targetWidth: h,
                                        targetHeight: u,
                                        elemWidth: l,
                                        elemHeight: c,
                                        collisionPosition: o,
                                        collisionWidth: k,
                                        collisionHeight: C,
                                        offset: [i[0] + D[0], i[1] + D[1]],
                                        my: e.my,
                                        at: e.at,
                                        within: b,
                                        elem: a,
                                    });
                            }),
                            e.using &&
                                (r = function (t) {
                                    var i = d.left - T.left,
                                        o = i + h - l,
                                        r = d.top - T.top,
                                        p = r + u - c,
                                        f = {
                                            target: { element: _, left: d.left, top: d.top, width: h, height: u },
                                            element: { element: a, left: T.left, top: T.top, width: l, height: c },
                                            horizontal: o < 0 ? "left" : 0 < i ? "right" : "center",
                                            vertical: p < 0 ? "top" : 0 < r ? "bottom" : "middle",
                                        };
                                    h < l && s(i + o) < h && (f.horizontal = "center"),
                                        u < c && s(r + p) < u && (f.vertical = "middle"),
                                        n(s(i), s(o)) > n(s(r), s(p)) ? (f.important = "horizontal") : (f.important = "vertical"),
                                        e.using.call(this, t, f);
                                }),
                            a.offset(t.extend(T, { using: r }));
                    })
                );
            }),
            (t.ui.position = {
                fit: {
                    left: function (t, e) {
                        var i = e.within,
                            s = i.isWindow ? i.scrollLeft : i.offset.left,
                            o = i.width,
                            r = t.left - e.collisionPosition.marginLeft,
                            a = s - r,
                            l = r + e.collisionWidth - o - s;
                        e.collisionWidth > o
                            ? 0 < a && l <= 0
                                ? ((i = t.left + a + e.collisionWidth - o - s), (t.left += a - i))
                                : (t.left = !(0 < l && a <= 0) && l < a ? s + o - e.collisionWidth : s)
                            : 0 < a
                            ? (t.left += a)
                            : 0 < l
                            ? (t.left -= l)
                            : (t.left = n(t.left - r, t.left));
                    },
                    top: function (t, e) {
                        var i = e.within,
                            s = i.isWindow ? i.scrollTop : i.offset.top,
                            o = e.within.height,
                            r = t.top - e.collisionPosition.marginTop,
                            a = s - r,
                            l = r + e.collisionHeight - o - s;
                        e.collisionHeight > o
                            ? 0 < a && l <= 0
                                ? ((i = t.top + a + e.collisionHeight - o - s), (t.top += a - i))
                                : (t.top = !(0 < l && a <= 0) && l < a ? s + o - e.collisionHeight : s)
                            : 0 < a
                            ? (t.top += a)
                            : 0 < l
                            ? (t.top -= l)
                            : (t.top = n(t.top - r, t.top));
                    },
                },
                flip: {
                    left: function (t, e) {
                        var i = (h = e.within).offset.left + h.scrollLeft,
                            n = h.width,
                            o = h.isWindow ? h.scrollLeft : h.offset.left,
                            r = (c = t.left - e.collisionPosition.marginLeft) - o,
                            a = c + e.collisionWidth - n - o,
                            l = "left" === e.my[0] ? -e.elemWidth : "right" === e.my[0] ? e.elemWidth : 0,
                            h = "left" === e.at[0] ? e.targetWidth : "right" === e.at[0] ? -e.targetWidth : 0,
                            c = -2 * e.offset[0];
                        r < 0
                            ? ((i = t.left + l + h + c + e.collisionWidth - n - i) < 0 || i < s(r)) && (t.left += l + h + c)
                            : 0 < a && (0 < (o = t.left - e.collisionPosition.marginLeft + l + h + c - o) || s(o) < a) && (t.left += l + h + c);
                    },
                    top: function (t, e) {
                        var i = (h = e.within).offset.top + h.scrollTop,
                            n = h.height,
                            o = h.isWindow ? h.scrollTop : h.offset.top,
                            r = (c = t.top - e.collisionPosition.marginTop) - o,
                            a = c + e.collisionHeight - n - o,
                            l = "top" === e.my[1] ? -e.elemHeight : "bottom" === e.my[1] ? e.elemHeight : 0,
                            h = "top" === e.at[1] ? e.targetHeight : "bottom" === e.at[1] ? -e.targetHeight : 0,
                            c = -2 * e.offset[1];
                        r < 0 ? ((i = t.top + l + h + c + e.collisionHeight - n - i) < 0 || i < s(r)) && (t.top += l + h + c) : 0 < a && (0 < (o = t.top - e.collisionPosition.marginTop + l + h + c - o) || s(o) < a) && (t.top += l + h + c);
                    },
                },
                flipfit: {
                    left: function () {
                        t.ui.position.flip.left.apply(this, arguments), t.ui.position.fit.left.apply(this, arguments);
                    },
                    top: function () {
                        t.ui.position.flip.top.apply(this, arguments), t.ui.position.fit.top.apply(this, arguments);
                    },
                },
            }),
            t.ui.position,
            t.extend(t.expr.pseudos, {
                data: t.expr.createPseudo
                    ? t.expr.createPseudo(function (e) {
                          return function (i) {
                              return !!t.data(i, e);
                          };
                      })
                    : function (e, i, n) {
                          return !!t.data(e, n[3]);
                      },
            }),
            t.fn.extend({
                disableSelection:
                    ((v = "onselectstart" in document.createElement("div") ? "selectstart" : "mousedown"),
                    function () {
                        return this.on(v + ".ui-disableSelection", function (t) {
                            t.preventDefault();
                        });
                    }),
                enableSelection: function () {
                    return this.off(".ui-disableSelection");
                },
            });
        var v,
            _ = t,
            b = {},
            y = b.toString,
            w = /^([\-+])=\s*(\d+\.?\d*)/,
            x = [
                {
                    re: /rgba?\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,
                    parse: function (t) {
                        return [t[1], t[2], t[3], t[4]];
                    },
                },
                {
                    re: /rgba?\(\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,
                    parse: function (t) {
                        return [2.55 * t[1], 2.55 * t[2], 2.55 * t[3], t[4]];
                    },
                },
                {
                    re: /#([a-f0-9]{2})([a-f0-9]{2})([a-f0-9]{2})([a-f0-9]{2})?/,
                    parse: function (t) {
                        return [parseInt(t[1], 16), parseInt(t[2], 16), parseInt(t[3], 16), t[4] ? (parseInt(t[4], 16) / 255).toFixed(2) : 1];
                    },
                },
                {
                    re: /#([a-f0-9])([a-f0-9])([a-f0-9])([a-f0-9])?/,
                    parse: function (t) {
                        return [parseInt(t[1] + t[1], 16), parseInt(t[2] + t[2], 16), parseInt(t[3] + t[3], 16), t[4] ? (parseInt(t[4] + t[4], 16) / 255).toFixed(2) : 1];
                    },
                },
                {
                    re: /hsla?\(\s*(\d+(?:\.\d+)?)\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,
                    space: "hsla",
                    parse: function (t) {
                        return [t[1], t[2] / 100, t[3] / 100, t[4]];
                    },
                },
            ],
            k = (_.Color = function (t, e, i, n) {
                return new _.Color.fn.parse(t, e, i, n);
            }),
            C = {
                rgba: { props: { red: { idx: 0, type: "byte" }, green: { idx: 1, type: "byte" }, blue: { idx: 2, type: "byte" } } },
                hsla: { props: { hue: { idx: 0, type: "degrees" }, saturation: { idx: 1, type: "percent" }, lightness: { idx: 2, type: "percent" } } },
            },
            T = { byte: { floor: !0, max: 255 }, percent: { max: 1 }, degrees: { mod: 360, floor: !0 } },
            D = (k.support = {}),
            S = _("<p>")[0],
            A = _.each;
        function E(t) {
            return null == t ? t + "" : "object" == typeof t ? b[y.call(t)] || "object" : typeof t;
        }
        function I(t, e, i) {
            var n = T[e.type] || {};
            return null == t ? (i || !e.def ? null : e.def) : ((t = n.floor ? ~~t : parseFloat(t)), isNaN(t) ? e.def : n.mod ? (t + n.mod) % n.mod : Math.min(n.max, Math.max(0, t)));
        }
        function P(t) {
            var e = k(),
                i = (e._rgba = []);
            return (
                (t = t.toLowerCase()),
                A(x, function (n, s) {
                    var o = (o = s.re.exec(t)) && s.parse(o);
                    s = s.space || "rgba";
                    if (o) return (o = e[s](o)), (e[C[s].cache] = o[C[s].cache]), (i = e._rgba = o._rgba), !1;
                }),
                i.length ? ("0,0,0,0" === i.join() && _.extend(i, X.transparent), e) : X[t]
            );
        }
        function M(t, e, i) {
            return 6 * (i = (i + 1) % 1) < 1 ? t + (e - t) * i * 6 : 2 * i < 1 ? e : 3 * i < 2 ? t + (e - t) * (2 / 3 - i) * 6 : t;
        }
        (S.style.cssText = "background-color:rgba(1,1,1,.5)"),
            (D.rgba = -1 < S.style.backgroundColor.indexOf("rgba")),
            A(C, function (t, e) {
                (e.cache = "_" + t), (e.props.alpha = { idx: 3, type: "percent", def: 1 });
            }),
            _.each("Boolean Number String Function Array Date RegExp Object Error Symbol".split(" "), function (t, e) {
                b["[object " + e + "]"] = e.toLowerCase();
            }),
            ((k.fn = _.extend(k.prototype, {
                parse: function (t, e, i, n) {
                    if (void 0 === t) return (this._rgba = [null, null, null, null]), this;
                    (t.jquery || t.nodeType) && ((t = _(t).css(e)), (e = void 0));
                    var s = this,
                        o = E(t),
                        r = (this._rgba = []);
                    return (
                        void 0 !== e && ((t = [t, e, i, n]), (o = "array")),
                        "string" === o
                            ? this.parse(P(t) || X._default)
                            : "array" === o
                            ? (A(C.rgba.props, function (e, i) {
                                  r[i.idx] = I(t[i.idx], i);
                              }),
                              this)
                            : "object" === o
                            ? (A(
                                  C,
                                  t instanceof k
                                      ? function (e, i) {
                                            t[i.cache] && (s[i.cache] = t[i.cache].slice());
                                        }
                                      : function (e, i) {
                                            var n = i.cache;
                                            A(i.props, function (e, o) {
                                                if (!s[n] && i.to) {
                                                    if ("alpha" === e || null == t[e]) return;
                                                    s[n] = i.to(s._rgba);
                                                }
                                                s[n][o.idx] = I(t[e], o, !0);
                                            }),
                                                s[n] && _.inArray(null, s[n].slice(0, 3)) < 0 && (null == s[n][3] && (s[n][3] = 1), i.from && (s._rgba = i.from(s[n])));
                                        }
                              ),
                              this)
                            : void 0
                    );
                },
                is: function (t) {
                    var e = k(t),
                        i = !0,
                        n = this;
                    return (
                        A(C, function (t, s) {
                            var o,
                                r = e[s.cache];
                            return (
                                r &&
                                    ((o = n[s.cache] || (s.to && s.to(n._rgba)) || []),
                                    A(s.props, function (t, e) {
                                        if (null != r[e.idx]) return (i = r[e.idx] === o[e.idx]);
                                    })),
                                i
                            );
                        }),
                        i
                    );
                },
                _space: function () {
                    var t = [],
                        e = this;
                    return (
                        A(C, function (i, n) {
                            e[n.cache] && t.push(i);
                        }),
                        t.pop()
                    );
                },
                transition: function (t, e) {
                    var i = (r = k(t))._space(),
                        n = C[i],
                        s = (t = 0 === this.alpha() ? k("transparent") : this)[n.cache] || n.to(t._rgba),
                        o = s.slice(),
                        r = r[n.cache];
                    return (
                        A(n.props, function (t, i) {
                            var n = i.idx,
                                a = s[n],
                                l = r[n],
                                h = T[i.type] || {};
                            null !== l && (null === a ? (o[n] = l) : (h.mod && (l - a > h.mod / 2 ? (a += h.mod) : a - l > h.mod / 2 && (a -= h.mod)), (o[n] = I((l - a) * e + a, i))));
                        }),
                        this[i](o)
                    );
                },
                blend: function (t) {
                    if (1 === this._rgba[3]) return this;
                    var e = this._rgba.slice(),
                        i = e.pop(),
                        n = k(t)._rgba;
                    return k(
                        _.map(e, function (t, e) {
                            return (1 - i) * n[e] + i * t;
                        })
                    );
                },
                toRgbaString: function () {
                    var t = "rgba(",
                        e = _.map(this._rgba, function (t, e) {
                            return null != t ? t : 2 < e ? 1 : 0;
                        });
                    return 1 === e[3] && (e.pop(), (t = "rgb(")), t + e.join() + ")";
                },
                toHslaString: function () {
                    var t = "hsla(",
                        e = _.map(this.hsla(), function (t, e) {
                            return null == t && (t = 2 < e ? 1 : 0), e && e < 3 ? Math.round(100 * t) + "%" : t;
                        });
                    return 1 === e[3] && (e.pop(), (t = "hsl(")), t + e.join() + ")";
                },
                toHexString: function (t) {
                    var e = this._rgba.slice(),
                        i = e.pop();
                    return (
                        t && e.push(~~(255 * i)),
                        "#" +
                            _.map(e, function (t) {
                                return 1 === (t = (t || 0).toString(16)).length ? "0" + t : t;
                            }).join("")
                    );
                },
                toString: function () {
                    return 0 === this._rgba[3] ? "transparent" : this.toRgbaString();
                },
            })).parse.prototype = k.fn),
            (C.hsla.to = function (t) {
                if (null == t[0] || null == t[1] || null == t[2]) return [null, null, null, t[3]];
                var e = t[0] / 255,
                    i = t[1] / 255,
                    n = t[2] / 255,
                    s = t[3],
                    o = Math.max(e, i, n),
                    r = Math.min(e, i, n),
                    a = o - r,
                    l = ((t = 0.5 * (l = o + r)), (i = r === o ? 0 : e === o ? (60 * (i - n)) / a + 360 : i === o ? (60 * (n - e)) / a + 120 : (60 * (e - i)) / a + 240), 0 == a ? 0 : t <= 0.5 ? a / l : a / (2 - l));
                return [Math.round(i) % 360, l, t, null == s ? 1 : s];
            }),
            (C.hsla.from = function (t) {
                if (null == t[0] || null == t[1] || null == t[2]) return [null, null, null, t[3]];
                var e = t[0] / 360,
                    i = t[1],
                    n = t[2];
                (t = t[3]), (n = 2 * n - (i = n <= 0.5 ? n * (1 + i) : n + i - n * i));
                return [Math.round(255 * M(n, i, e + 1 / 3)), Math.round(255 * M(n, i, e)), Math.round(255 * M(n, i, e - 1 / 3)), t];
            }),
            A(C, function (t, e) {
                var i = e.props,
                    n = e.cache,
                    s = e.to,
                    o = e.from;
                (k.fn[t] = function (t) {
                    if ((s && !this[n] && (this[n] = s(this._rgba)), void 0 === t)) return this[n].slice();
                    var e = E(t),
                        r = "array" === e || "object" === e ? t : arguments,
                        a = this[n].slice();
                    return (
                        A(i, function (t, i) {
                            null == (t = r["object" === e ? t : i.idx]) && (t = a[i.idx]), (a[i.idx] = I(t, i));
                        }),
                        o ? (((t = k(o(a)))[n] = a), t) : k(a)
                    );
                }),
                    A(i, function (e, i) {
                        k.fn[e] ||
                            (k.fn[e] = function (n) {
                                var s,
                                    o = E(n),
                                    r = "alpha" === e ? (this._hsla ? "hsla" : "rgba") : t,
                                    a = this[r](),
                                    l = a[i.idx];
                                return "undefined" === o
                                    ? l
                                    : ("function" === o && (o = E((n = n.call(this, l)))),
                                      null == n && i.empty ? this : ("string" === o && (s = w.exec(n)) && (n = l + parseFloat(s[2]) * ("+" === s[1] ? 1 : -1)), (a[i.idx] = n), this[r](a)));
                            });
                    });
            }),
            (k.hook = function (t) {
                (t = t.split(" ")),
                    A(t, function (t, e) {
                        (_.cssHooks[e] = {
                            set: function (t, i) {
                                var n,
                                    s,
                                    o = "";
                                if ("transparent" !== i && ("string" !== E(i) || (n = P(i)))) {
                                    if (((i = k(n || i)), !D.rgba && 1 !== i._rgba[3])) {
                                        for (s = "backgroundColor" === e ? t.parentNode : t; ("" === o || "transparent" === o) && s && s.style; )
                                            try {
                                                (o = _.css(s, "backgroundColor")), (s = s.parentNode);
                                            } catch (t) {}
                                        i = i.blend(o && "transparent" !== o ? o : "_default");
                                    }
                                    i = i.toRgbaString();
                                }
                                try {
                                    t.style[e] = i;
                                } catch (t) {}
                            },
                        }),
                            (_.fx.step[e] = function (t) {
                                t.colorInit || ((t.start = k(t.elem, e)), (t.end = k(t.end)), (t.colorInit = !0)), _.cssHooks[e].set(t.elem, t.start.transition(t.end, t.pos));
                            });
                    });
            })("backgroundColor borderBottomColor borderLeftColor borderRightColor borderTopColor color columnRuleColor outlineColor textDecorationColor textEmphasisColor"),
            (_.cssHooks.borderColor = {
                expand: function (t) {
                    var e = {};
                    return (
                        A(["Top", "Right", "Bottom", "Left"], function (i, n) {
                            e["border" + n + "Color"] = t;
                        }),
                        e
                    );
                },
            });
        var O,
            H,
            L,
            N,
            W,
            R,
            z,
            j,
            F,
            q,
            Y,
            B,
            $,
            X = (_.Color.names = {
                aqua: "#00ffff",
                black: "#000000",
                blue: "#0000ff",
                fuchsia: "#ff00ff",
                gray: "#808080",
                green: "#008000",
                lime: "#00ff00",
                maroon: "#800000",
                navy: "#000080",
                olive: "#808000",
                purple: "#800080",
                red: "#ff0000",
                silver: "#c0c0c0",
                teal: "#008080",
                white: "#ffffff",
                yellow: "#ffff00",
                transparent: [null, null, null, 0],
                _default: "#ffffff",
            }),
            U = "ui-effects-",
            K = "ui-effects-style",
            V = "ui-effects-animated";
        function G(t) {
            var e,
                i,
                n = t.ownerDocument.defaultView ? t.ownerDocument.defaultView.getComputedStyle(t, null) : t.currentStyle,
                s = {};
            if (n && n.length && n[0] && n[n[0]])
                for (i = n.length; i--; )
                    "string" == typeof n[(e = n[i])] &&
                        (s[
                            e.replace(/-([\da-z])/gi, function (t, e) {
                                return e.toUpperCase();
                            })
                        ] = n[e]);
            else for (e in n) "string" == typeof n[e] && (s[e] = n[e]);
            return s;
        }
        function Q(e, i, n, s) {
            return (
                (e = { effect: (e = t.isPlainObject(e) ? (i = e).effect : e) }),
                "function" == typeof (i = null == i ? {} : i) && ((s = i), (n = null), (i = {})),
                ("number" != typeof i && !t.fx.speeds[i]) || ((s = n), (n = i), (i = {})),
                "function" == typeof n && ((s = n), (n = null)),
                i && t.extend(e, i),
                (n = n || i.duration),
                (e.duration = t.fx.off ? 0 : "number" == typeof n ? n : n in t.fx.speeds ? t.fx.speeds[n] : t.fx.speeds._default),
                (e.complete = s || i.complete),
                e
            );
        }
        function J(e) {
            return !e || "number" == typeof e || t.fx.speeds[e] || ("string" == typeof e && !t.effects.effect[e]) || "function" == typeof e || ("object" == typeof e && !e.effect);
        }
        function Z(t, e) {
            var i = e.outerWidth();
            (e = e.outerHeight()), (t = /^rect\((-?\d*\.?\d*px|-?\d+%|auto),?\s*(-?\d*\.?\d*px|-?\d+%|auto),?\s*(-?\d*\.?\d*px|-?\d+%|auto),?\s*(-?\d*\.?\d*px|-?\d+%|auto)\)$/.exec(t) || ["", 0, i, e, 0]);
            return { top: parseFloat(t[1]) || 0, right: "auto" === t[2] ? i : parseFloat(t[2]), bottom: "auto" === t[3] ? e : parseFloat(t[3]), left: parseFloat(t[4]) || 0 };
        }
        (t.effects = { effect: {} }),
            (N = ["add", "remove", "toggle"]),
            (W = { border: 1, borderBottom: 1, borderColor: 1, borderLeft: 1, borderRight: 1, borderTop: 1, borderWidth: 1, margin: 1, padding: 1 }),
            t.each(["borderLeftStyle", "borderRightStyle", "borderBottomStyle", "borderTopStyle"], function (e, i) {
                t.fx.step[i] = function (t) {
                    (("none" !== t.end && !t.setAttr) || (1 === t.pos && !t.setAttr)) && (_.style(t.elem, i, t.end), (t.setAttr = !0));
                };
            }),
            t.fn.addBack ||
                (t.fn.addBack = function (t) {
                    return this.add(null == t ? this.prevObject : this.prevObject.filter(t));
                }),
            (t.effects.animateClass = function (e, i, n, s) {
                var o = t.speed(i, n, s);
                return this.queue(function () {
                    var i = t(this),
                        n = i.attr("class") || "",
                        s = (s = o.children ? i.find("*").addBack() : i).map(function () {
                            return { el: t(this), start: G(this) };
                        }),
                        r = function () {
                            t.each(N, function (t, n) {
                                e[n] && i[n + "Class"](e[n]);
                            });
                        };
                    r(),
                        (s = s.map(function () {
                            return (
                                (this.end = G(this.el[0])),
                                (this.diff = (function (e, i) {
                                    var n,
                                        s,
                                        o = {};
                                    for (n in i) (s = i[n]), e[n] !== s && (W[n] || (!t.fx.step[n] && isNaN(parseFloat(s))) || (o[n] = s));
                                    return o;
                                })(this.start, this.end)),
                                this
                            );
                        })),
                        i.attr("class", n),
                        (s = s.map(function () {
                            var e = this,
                                i = t.Deferred(),
                                n = t.extend({}, o, {
                                    queue: !1,
                                    complete: function () {
                                        i.resolve(e);
                                    },
                                });
                            return this.el.animate(this.diff, n), i.promise();
                        })),
                        t.when.apply(t, s.get()).done(function () {
                            r(),
                                t.each(arguments, function () {
                                    var e = this.el;
                                    t.each(this.diff, function (t) {
                                        e.css(t, "");
                                    });
                                }),
                                o.complete.call(i[0]);
                        });
                });
            }),
            t.fn.extend({
                addClass:
                    ((L = t.fn.addClass),
                    function (e, i, n, s) {
                        return i ? t.effects.animateClass.call(this, { add: e }, i, n, s) : L.apply(this, arguments);
                    }),
                removeClass:
                    ((H = t.fn.removeClass),
                    function (e, i, n, s) {
                        return 1 < arguments.length ? t.effects.animateClass.call(this, { remove: e }, i, n, s) : H.apply(this, arguments);
                    }),
                toggleClass:
                    ((O = t.fn.toggleClass),
                    function (e, i, n, s, o) {
                        return "boolean" == typeof i || void 0 === i ? (n ? t.effects.animateClass.call(this, i ? { add: e } : { remove: e }, n, s, o) : O.apply(this, arguments)) : t.effects.animateClass.call(this, { toggle: e }, i, n, s);
                    }),
                switchClass: function (e, i, n, s, o) {
                    return t.effects.animateClass.call(this, { add: i, remove: e }, n, s, o);
                },
            }),
            t.expr &&
                t.expr.pseudos &&
                t.expr.pseudos.animated &&
                (t.expr.pseudos.animated =
                    ((R = t.expr.pseudos.animated),
                    function (e) {
                        return !!t(e).data(V) || R(e);
                    })),
            !1 !== t.uiBackCompat &&
                t.extend(t.effects, {
                    save: function (t, e) {
                        for (var i = 0, n = e.length; i < n; i++) null !== e[i] && t.data(U + e[i], t[0].style[e[i]]);
                    },
                    restore: function (t, e) {
                        for (var i, n = 0, s = e.length; n < s; n++) null !== e[n] && ((i = t.data(U + e[n])), t.css(e[n], i));
                    },
                    setMode: function (t, e) {
                        return "toggle" === e ? (t.is(":hidden") ? "show" : "hide") : e;
                    },
                    createWrapper: function (e) {
                        if (e.parent().is(".ui-effects-wrapper")) return e.parent();
                        var i = { width: e.outerWidth(!0), height: e.outerHeight(!0), float: e.css("float") },
                            n = t("<div></div>").addClass("ui-effects-wrapper").css({ fontSize: "100%", background: "transparent", border: "none", margin: 0, padding: 0 }),
                            s = { width: e.width(), height: e.height() },
                            o = document.activeElement;
                        try {
                            o.id;
                        } catch (n) {
                            o = document.body;
                        }
                        return (
                            e.wrap(n),
                            (e[0] !== o && !t.contains(e[0], o)) || t(o).trigger("focus"),
                            (n = e.parent()),
                            "static" === e.css("position")
                                ? (n.css({ position: "relative" }), e.css({ position: "relative" }))
                                : (t.extend(i, { position: e.css("position"), zIndex: e.css("z-index") }),
                                  t.each(["top", "left", "bottom", "right"], function (t, n) {
                                      (i[n] = e.css(n)), isNaN(parseInt(i[n], 10)) && (i[n] = "auto");
                                  }),
                                  e.css({ position: "relative", top: 0, left: 0, right: "auto", bottom: "auto" })),
                            e.css(s),
                            n.css(i).show()
                        );
                    },
                    removeWrapper: function (e) {
                        var i = document.activeElement;
                        return e.parent().is(".ui-effects-wrapper") && (e.parent().replaceWith(e), (e[0] !== i && !t.contains(e[0], i)) || t(i).trigger("focus")), e;
                    },
                }),
            t.extend(t.effects, {
                version: "1.13.2",
                define: function (e, i, n) {
                    return n || ((n = i), (i = "effect")), (t.effects.effect[e] = n), (t.effects.effect[e].mode = i), n;
                },
                scaledDimensions: function (t, e, i) {
                    if (0 === e) return { height: 0, width: 0, outerHeight: 0, outerWidth: 0 };
                    var n = "horizontal" !== i ? (e || 100) / 100 : 1;
                    e = "vertical" !== i ? (e || 100) / 100 : 1;
                    return { height: t.height() * e, width: t.width() * n, outerHeight: t.outerHeight() * e, outerWidth: t.outerWidth() * n };
                },
                clipToBox: function (t) {
                    return { width: t.clip.right - t.clip.left, height: t.clip.bottom - t.clip.top, left: t.clip.left, top: t.clip.top };
                },
                unshift: function (t, e, i) {
                    var n = t.queue();
                    1 < e && n.splice.apply(n, [1, 0].concat(n.splice(e, i))), t.dequeue();
                },
                saveStyle: function (t) {
                    t.data(K, t[0].style.cssText);
                },
                restoreStyle: function (t) {
                    (t[0].style.cssText = t.data(K) || ""), t.removeData(K);
                },
                mode: function (t, e) {
                    return (t = t.is(":hidden")), "toggle" === e && (e = t ? "show" : "hide"), (t ? "hide" === e : "show" === e) ? "none" : e;
                },
                getBaseline: function (t, e) {
                    var i, n;
                    switch (t[0]) {
                        case "top":
                            i = 0;
                            break;
                        case "middle":
                            i = 0.5;
                            break;
                        case "bottom":
                            i = 1;
                            break;
                        default:
                            i = t[0] / e.height;
                    }
                    switch (t[1]) {
                        case "left":
                            n = 0;
                            break;
                        case "center":
                            n = 0.5;
                            break;
                        case "right":
                            n = 1;
                            break;
                        default:
                            n = t[1] / e.width;
                    }
                    return { x: n, y: i };
                },
                createPlaceholder: function (e) {
                    var i,
                        n = e.css("position"),
                        s = e.position();
                    return (
                        e
                            .css({ marginTop: e.css("marginTop"), marginBottom: e.css("marginBottom"), marginLeft: e.css("marginLeft"), marginRight: e.css("marginRight") })
                            .outerWidth(e.outerWidth())
                            .outerHeight(e.outerHeight()),
                        /^(static|relative)/.test(n) &&
                            ((n = "absolute"),
                            (i = t("<" + e[0].nodeName + ">")
                                .insertAfter(e)
                                .css({
                                    display: /^(inline|ruby)/.test(e.css("display")) ? "inline-block" : "block",
                                    visibility: "hidden",
                                    marginTop: e.css("marginTop"),
                                    marginBottom: e.css("marginBottom"),
                                    marginLeft: e.css("marginLeft"),
                                    marginRight: e.css("marginRight"),
                                    float: e.css("float"),
                                })
                                .outerWidth(e.outerWidth())
                                .outerHeight(e.outerHeight())
                                .addClass("ui-effects-placeholder")),
                            e.data(U + "placeholder", i)),
                        e.css({ position: n, left: s.left, top: s.top }),
                        i
                    );
                },
                removePlaceholder: function (t) {
                    var e = U + "placeholder",
                        i = t.data(e);
                    i && (i.remove(), t.removeData(e));
                },
                cleanUp: function (e) {
                    t.effects.restoreStyle(e), t.effects.removePlaceholder(e);
                },
                setTransition: function (e, i, n, s) {
                    return (
                        (s = s || {}),
                        t.each(i, function (t, i) {
                            var o = e.cssUnit(i);
                            0 < o[0] && (s[i] = o[0] * n + o[1]);
                        }),
                        s
                    );
                },
            }),
            t.fn.extend({
                effect: function () {
                    function e(e) {
                        var i = t(this),
                            n = t.effects.mode(i, l) || s;
                        i.data(V, !0), h.push(n), s && ("show" === n || (n === s && "hide" === n)) && i.show(), (s && "none" === n) || t.effects.saveStyle(i), "function" == typeof e && e();
                    }
                    var i = Q.apply(this, arguments),
                        n = t.effects.effect[i.effect],
                        s = n.mode,
                        o = i.queue,
                        r = o || "fx",
                        a = i.complete,
                        l = i.mode,
                        h = [];
                    return t.fx.off || !n
                        ? l
                            ? this[l](i.duration, a)
                            : this.each(function () {
                                  a && a.call(this);
                              })
                        : !1 === o
                        ? this.each(e).each(c)
                        : this.queue(r, e).queue(r, c);
                    function c(e) {
                        var o = t(this);
                        function r() {
                            "function" == typeof a && a.call(o[0]), "function" == typeof e && e();
                        }
                        (i.mode = h.shift()),
                            !1 === t.uiBackCompat || s
                                ? "none" === i.mode
                                    ? (o[l](), r())
                                    : n.call(o[0], i, function () {
                                          o.removeData(V), t.effects.cleanUp(o), "hide" === i.mode && o.hide(), r();
                                      })
                                : (o.is(":hidden") ? "hide" === l : "show" === l)
                                ? (o[l](), r())
                                : n.call(o[0], i, r);
                    }
                },
                show:
                    ((F = t.fn.show),
                    function (t) {
                        return J(t) ? F.apply(this, arguments) : (((t = Q.apply(this, arguments)).mode = "show"), this.effect.call(this, t));
                    }),
                hide:
                    ((j = t.fn.hide),
                    function (t) {
                        return J(t) ? j.apply(this, arguments) : (((t = Q.apply(this, arguments)).mode = "hide"), this.effect.call(this, t));
                    }),
                toggle:
                    ((z = t.fn.toggle),
                    function (t) {
                        return J(t) || "boolean" == typeof t ? z.apply(this, arguments) : (((t = Q.apply(this, arguments)).mode = "toggle"), this.effect.call(this, t));
                    }),
                cssUnit: function (e) {
                    var i = this.css(e),
                        n = [];
                    return (
                        t.each(["em", "px", "%", "pt"], function (t, e) {
                            0 < i.indexOf(e) && (n = [parseFloat(i), e]);
                        }),
                        n
                    );
                },
                cssClip: function (t) {
                    return t ? this.css("clip", "rect(" + t.top + "px " + t.right + "px " + t.bottom + "px " + t.left + "px)") : Z(this.css("clip"), this);
                },
                transfer: function (e, i) {
                    var n = t(this),
                        s = "fixed" === (l = t(e.to)).css("position"),
                        o = t("body"),
                        r = s ? o.scrollTop() : 0,
                        a = s ? o.scrollLeft() : 0,
                        l = ((o = { top: (o = l.offset()).top - r, left: o.left - a, height: l.innerHeight(), width: l.innerWidth() }), n.offset()),
                        h = t("<div class='ui-effects-transfer'></div>");
                    h.appendTo("body")
                        .addClass(e.className)
                        .css({ top: l.top - r, left: l.left - a, height: n.innerHeight(), width: n.innerWidth(), position: s ? "fixed" : "absolute" })
                        .animate(o, e.duration, e.easing, function () {
                            h.remove(), "function" == typeof i && i();
                        });
                },
            }),
            (t.fx.step.clip = function (e) {
                e.clipInit || ((e.start = t(e.elem).cssClip()), "string" == typeof e.end && (e.end = Z(e.end, e.elem)), (e.clipInit = !0)),
                    t(e.elem).cssClip({
                        top: e.pos * (e.end.top - e.start.top) + e.start.top,
                        right: e.pos * (e.end.right - e.start.right) + e.start.right,
                        bottom: e.pos * (e.end.bottom - e.start.bottom) + e.start.bottom,
                        left: e.pos * (e.end.left - e.start.left) + e.start.left,
                    });
            }),
            (q = {}),
            t.each(["Quad", "Cubic", "Quart", "Quint", "Expo"], function (t, e) {
                q[e] = function (e) {
                    return Math.pow(e, t + 2);
                };
            }),
            t.extend(q, {
                Sine: function (t) {
                    return 1 - Math.cos((t * Math.PI) / 2);
                },
                Circ: function (t) {
                    return 1 - Math.sqrt(1 - t * t);
                },
                Elastic: function (t) {
                    return 0 === t || 1 === t ? t : -Math.pow(2, 8 * (t - 1)) * Math.sin(((80 * (t - 1) - 7.5) * Math.PI) / 15);
                },
                Back: function (t) {
                    return t * t * (3 * t - 2);
                },
                Bounce: function (t) {
                    for (var e, i = 4; t < ((e = Math.pow(2, --i)) - 1) / 11; );
                    return 1 / Math.pow(4, 3 - i) - 7.5625 * Math.pow((3 * e - 2) / 22 - t, 2);
                },
            }),
            t.each(q, function (e, i) {
                (t.easing["easeIn" + e] = i),
                    (t.easing["easeOut" + e] = function (t) {
                        return 1 - i(1 - t);
                    }),
                    (t.easing["easeInOut" + e] = function (t) {
                        return t < 0.5 ? i(2 * t) / 2 : 1 - i(-2 * t + 2) / 2;
                    });
            }),
            (S = t.effects),
            t.effects.define("blind", "hide", function (e, i) {
                var n = { up: ["bottom", "top"], vertical: ["bottom", "top"], down: ["top", "bottom"], left: ["right", "left"], horizontal: ["right", "left"], right: ["left", "right"] },
                    s = t(this),
                    o = e.direction || "up",
                    r = s.cssClip(),
                    a = { clip: t.extend({}, r) },
                    l = t.effects.createPlaceholder(s);
                (a.clip[n[o][0]] = a.clip[n[o][1]]),
                    "show" === e.mode && (s.cssClip(a.clip), l && l.css(t.effects.clipToBox(a)), (a.clip = r)),
                    l && l.animate(t.effects.clipToBox(a), e.duration, e.easing),
                    s.animate(a, { queue: !1, duration: e.duration, easing: e.easing, complete: i });
            }),
            t.effects.define("bounce", function (e, i) {
                var n,
                    s,
                    o = t(this),
                    r = "hide" === (u = e.mode),
                    a = "show" === u,
                    l = e.direction || "up",
                    h = e.distance,
                    c = e.times || 5,
                    u = 2 * c + (a || r ? 1 : 0),
                    d = e.duration / u,
                    p = e.easing,
                    f = "up" === l || "down" === l ? "top" : "left",
                    g = "up" === l || "left" === l,
                    m = 0;
                e = o.queue().length;
                for (
                    t.effects.createPlaceholder(o),
                        l = o.css(f),
                        h = h || o["top" == f ? "outerHeight" : "outerWidth"]() / 3,
                        a &&
                            (((s = { opacity: 1 })[f] = l),
                            o
                                .css("opacity", 0)
                                .css(f, g ? 2 * -h : 2 * h)
                                .animate(s, d, p)),
                        r && (h /= Math.pow(2, c - 1)),
                        (s = {})[f] = l;
                    m < c;
                    m++
                )
                    ((n = {})[f] = (g ? "-=" : "+=") + h), o.animate(n, d, p).animate(s, d, p), (h = r ? 2 * h : h / 2);
                r && (((n = { opacity: 0 })[f] = (g ? "-=" : "+=") + h), o.animate(n, d, p)), o.queue(i), t.effects.unshift(o, e, 1 + u);
            }),
            t.effects.define("clip", "hide", function (e, i) {
                var n = {},
                    s = t(this),
                    o = (r = "both" === (a = e.direction || "vertical")) || "horizontal" === a,
                    r = r || "vertical" === a,
                    a = s.cssClip();
                (n.clip = { top: r ? (a.bottom - a.top) / 2 : a.top, right: o ? (a.right - a.left) / 2 : a.right, bottom: r ? (a.bottom - a.top) / 2 : a.bottom, left: o ? (a.right - a.left) / 2 : a.left }),
                    t.effects.createPlaceholder(s),
                    "show" === e.mode && (s.cssClip(n.clip), (n.clip = a)),
                    s.animate(n, { queue: !1, duration: e.duration, easing: e.easing, complete: i });
            }),
            t.effects.define("drop", "hide", function (e, i) {
                var n = t(this),
                    s = "show" === e.mode,
                    o = e.direction || "left",
                    r = "up" === o || "down" === o ? "top" : "left",
                    a = "up" === o || "left" === o ? "-=" : "+=",
                    l = "+=" == a ? "-=" : "+=",
                    h = { opacity: 0 };
                t.effects.createPlaceholder(n),
                    (o = e.distance || n["top" == r ? "outerHeight" : "outerWidth"](!0) / 2),
                    (h[r] = a + o),
                    s && (n.css(h), (h[r] = l + o), (h.opacity = 1)),
                    n.animate(h, { queue: !1, duration: e.duration, easing: e.easing, complete: i });
            }),
            t.effects.define("explode", "hide", function (e, i) {
                var n,
                    s,
                    o,
                    r,
                    a,
                    l,
                    h = e.pieces ? Math.round(Math.sqrt(e.pieces)) : 3,
                    c = h,
                    u = t(this),
                    d = "show" === e.mode,
                    p = u.show().css("visibility", "hidden").offset(),
                    f = Math.ceil(u.outerWidth() / c),
                    g = Math.ceil(u.outerHeight() / h),
                    m = [];
                function v() {
                    m.push(this), m.length === h * c && (u.css({ visibility: "visible" }), t(m).remove(), i());
                }
                for (n = 0; n < h; n++)
                    for (r = p.top + n * g, l = n - (h - 1) / 2, s = 0; s < c; s++)
                        (o = p.left + s * f),
                            (a = s - (c - 1) / 2),
                            u
                                .clone()
                                .appendTo("body")
                                .wrap("<div></div>")
                                .css({ position: "absolute", visibility: "visible", left: -s * f, top: -n * g })
                                .parent()
                                .addClass("ui-effects-explode")
                                .css({ position: "absolute", overflow: "hidden", width: f, height: g, left: o + (d ? a * f : 0), top: r + (d ? l * g : 0), opacity: d ? 0 : 1 })
                                .animate({ left: o + (d ? 0 : a * f), top: r + (d ? 0 : l * g), opacity: d ? 1 : 0 }, e.duration || 500, e.easing, v);
            }),
            t.effects.define("fade", "toggle", function (e, i) {
                var n = "show" === e.mode;
                t(this)
                    .css("opacity", n ? 0 : 1)
                    .animate({ opacity: n ? 1 : 0 }, { queue: !1, duration: e.duration, easing: e.easing, complete: i });
            }),
            t.effects.define("fold", "hide", function (e, i) {
                var n = t(this),
                    s = "show" === (g = e.mode),
                    o = "hide" === g,
                    r = e.size || 15,
                    a = /([0-9]+)%/.exec(r),
                    l = e.horizFirst ? ["right", "bottom"] : ["bottom", "right"],
                    h = e.duration / 2,
                    c = t.effects.createPlaceholder(n),
                    u = n.cssClip(),
                    d = { clip: t.extend({}, u) },
                    p = { clip: t.extend({}, u) },
                    f = [u[l[0]], u[l[1]]],
                    g = n.queue().length;
                a && (r = (parseInt(a[1], 10) / 100) * f[o ? 0 : 1]),
                    (d.clip[l[0]] = r),
                    (p.clip[l[0]] = r),
                    (p.clip[l[1]] = 0),
                    s && (n.cssClip(p.clip), c && c.css(t.effects.clipToBox(p)), (p.clip = u)),
                    n
                        .queue(function (i) {
                            c && c.animate(t.effects.clipToBox(d), h, e.easing).animate(t.effects.clipToBox(p), h, e.easing), i();
                        })
                        .animate(d, h, e.easing)
                        .animate(p, h, e.easing)
                        .queue(i),
                    t.effects.unshift(n, g, 4);
            }),
            t.effects.define("highlight", "show", function (e, i) {
                var n = t(this),
                    s = { backgroundColor: n.css("backgroundColor") };
                "hide" === e.mode && (s.opacity = 0), t.effects.saveStyle(n), n.css({ backgroundImage: "none", backgroundColor: e.color || "#ffff99" }).animate(s, { queue: !1, duration: e.duration, easing: e.easing, complete: i });
            }),
            t.effects.define("size", function (e, i) {
                var n,
                    s = t(this),
                    o = ["fontSize"],
                    r = ["borderTopWidth", "borderBottomWidth", "paddingTop", "paddingBottom"],
                    a = ["borderLeftWidth", "borderRightWidth", "paddingLeft", "paddingRight"],
                    l = e.mode,
                    h = "effect" !== l,
                    c = e.scale || "both",
                    u = e.origin || ["middle", "center"],
                    d = s.css("position"),
                    p = s.position(),
                    f = t.effects.scaledDimensions(s),
                    g = e.from || f,
                    m = e.to || t.effects.scaledDimensions(s, 0);
                t.effects.createPlaceholder(s),
                    "show" === l && ((l = g), (g = m), (m = l)),
                    (n = { from: { y: g.height / f.height, x: g.width / f.width }, to: { y: m.height / f.height, x: m.width / f.width } }),
                    ("box" !== c && "both" !== c) ||
                        (n.from.y !== n.to.y && ((g = t.effects.setTransition(s, r, n.from.y, g)), (m = t.effects.setTransition(s, r, n.to.y, m))),
                        n.from.x !== n.to.x && ((g = t.effects.setTransition(s, a, n.from.x, g)), (m = t.effects.setTransition(s, a, n.to.x, m)))),
                    ("content" !== c && "both" !== c) || (n.from.y !== n.to.y && ((g = t.effects.setTransition(s, o, n.from.y, g)), (m = t.effects.setTransition(s, o, n.to.y, m)))),
                    u &&
                        ((u = t.effects.getBaseline(u, f)),
                        (g.top = (f.outerHeight - g.outerHeight) * u.y + p.top),
                        (g.left = (f.outerWidth - g.outerWidth) * u.x + p.left),
                        (m.top = (f.outerHeight - m.outerHeight) * u.y + p.top),
                        (m.left = (f.outerWidth - m.outerWidth) * u.x + p.left)),
                    delete g.outerHeight,
                    delete g.outerWidth,
                    s.css(g),
                    ("content" !== c && "both" !== c) ||
                        ((r = r.concat(["marginTop", "marginBottom"]).concat(o)),
                        (a = a.concat(["marginLeft", "marginRight"])),
                        s.find("*[width]").each(function () {
                            var i = t(this),
                                s = { height: (o = t.effects.scaledDimensions(i)).height * n.from.y, width: o.width * n.from.x, outerHeight: o.outerHeight * n.from.y, outerWidth: o.outerWidth * n.from.x },
                                o = { height: o.height * n.to.y, width: o.width * n.to.x, outerHeight: o.height * n.to.y, outerWidth: o.width * n.to.x };
                            n.from.y !== n.to.y && ((s = t.effects.setTransition(i, r, n.from.y, s)), (o = t.effects.setTransition(i, r, n.to.y, o))),
                                n.from.x !== n.to.x && ((s = t.effects.setTransition(i, a, n.from.x, s)), (o = t.effects.setTransition(i, a, n.to.x, o))),
                                h && t.effects.saveStyle(i),
                                i.css(s),
                                i.animate(o, e.duration, e.easing, function () {
                                    h && t.effects.restoreStyle(i);
                                });
                        })),
                    s.animate(m, {
                        queue: !1,
                        duration: e.duration,
                        easing: e.easing,
                        complete: function () {
                            var e = s.offset();
                            0 === m.opacity && s.css("opacity", g.opacity), h || (s.css("position", "static" === d ? "relative" : d).offset(e), t.effects.saveStyle(s)), i();
                        },
                    });
            }),
            t.effects.define("scale", function (e, i) {
                var n = t(this),
                    s = e.mode;
                (s = parseInt(e.percent, 10) || (0 === parseInt(e.percent, 10) || "effect" !== s ? 0 : 100)),
                    (s = t.extend(!0, { from: t.effects.scaledDimensions(n), to: t.effects.scaledDimensions(n, s, e.direction || "both"), origin: e.origin || ["middle", "center"] }, e));
                e.fade && ((s.from.opacity = 1), (s.to.opacity = 0)), t.effects.effect.size.call(this, s, i);
            }),
            t.effects.define("puff", "hide", function (e, i) {
                (e = t.extend(!0, {}, e, { fade: !0, percent: parseInt(e.percent, 10) || 150 })), t.effects.effect.scale.call(this, e, i);
            }),
            t.effects.define("pulsate", "show", function (e, i) {
                var n = t(this),
                    s = "show" === (h = e.mode),
                    o = 2 * (e.times || 5) + (s || "hide" === h ? 1 : 0),
                    r = e.duration / o,
                    a = 0,
                    l = 1,
                    h = n.queue().length;
                for ((!s && n.is(":visible")) || (n.css("opacity", 0).show(), (a = 1)); l < o; l++) n.animate({ opacity: a }, r, e.easing), (a = 1 - a);
                n.animate({ opacity: a }, r, e.easing), n.queue(i), t.effects.unshift(n, h, 1 + o);
            }),
            t.effects.define("shake", function (e, i) {
                var n = 1,
                    s = t(this),
                    o = e.direction || "left",
                    r = e.distance || 20,
                    a = e.times || 3,
                    l = 2 * a + 1,
                    h = Math.round(e.duration / l),
                    c = "up" === o || "down" === o ? "top" : "left",
                    u = "up" === o || "left" === o,
                    d = {},
                    p = {},
                    f = {};
                o = s.queue().length;
                for (t.effects.createPlaceholder(s), d[c] = (u ? "-=" : "+=") + r, p[c] = (u ? "+=" : "-=") + 2 * r, f[c] = (u ? "-=" : "+=") + 2 * r, s.animate(d, h, e.easing); n < a; n++) s.animate(p, h, e.easing).animate(f, h, e.easing);
                s
                    .animate(p, h, e.easing)
                    .animate(d, h / 2, e.easing)
                    .queue(i),
                    t.effects.unshift(s, o, 1 + l);
            }),
            t.effects.define("slide", "show", function (e, i) {
                var n,
                    s,
                    o = t(this),
                    r = { up: ["bottom", "top"], down: ["top", "bottom"], left: ["right", "left"], right: ["left", "right"] },
                    a = e.mode,
                    l = e.direction || "left",
                    h = "up" === l || "down" === l ? "top" : "left",
                    c = "up" === l || "left" === l,
                    u = e.distance || o["top" == h ? "outerHeight" : "outerWidth"](!0),
                    d = {};
                t.effects.createPlaceholder(o),
                    (n = o.cssClip()),
                    (s = o.position()[h]),
                    (d[h] = (c ? -1 : 1) * u + s),
                    (d.clip = o.cssClip()),
                    (d.clip[r[l][1]] = d.clip[r[l][0]]),
                    "show" === a && (o.cssClip(d.clip), o.css(h, d[h]), (d.clip = n), (d[h] = s)),
                    o.animate(d, { queue: !1, duration: e.duration, easing: e.easing, complete: i });
            }),
            (S =
                !1 !== t.uiBackCompat
                    ? t.effects.define("transfer", function (e, i) {
                          t(this).transfer(e, i);
                      })
                    : S),
            (t.ui.focusable = function (e, i) {
                var n,
                    s,
                    o,
                    r,
                    a = e.nodeName.toLowerCase();
                return "area" === a
                    ? ((s = (n = e.parentNode).name), !(!e.href || !s || "map" !== n.nodeName.toLowerCase()) && 0 < (s = t("img[usemap='#" + s + "']")).length && s.is(":visible"))
                    : (/^(input|select|textarea|button|object)$/.test(a) ? (o = !e.disabled) && (r = t(e).closest("fieldset")[0]) && (o = !r.disabled) : (o = ("a" === a && e.href) || i),
                      o &&
                          t(e).is(":visible") &&
                          (function (t) {
                              for (var e = t.css("visibility"); "inherit" === e; ) e = (t = t.parent()).css("visibility");
                              return "visible" === e;
                          })(t(e)));
            }),
            t.extend(t.expr.pseudos, {
                focusable: function (e) {
                    return t.ui.focusable(e, null != t.attr(e, "tabindex"));
                },
            }),
            t.ui.focusable,
            (t.fn._form = function () {
                return "string" == typeof this[0].form ? this.closest("form") : t(this[0].form);
            }),
            (t.ui.formResetMixin = {
                _formResetHandler: function () {
                    var e = t(this);
                    setTimeout(function () {
                        var i = e.data("ui-form-reset-instances");
                        t.each(i, function () {
                            this.refresh();
                        });
                    });
                },
                _bindFormResetHandler: function () {
                    var t;
                    (this.form = this.element._form()),
                        this.form.length && ((t = this.form.data("ui-form-reset-instances") || []).length || this.form.on("reset.ui-form-reset", this._formResetHandler), t.push(this), this.form.data("ui-form-reset-instances", t));
                },
                _unbindFormResetHandler: function () {
                    var e;
                    this.form.length &&
                        ((e = this.form.data("ui-form-reset-instances")).splice(t.inArray(this, e), 1), e.length ? this.form.data("ui-form-reset-instances", e) : this.form.removeData("ui-form-reset-instances").off("reset.ui-form-reset"));
                },
            }),
            t.expr.pseudos || (t.expr.pseudos = t.expr[":"]),
            t.uniqueSort || (t.uniqueSort = t.unique),
            t.escapeSelector ||
                ((Y = /([\0-\x1f\x7f]|^-?\d)|^-$|[^\x80-\uFFFF\w-]/g),
                (B = function (t, e) {
                    return e ? ("\0" === t ? "�" : t.slice(0, -1) + "\\" + t.charCodeAt(t.length - 1).toString(16) + " ") : "\\" + t;
                }),
                (t.escapeSelector = function (t) {
                    return (t + "").replace(Y, B);
                })),
            (t.fn.even && t.fn.odd) ||
                t.fn.extend({
                    even: function () {
                        return this.filter(function (t) {
                            return t % 2 == 0;
                        });
                    },
                    odd: function () {
                        return this.filter(function (t) {
                            return t % 2 == 1;
                        });
                    },
                }),
            (t.ui.keyCode = { BACKSPACE: 8, COMMA: 188, DELETE: 46, DOWN: 40, END: 35, ENTER: 13, ESCAPE: 27, HOME: 36, LEFT: 37, PAGE_DOWN: 34, PAGE_UP: 33, PERIOD: 190, RIGHT: 39, SPACE: 32, TAB: 9, UP: 38 }),
            (t.fn.labels = function () {
                var e, i, n;
                return this.length
                    ? this[0].labels && this[0].labels.length
                        ? this.pushStack(this[0].labels)
                        : ((i = this.eq(0).parents("label")),
                          (e = this.attr("id")) && ((n = (n = this.eq(0).parents().last()).add((n.length ? n : this).siblings())), (e = "label[for='" + t.escapeSelector(e) + "']"), (i = i.add(n.find(e).addBack(e)))),
                          this.pushStack(i))
                    : this.pushStack([]);
            }),
            (t.fn.scrollParent = function (e) {
                var i = this.css("position"),
                    n = "absolute" === i,
                    s = e ? /(auto|scroll|hidden)/ : /(auto|scroll)/;
                e = this.parents()
                    .filter(function () {
                        var e = t(this);
                        return (!n || "static" !== e.css("position")) && s.test(e.css("overflow") + e.css("overflow-y") + e.css("overflow-x"));
                    })
                    .eq(0);
                return "fixed" !== i && e.length ? e : t(this[0].ownerDocument || document);
            }),
            t.extend(t.expr.pseudos, {
                tabbable: function (e) {
                    var i = t.attr(e, "tabindex"),
                        n = null != i;
                    return (!n || 0 <= i) && t.ui.focusable(e, n);
                },
            }),
            t.fn.extend({
                uniqueId:
                    (($ = 0),
                    function () {
                        return this.each(function () {
                            this.id || (this.id = "ui-id-" + ++$);
                        });
                    }),
                removeUniqueId: function () {
                    return this.each(function () {
                        /^ui-id-\d+$/.test(this.id) && t(this).removeAttr("id");
                    });
                },
            }),
            t.widget("ui.accordion", {
                version: "1.13.2",
                options: {
                    active: 0,
                    animate: {},
                    classes: { "ui-accordion-header": "ui-corner-top", "ui-accordion-header-collapsed": "ui-corner-all", "ui-accordion-content": "ui-corner-bottom" },
                    collapsible: !1,
                    event: "click",
                    header: function (t) {
                        return t.find("> li > :first-child").add(t.find("> :not(li)").even());
                    },
                    heightStyle: "auto",
                    icons: { activeHeader: "ui-icon-triangle-1-s", header: "ui-icon-triangle-1-e" },
                    activate: null,
                    beforeActivate: null,
                },
                hideProps: { borderTopWidth: "hide", borderBottomWidth: "hide", paddingTop: "hide", paddingBottom: "hide", height: "hide" },
                showProps: { borderTopWidth: "show", borderBottomWidth: "show", paddingTop: "show", paddingBottom: "show", height: "show" },
                _create: function () {
                    var e = this.options;
                    (this.prevShow = this.prevHide = t()),
                        this._addClass("ui-accordion", "ui-widget ui-helper-reset"),
                        this.element.attr("role", "tablist"),
                        e.collapsible || (!1 !== e.active && null != e.active) || (e.active = 0),
                        this._processPanels(),
                        e.active < 0 && (e.active += this.headers.length),
                        this._refresh();
                },
                _getCreateEventData: function () {
                    return { header: this.active, panel: this.active.length ? this.active.next() : t() };
                },
                _createIcons: function () {
                    var e,
                        i = this.options.icons;
                    i &&
                        ((e = t("<span>")),
                        this._addClass(e, "ui-accordion-header-icon", "ui-icon " + i.header),
                        e.prependTo(this.headers),
                        (e = this.active.children(".ui-accordion-header-icon")),
                        this._removeClass(e, i.header)._addClass(e, null, i.activeHeader)._addClass(this.headers, "ui-accordion-icons"));
                },
                _destroyIcons: function () {
                    this._removeClass(this.headers, "ui-accordion-icons"), this.headers.children(".ui-accordion-header-icon").remove();
                },
                _destroy: function () {
                    var t;
                    this.element.removeAttr("role"),
                        this.headers.removeAttr("role aria-expanded aria-selected aria-controls tabIndex").removeUniqueId(),
                        this._destroyIcons(),
                        (t = this.headers.next().css("display", "").removeAttr("role aria-hidden aria-labelledby").removeUniqueId()),
                        "content" !== this.options.heightStyle && t.css("height", "");
                },
                _setOption: function (t, e) {
                    "active" !== t
                        ? ("event" === t && (this.options.event && this._off(this.headers, this.options.event), this._setupEvents(e)),
                          this._super(t, e),
                          "collapsible" !== t || e || !1 !== this.options.active || this._activate(0),
                          "icons" === t && (this._destroyIcons(), e && this._createIcons()))
                        : this._activate(e);
                },
                _setOptionDisabled: function (t) {
                    this._super(t), this.element.attr("aria-disabled", t), this._toggleClass(null, "ui-state-disabled", !!t), this._toggleClass(this.headers.add(this.headers.next()), null, "ui-state-disabled", !!t);
                },
                _keydown: function (e) {
                    if (!e.altKey && !e.ctrlKey) {
                        var i = t.ui.keyCode,
                            n = this.headers.length,
                            s = this.headers.index(e.target),
                            o = !1;
                        switch (e.keyCode) {
                            case i.RIGHT:
                            case i.DOWN:
                                o = this.headers[(s + 1) % n];
                                break;
                            case i.LEFT:
                            case i.UP:
                                o = this.headers[(s - 1 + n) % n];
                                break;
                            case i.SPACE:
                            case i.ENTER:
                                this._eventHandler(e);
                                break;
                            case i.HOME:
                                o = this.headers[0];
                                break;
                            case i.END:
                                o = this.headers[n - 1];
                        }
                        o && (t(e.target).attr("tabIndex", -1), t(o).attr("tabIndex", 0), t(o).trigger("focus"), e.preventDefault());
                    }
                },
                _panelKeyDown: function (e) {
                    e.keyCode === t.ui.keyCode.UP && e.ctrlKey && t(e.currentTarget).prev().trigger("focus");
                },
                refresh: function () {
                    var e = this.options;
                    this._processPanels(),
                        (!1 === e.active && !0 === e.collapsible) || !this.headers.length
                            ? ((e.active = !1), (this.active = t()))
                            : !1 === e.active
                            ? this._activate(0)
                            : this.active.length && !t.contains(this.element[0], this.active[0])
                            ? this.headers.length === this.headers.find(".ui-state-disabled").length
                                ? ((e.active = !1), (this.active = t()))
                                : this._activate(Math.max(0, e.active - 1))
                            : (e.active = this.headers.index(this.active)),
                        this._destroyIcons(),
                        this._refresh();
                },
                _processPanels: function () {
                    var t = this.headers,
                        e = this.panels;
                    "function" == typeof this.options.header ? (this.headers = this.options.header(this.element)) : (this.headers = this.element.find(this.options.header)),
                        this._addClass(this.headers, "ui-accordion-header ui-accordion-header-collapsed", "ui-state-default"),
                        (this.panels = this.headers.next().filter(":not(.ui-accordion-content-active)").hide()),
                        this._addClass(this.panels, "ui-accordion-content", "ui-helper-reset ui-widget-content"),
                        e && (this._off(t.not(this.headers)), this._off(e.not(this.panels)));
                },
                _refresh: function () {
                    var e,
                        i = this.options,
                        n = i.heightStyle,
                        s = this.element.parent();
                    (this.active = this._findActive(i.active)),
                        this._addClass(this.active, "ui-accordion-header-active", "ui-state-active")._removeClass(this.active, "ui-accordion-header-collapsed"),
                        this._addClass(this.active.next(), "ui-accordion-content-active"),
                        this.active.next().show(),
                        this.headers
                            .attr("role", "tab")
                            .each(function () {
                                var e = t(this),
                                    i = e.uniqueId().attr("id"),
                                    n = e.next(),
                                    s = n.uniqueId().attr("id");
                                e.attr("aria-controls", s), n.attr("aria-labelledby", i);
                            })
                            .next()
                            .attr("role", "tabpanel"),
                        this.headers.not(this.active).attr({ "aria-selected": "false", "aria-expanded": "false", tabIndex: -1 }).next().attr({ "aria-hidden": "true" }).hide(),
                        this.active.length ? this.active.attr({ "aria-selected": "true", "aria-expanded": "true", tabIndex: 0 }).next().attr({ "aria-hidden": "false" }) : this.headers.eq(0).attr("tabIndex", 0),
                        this._createIcons(),
                        this._setupEvents(i.event),
                        "fill" === n
                            ? ((e = s.height()),
                              this.element.siblings(":visible").each(function () {
                                  var i = t(this),
                                      n = i.css("position");
                                  "absolute" !== n && "fixed" !== n && (e -= i.outerHeight(!0));
                              }),
                              this.headers.each(function () {
                                  e -= t(this).outerHeight(!0);
                              }),
                              this.headers
                                  .next()
                                  .each(function () {
                                      t(this).height(Math.max(0, e - t(this).innerHeight() + t(this).height()));
                                  })
                                  .css("overflow", "auto"))
                            : "auto" === n &&
                              ((e = 0),
                              this.headers
                                  .next()
                                  .each(function () {
                                      var i = t(this).is(":visible");
                                      i || t(this).show(), (e = Math.max(e, t(this).css("height", "").height())), i || t(this).hide();
                                  })
                                  .height(e));
                },
                _activate: function (e) {
                    (e = this._findActive(e)[0]) !== this.active[0] && ((e = e || this.active[0]), this._eventHandler({ target: e, currentTarget: e, preventDefault: t.noop }));
                },
                _findActive: function (e) {
                    return "number" == typeof e ? this.headers.eq(e) : t();
                },
                _setupEvents: function (e) {
                    var i = { keydown: "_keydown" };
                    e &&
                        t.each(e.split(" "), function (t, e) {
                            i[e] = "_eventHandler";
                        }),
                        this._off(this.headers.add(this.headers.next())),
                        this._on(this.headers, i),
                        this._on(this.headers.next(), { keydown: "_panelKeyDown" }),
                        this._hoverable(this.headers),
                        this._focusable(this.headers);
                },
                _eventHandler: function (e) {
                    var i = this.options,
                        n = this.active,
                        s = t(e.currentTarget),
                        o = s[0] === n[0],
                        r = o && i.collapsible,
                        a = r ? t() : s.next(),
                        l = n.next();
                    a = { oldHeader: n, oldPanel: l, newHeader: r ? t() : s, newPanel: a };
                    e.preventDefault(),
                        (o && !i.collapsible) ||
                            !1 === this._trigger("beforeActivate", e, a) ||
                            ((i.active = !r && this.headers.index(s)),
                            (this.active = o ? t() : s),
                            this._toggle(a),
                            this._removeClass(n, "ui-accordion-header-active", "ui-state-active"),
                            i.icons && ((n = n.children(".ui-accordion-header-icon")), this._removeClass(n, null, i.icons.activeHeader)._addClass(n, null, i.icons.header)),
                            o ||
                                (this._removeClass(s, "ui-accordion-header-collapsed")._addClass(s, "ui-accordion-header-active", "ui-state-active"),
                                i.icons && ((o = s.children(".ui-accordion-header-icon")), this._removeClass(o, null, i.icons.header)._addClass(o, null, i.icons.activeHeader)),
                                this._addClass(s.next(), "ui-accordion-content-active")));
                },
                _toggle: function (e) {
                    var i = e.newPanel,
                        n = this.prevShow.length ? this.prevShow : e.oldPanel;
                    this.prevShow.add(this.prevHide).stop(!0, !0),
                        (this.prevShow = i),
                        (this.prevHide = n),
                        this.options.animate ? this._animate(i, n, e) : (n.hide(), i.show(), this._toggleComplete(e)),
                        n.attr({ "aria-hidden": "true" }),
                        n.prev().attr({ "aria-selected": "false", "aria-expanded": "false" }),
                        i.length && n.length
                            ? n.prev().attr({ tabIndex: -1, "aria-expanded": "false" })
                            : i.length &&
                              this.headers
                                  .filter(function () {
                                      return 0 === parseInt(t(this).attr("tabIndex"), 10);
                                  })
                                  .attr("tabIndex", -1),
                        i.attr("aria-hidden", "false").prev().attr({ "aria-selected": "true", "aria-expanded": "true", tabIndex: 0 });
                },
                _animate: function (t, e, i) {
                    var n,
                        s,
                        o,
                        r = this,
                        a = 0,
                        l = t.css("box-sizing"),
                        h = t.length && (!e.length || t.index() < e.index()),
                        c = this.options.animate || {},
                        u = (h && c.down) || c;
                    h = function () {
                        r._toggleComplete(i);
                    };
                    return (
                        (s = (s = "string" == typeof u ? u : s) || u.easing || c.easing),
                        (o = (o = "number" == typeof u ? u : o) || u.duration || c.duration),
                        e.length
                            ? t.length
                                ? ((n = t.show().outerHeight()),
                                  e.animate(this.hideProps, {
                                      duration: o,
                                      easing: s,
                                      step: function (t, e) {
                                          e.now = Math.round(t);
                                      },
                                  }),
                                  void t.hide().animate(this.showProps, {
                                      duration: o,
                                      easing: s,
                                      complete: h,
                                      step: function (t, i) {
                                          (i.now = Math.round(t)), "height" !== i.prop ? "content-box" === l && (a += i.now) : "content" !== r.options.heightStyle && ((i.now = Math.round(n - e.outerHeight() - a)), (a = 0));
                                      },
                                  }))
                                : e.animate(this.hideProps, o, s, h)
                            : t.animate(this.showProps, o, s, h)
                    );
                },
                _toggleComplete: function (t) {
                    var e = t.oldPanel,
                        i = e.prev();
                    this._removeClass(e, "ui-accordion-content-active"),
                        this._removeClass(i, "ui-accordion-header-active")._addClass(i, "ui-accordion-header-collapsed"),
                        e.length && (e.parent()[0].className = e.parent()[0].className),
                        this._trigger("activate", null, t);
                },
            }),
            (t.ui.safeActiveElement = function (t) {
                var e;
                try {
                    e = t.activeElement;
                } catch (i) {
                    e = t.body;
                }
                return (e = e || t.body).nodeName ? e : t.body;
            }),
            t.widget("ui.menu", {
                version: "1.13.2",
                defaultElement: "<ul>",
                delay: 300,
                options: { icons: { submenu: "ui-icon-caret-1-e" }, items: "> *", menus: "ul", position: { my: "left top", at: "right top" }, role: "menu", blur: null, focus: null, select: null },
                _create: function () {
                    (this.activeMenu = this.element),
                        (this.mouseHandled = !1),
                        (this.lastMousePosition = { x: null, y: null }),
                        this.element.uniqueId().attr({ role: this.options.role, tabIndex: 0 }),
                        this._addClass("ui-menu", "ui-widget ui-widget-content"),
                        this._on({
                            "mousedown .ui-menu-item": function (t) {
                                t.preventDefault(), this._activateItem(t);
                            },
                            "click .ui-menu-item": function (e) {
                                var i = t(e.target),
                                    n = t(t.ui.safeActiveElement(this.document[0]));
                                !this.mouseHandled &&
                                    i.not(".ui-state-disabled").length &&
                                    (this.select(e),
                                    e.isPropagationStopped() || (this.mouseHandled = !0),
                                    i.has(".ui-menu").length
                                        ? this.expand(e)
                                        : !this.element.is(":focus") && n.closest(".ui-menu").length && (this.element.trigger("focus", [!0]), this.active && 1 === this.active.parents(".ui-menu").length && clearTimeout(this.timer)));
                            },
                            "mouseenter .ui-menu-item": "_activateItem",
                            "mousemove .ui-menu-item": "_activateItem",
                            mouseleave: "collapseAll",
                            "mouseleave .ui-menu": "collapseAll",
                            focus: function (t, e) {
                                var i = this.active || this._menuItems().first();
                                e || this.focus(t, i);
                            },
                            blur: function (e) {
                                this._delay(function () {
                                    t.contains(this.element[0], t.ui.safeActiveElement(this.document[0])) || this.collapseAll(e);
                                });
                            },
                            keydown: "_keydown",
                        }),
                        this.refresh(),
                        this._on(this.document, {
                            click: function (t) {
                                this._closeOnDocumentClick(t) && this.collapseAll(t, !0), (this.mouseHandled = !1);
                            },
                        });
                },
                _activateItem: function (e) {
                    var i, n;
                    this.previousFilter ||
                        (e.clientX === this.lastMousePosition.x && e.clientY === this.lastMousePosition.y) ||
                        ((this.lastMousePosition = { x: e.clientX, y: e.clientY }),
                        (i = t(e.target).closest(".ui-menu-item")),
                        (n = t(e.currentTarget)),
                        i[0] === n[0] && (n.is(".ui-state-active") || (this._removeClass(n.siblings().children(".ui-state-active"), null, "ui-state-active"), this.focus(e, n))));
                },
                _destroy: function () {
                    var e = this.element.find(".ui-menu-item").removeAttr("role aria-disabled").children(".ui-menu-item-wrapper").removeUniqueId().removeAttr("tabIndex role aria-haspopup");
                    this.element.removeAttr("aria-activedescendant").find(".ui-menu").addBack().removeAttr("role aria-labelledby aria-expanded aria-hidden aria-disabled tabIndex").removeUniqueId().show(),
                        e.children().each(function () {
                            var e = t(this);
                            e.data("ui-menu-submenu-caret") && e.remove();
                        });
                },
                _keydown: function (e) {
                    var i,
                        n,
                        s,
                        o = !0;
                    switch (e.keyCode) {
                        case t.ui.keyCode.PAGE_UP:
                            this.previousPage(e);
                            break;
                        case t.ui.keyCode.PAGE_DOWN:
                            this.nextPage(e);
                            break;
                        case t.ui.keyCode.HOME:
                            this._move("first", "first", e);
                            break;
                        case t.ui.keyCode.END:
                            this._move("last", "last", e);
                            break;
                        case t.ui.keyCode.UP:
                            this.previous(e);
                            break;
                        case t.ui.keyCode.DOWN:
                            this.next(e);
                            break;
                        case t.ui.keyCode.LEFT:
                            this.collapse(e);
                            break;
                        case t.ui.keyCode.RIGHT:
                            this.active && !this.active.is(".ui-state-disabled") && this.expand(e);
                            break;
                        case t.ui.keyCode.ENTER:
                        case t.ui.keyCode.SPACE:
                            this._activate(e);
                            break;
                        case t.ui.keyCode.ESCAPE:
                            this.collapse(e);
                            break;
                        default:
                            (i = this.previousFilter || ""),
                                (s = o = !1),
                                (n = 96 <= e.keyCode && e.keyCode <= 105 ? (e.keyCode - 96).toString() : String.fromCharCode(e.keyCode)),
                                clearTimeout(this.filterTimer),
                                n === i ? (s = !0) : (n = i + n),
                                (i = this._filterMenuItems(n)),
                                (i = s && -1 !== i.index(this.active.next()) ? this.active.nextAll(".ui-menu-item") : i).length || ((n = String.fromCharCode(e.keyCode)), (i = this._filterMenuItems(n))),
                                i.length
                                    ? (this.focus(e, i),
                                      (this.previousFilter = n),
                                      (this.filterTimer = this._delay(function () {
                                          delete this.previousFilter;
                                      }, 1e3)))
                                    : delete this.previousFilter;
                    }
                    o && e.preventDefault();
                },
                _activate: function (t) {
                    this.active && !this.active.is(".ui-state-disabled") && (this.active.children("[aria-haspopup='true']").length ? this.expand(t) : this.select(t));
                },
                refresh: function () {
                    var e,
                        i,
                        n = this,
                        s = this.options.icons.submenu,
                        o = this.element.find(this.options.menus);
                    this._toggleClass("ui-menu-icons", null, !!this.element.find(".ui-icon").length),
                        (i = o
                            .filter(":not(.ui-menu)")
                            .hide()
                            .attr({ role: this.options.role, "aria-hidden": "true", "aria-expanded": "false" })
                            .each(function () {
                                var e = t(this),
                                    i = e.prev(),
                                    o = t("<span>").data("ui-menu-submenu-caret", !0);
                                n._addClass(o, "ui-menu-icon", "ui-icon " + s), i.attr("aria-haspopup", "true").prepend(o), e.attr("aria-labelledby", i.attr("id"));
                            })),
                        this._addClass(i, "ui-menu", "ui-widget ui-widget-content ui-front"),
                        (e = o.add(this.element).find(this.options.items)).not(".ui-menu-item").each(function () {
                            var e = t(this);
                            n._isDivider(e) && n._addClass(e, "ui-menu-divider", "ui-widget-content");
                        }),
                        (o = (i = e.not(".ui-menu-item, .ui-menu-divider")).children().not(".ui-menu").uniqueId().attr({ tabIndex: -1, role: this._itemRole() })),
                        this._addClass(i, "ui-menu-item")._addClass(o, "ui-menu-item-wrapper"),
                        e.filter(".ui-state-disabled").attr("aria-disabled", "true"),
                        this.active && !t.contains(this.element[0], this.active[0]) && this.blur();
                },
                _itemRole: function () {
                    return { menu: "menuitem", listbox: "option" }[this.options.role];
                },
                _setOption: function (t, e) {
                    var i;
                    "icons" === t && ((i = this.element.find(".ui-menu-icon")), this._removeClass(i, null, this.options.icons.submenu)._addClass(i, null, e.submenu)), this._super(t, e);
                },
                _setOptionDisabled: function (t) {
                    this._super(t), this.element.attr("aria-disabled", String(t)), this._toggleClass(null, "ui-state-disabled", !!t);
                },
                focus: function (t, e) {
                    var i;
                    this.blur(t, t && "focus" === t.type),
                        this._scrollIntoView(e),
                        (this.active = e.first()),
                        (i = this.active.children(".ui-menu-item-wrapper")),
                        this._addClass(i, null, "ui-state-active"),
                        this.options.role && this.element.attr("aria-activedescendant", i.attr("id")),
                        (i = this.active.parent().closest(".ui-menu-item").children(".ui-menu-item-wrapper")),
                        this._addClass(i, null, "ui-state-active"),
                        t && "keydown" === t.type
                            ? this._close()
                            : (this.timer = this._delay(function () {
                                  this._close();
                              }, this.delay)),
                        (i = e.children(".ui-menu")).length && t && /^mouse/.test(t.type) && this._startOpening(i),
                        (this.activeMenu = e.parent()),
                        this._trigger("focus", t, { item: e });
                },
                _scrollIntoView: function (e) {
                    var i, n, s;
                    this._hasScroll() &&
                        ((n = parseFloat(t.css(this.activeMenu[0], "borderTopWidth")) || 0),
                        (s = parseFloat(t.css(this.activeMenu[0], "paddingTop")) || 0),
                        (i = e.offset().top - this.activeMenu.offset().top - n - s),
                        (n = this.activeMenu.scrollTop()),
                        (s = this.activeMenu.height()),
                        (e = e.outerHeight()),
                        i < 0 ? this.activeMenu.scrollTop(n + i) : s < i + e && this.activeMenu.scrollTop(n + i - s + e));
                },
                blur: function (t, e) {
                    e || clearTimeout(this.timer), this.active && (this._removeClass(this.active.children(".ui-menu-item-wrapper"), null, "ui-state-active"), this._trigger("blur", t, { item: this.active }), (this.active = null));
                },
                _startOpening: function (t) {
                    clearTimeout(this.timer),
                        "true" === t.attr("aria-hidden") &&
                            (this.timer = this._delay(function () {
                                this._close(), this._open(t);
                            }, this.delay));
                },
                _open: function (e) {
                    var i = t.extend({ of: this.active }, this.options.position);
                    clearTimeout(this.timer), this.element.find(".ui-menu").not(e.parents(".ui-menu")).hide().attr("aria-hidden", "true"), e.show().removeAttr("aria-hidden").attr("aria-expanded", "true").position(i);
                },
                collapseAll: function (e, i) {
                    clearTimeout(this.timer),
                        (this.timer = this._delay(
                            function () {
                                var n = i ? this.element : t(e && e.target).closest(this.element.find(".ui-menu"));
                                n.length || (n = this.element), this._close(n), this.blur(e), this._removeClass(n.find(".ui-state-active"), null, "ui-state-active"), (this.activeMenu = n);
                            },
                            i ? 0 : this.delay
                        ));
                },
                _close: function (t) {
                    (t = t || (this.active ? this.active.parent() : this.element)).find(".ui-menu").hide().attr("aria-hidden", "true").attr("aria-expanded", "false");
                },
                _closeOnDocumentClick: function (e) {
                    return !t(e.target).closest(".ui-menu").length;
                },
                _isDivider: function (t) {
                    return !/[^\-\u2014\u2013\s]/.test(t.text());
                },
                collapse: function (t) {
                    var e = this.active && this.active.parent().closest(".ui-menu-item", this.element);
                    e && e.length && (this._close(), this.focus(t, e));
                },
                expand: function (t) {
                    var e = this.active && this._menuItems(this.active.children(".ui-menu")).first();
                    e &&
                        e.length &&
                        (this._open(e.parent()),
                        this._delay(function () {
                            this.focus(t, e);
                        }));
                },
                next: function (t) {
                    this._move("next", "first", t);
                },
                previous: function (t) {
                    this._move("prev", "last", t);
                },
                isFirstItem: function () {
                    return this.active && !this.active.prevAll(".ui-menu-item").length;
                },
                isLastItem: function () {
                    return this.active && !this.active.nextAll(".ui-menu-item").length;
                },
                _menuItems: function (t) {
                    return (t || this.element).find(this.options.items).filter(".ui-menu-item");
                },
                _move: function (t, e, i) {
                    var n;
                    ((n = this.active ? ("first" === t || "last" === t ? this.active["first" === t ? "prevAll" : "nextAll"](".ui-menu-item").last() : this.active[t + "All"](".ui-menu-item").first()) : n) && n.length && this.active) ||
                        (n = this._menuItems(this.activeMenu)[e]()),
                        this.focus(i, n);
                },
                nextPage: function (e) {
                    var i, n, s;
                    this.active
                        ? this.isLastItem() ||
                          (this._hasScroll()
                              ? ((n = this.active.offset().top),
                                (s = this.element.innerHeight()),
                                0 === t.fn.jquery.indexOf("3.2.") && (s += this.element[0].offsetHeight - this.element.outerHeight()),
                                this.active.nextAll(".ui-menu-item").each(function () {
                                    return (i = t(this)).offset().top - n - s < 0;
                                }),
                                this.focus(e, i))
                              : this.focus(e, this._menuItems(this.activeMenu)[this.active ? "last" : "first"]()))
                        : this.next(e);
                },
                previousPage: function (e) {
                    var i, n, s;
                    this.active
                        ? this.isFirstItem() ||
                          (this._hasScroll()
                              ? ((n = this.active.offset().top),
                                (s = this.element.innerHeight()),
                                0 === t.fn.jquery.indexOf("3.2.") && (s += this.element[0].offsetHeight - this.element.outerHeight()),
                                this.active.prevAll(".ui-menu-item").each(function () {
                                    return 0 < (i = t(this)).offset().top - n + s;
                                }),
                                this.focus(e, i))
                              : this.focus(e, this._menuItems(this.activeMenu).first()))
                        : this.next(e);
                },
                _hasScroll: function () {
                    return this.element.outerHeight() < this.element.prop("scrollHeight");
                },
                select: function (e) {
                    this.active = this.active || t(e.target).closest(".ui-menu-item");
                    var i = { item: this.active };
                    this.active.has(".ui-menu").length || this.collapseAll(e, !0), this._trigger("select", e, i);
                },
                _filterMenuItems: function (e) {
                    e = e.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, "\\$&");
                    var i = new RegExp("^" + e, "i");
                    return this.activeMenu
                        .find(this.options.items)
                        .filter(".ui-menu-item")
                        .filter(function () {
                            return i.test(String.prototype.trim.call(t(this).children(".ui-menu-item-wrapper").text()));
                        });
                },
            }),
            t.widget("ui.autocomplete", {
                version: "1.13.2",
                defaultElement: "<input>",
                options: {
                    appendTo: null,
                    autoFocus: !1,
                    delay: 300,
                    minLength: 1,
                    position: { my: "left top", at: "left bottom", collision: "none" },
                    source: null,
                    change: null,
                    close: null,
                    focus: null,
                    open: null,
                    response: null,
                    search: null,
                    select: null,
                },
                requestIndex: 0,
                pending: 0,
                liveRegionTimer: null,
                _create: function () {
                    var e,
                        i,
                        n,
                        s = "textarea" === (o = this.element[0].nodeName.toLowerCase()),
                        o = "input" === o;
                    (this.isMultiLine = s || (!o && this._isContentEditable(this.element))),
                        (this.valueMethod = this.element[s || o ? "val" : "text"]),
                        (this.isNewMenu = !0),
                        this._addClass("ui-autocomplete-input"),
                        this.element.attr("autocomplete", "off"),
                        this._on(this.element, {
                            keydown: function (s) {
                                if (this.element.prop("readOnly")) i = n = e = !0;
                                else {
                                    i = n = e = !1;
                                    var o = t.ui.keyCode;
                                    switch (s.keyCode) {
                                        case o.PAGE_UP:
                                            (e = !0), this._move("previousPage", s);
                                            break;
                                        case o.PAGE_DOWN:
                                            (e = !0), this._move("nextPage", s);
                                            break;
                                        case o.UP:
                                            (e = !0), this._keyEvent("previous", s);
                                            break;
                                        case o.DOWN:
                                            (e = !0), this._keyEvent("next", s);
                                            break;
                                        case o.ENTER:
                                            this.menu.active && ((e = !0), s.preventDefault(), this.menu.select(s));
                                            break;
                                        case o.TAB:
                                            this.menu.active && this.menu.select(s);
                                            break;
                                        case o.ESCAPE:
                                            this.menu.element.is(":visible") && (this.isMultiLine || this._value(this.term), this.close(s), s.preventDefault());
                                            break;
                                        default:
                                            (i = !0), this._searchTimeout(s);
                                    }
                                }
                            },
                            keypress: function (n) {
                                if (e) return (e = !1), void ((this.isMultiLine && !this.menu.element.is(":visible")) || n.preventDefault());
                                if (!i) {
                                    var s = t.ui.keyCode;
                                    switch (n.keyCode) {
                                        case s.PAGE_UP:
                                            this._move("previousPage", n);
                                            break;
                                        case s.PAGE_DOWN:
                                            this._move("nextPage", n);
                                            break;
                                        case s.UP:
                                            this._keyEvent("previous", n);
                                            break;
                                        case s.DOWN:
                                            this._keyEvent("next", n);
                                    }
                                }
                            },
                            input: function (t) {
                                if (n) return (n = !1), void t.preventDefault();
                                this._searchTimeout(t);
                            },
                            focus: function () {
                                (this.selectedItem = null), (this.previous = this._value());
                            },
                            blur: function (t) {
                                clearTimeout(this.searching), this.close(t), this._change(t);
                            },
                        }),
                        this._initSource(),
                        (this.menu = t("<ul>").appendTo(this._appendTo()).menu({ role: null }).hide().attr({ unselectable: "on" }).menu("instance")),
                        this._addClass(this.menu.element, "ui-autocomplete", "ui-front"),
                        this._on(this.menu.element, {
                            mousedown: function (t) {
                                t.preventDefault();
                            },
                            menufocus: function (e, i) {
                                var n, s;
                                if (this.isNewMenu && ((this.isNewMenu = !1), e.originalEvent && /^mouse/.test(e.originalEvent.type)))
                                    return (
                                        this.menu.blur(),
                                        void this.document.one("mousemove", function () {
                                            t(e.target).trigger(e.originalEvent);
                                        })
                                    );
                                (s = i.item.data("ui-autocomplete-item")),
                                    !1 !== this._trigger("focus", e, { item: s }) && e.originalEvent && /^key/.test(e.originalEvent.type) && this._value(s.value),
                                    (n = i.item.attr("aria-label") || s.value) &&
                                        String.prototype.trim.call(n).length &&
                                        (clearTimeout(this.liveRegionTimer),
                                        (this.liveRegionTimer = this._delay(function () {
                                            this.liveRegion.html(t("<div>").text(n));
                                        }, 100)));
                            },
                            menuselect: function (e, i) {
                                var n = i.item.data("ui-autocomplete-item"),
                                    s = this.previous;
                                this.element[0] !== t.ui.safeActiveElement(this.document[0]) &&
                                    (this.element.trigger("focus"),
                                    (this.previous = s),
                                    this._delay(function () {
                                        (this.previous = s), (this.selectedItem = n);
                                    })),
                                    !1 !== this._trigger("select", e, { item: n }) && this._value(n.value),
                                    (this.term = this._value()),
                                    this.close(e),
                                    (this.selectedItem = n);
                            },
                        }),
                        (this.liveRegion = t("<div>", { role: "status", "aria-live": "assertive", "aria-relevant": "additions" }).appendTo(this.document[0].body)),
                        this._addClass(this.liveRegion, null, "ui-helper-hidden-accessible"),
                        this._on(this.window, {
                            beforeunload: function () {
                                this.element.removeAttr("autocomplete");
                            },
                        });
                },
                _destroy: function () {
                    clearTimeout(this.searching), this.element.removeAttr("autocomplete"), this.menu.element.remove(), this.liveRegion.remove();
                },
                _setOption: function (t, e) {
                    this._super(t, e), "source" === t && this._initSource(), "appendTo" === t && this.menu.element.appendTo(this._appendTo()), "disabled" === t && e && this.xhr && this.xhr.abort();
                },
                _isEventTargetInWidget: function (e) {
                    var i = this.menu.element[0];
                    return e.target === this.element[0] || e.target === i || t.contains(i, e.target);
                },
                _closeOnClickOutside: function (t) {
                    this._isEventTargetInWidget(t) || this.close();
                },
                _appendTo: function () {
                    var e = this.options.appendTo;
                    return (e = (e = e && (e.jquery || e.nodeType ? t(e) : this.document.find(e).eq(0))) && e[0] ? e : this.element.closest(".ui-front, dialog")).length ? e : this.document[0].body;
                },
                _initSource: function () {
                    var e,
                        i,
                        n = this;
                    Array.isArray(this.options.source)
                        ? ((e = this.options.source),
                          (this.source = function (i, n) {
                              n(t.ui.autocomplete.filter(e, i.term));
                          }))
                        : "string" == typeof this.options.source
                        ? ((i = this.options.source),
                          (this.source = function (e, s) {
                              n.xhr && n.xhr.abort(),
                                  (n.xhr = t.ajax({
                                      url: i,
                                      data: e,
                                      dataType: "json",
                                      success: function (t) {
                                          s(t);
                                      },
                                      error: function () {
                                          s([]);
                                      },
                                  }));
                          }))
                        : (this.source = this.options.source);
                },
                _searchTimeout: function (t) {
                    clearTimeout(this.searching),
                        (this.searching = this._delay(function () {
                            var e = this.term === this._value(),
                                i = this.menu.element.is(":visible"),
                                n = t.altKey || t.ctrlKey || t.metaKey || t.shiftKey;
                            (e && (i || n)) || ((this.selectedItem = null), this.search(null, t));
                        }, this.options.delay));
                },
                search: function (t, e) {
                    return (t = null != t ? t : this._value()), (this.term = this._value()), t.length < this.options.minLength ? this.close(e) : !1 !== this._trigger("search", e) ? this._search(t) : void 0;
                },
                _search: function (t) {
                    this.pending++, this._addClass("ui-autocomplete-loading"), (this.cancelSearch = !1), this.source({ term: t }, this._response());
                },
                _response: function () {
                    var t = ++this.requestIndex;
                    return function (e) {
                        t === this.requestIndex && this.__response(e), this.pending--, this.pending || this._removeClass("ui-autocomplete-loading");
                    }.bind(this);
                },
                __response: function (t) {
                    (t = t && this._normalize(t)), this._trigger("response", null, { content: t }), !this.options.disabled && t && t.length && !this.cancelSearch ? (this._suggest(t), this._trigger("open")) : this._close();
                },
                close: function (t) {
                    (this.cancelSearch = !0), this._close(t);
                },
                _close: function (t) {
                    this._off(this.document, "mousedown"), this.menu.element.is(":visible") && (this.menu.element.hide(), this.menu.blur(), (this.isNewMenu = !0), this._trigger("close", t));
                },
                _change: function (t) {
                    this.previous !== this._value() && this._trigger("change", t, { item: this.selectedItem });
                },
                _normalize: function (e) {
                    return e.length && e[0].label && e[0].value
                        ? e
                        : t.map(e, function (e) {
                              return "string" == typeof e ? { label: e, value: e } : t.extend({}, e, { label: e.label || e.value, value: e.value || e.label });
                          });
                },
                _suggest: function (e) {
                    var i = this.menu.element.empty();
                    this._renderMenu(i, e),
                        (this.isNewMenu = !0),
                        this.menu.refresh(),
                        i.show(),
                        this._resizeMenu(),
                        i.position(t.extend({ of: this.element }, this.options.position)),
                        this.options.autoFocus && this.menu.next(),
                        this._on(this.document, { mousedown: "_closeOnClickOutside" });
                },
                _resizeMenu: function () {
                    var t = this.menu.element;
                    t.outerWidth(Math.max(t.width("").outerWidth() + 1, this.element.outerWidth()));
                },
                _renderMenu: function (e, i) {
                    var n = this;
                    t.each(i, function (t, i) {
                        n._renderItemData(e, i);
                    });
                },
                _renderItemData: function (t, e) {
                    return this._renderItem(t, e).data("ui-autocomplete-item", e);
                },
                _renderItem: function (e, i) {
                    return t("<li>").append(t("<div>").text(i.label)).appendTo(e);
                },
                _move: function (t, e) {
                    if (this.menu.element.is(":visible"))
                        return (this.menu.isFirstItem() && /^previous/.test(t)) || (this.menu.isLastItem() && /^next/.test(t)) ? (this.isMultiLine || this._value(this.term), void this.menu.blur()) : void this.menu[t](e);
                    this.search(null, e);
                },
                widget: function () {
                    return this.menu.element;
                },
                _value: function () {
                    return this.valueMethod.apply(this.element, arguments);
                },
                _keyEvent: function (t, e) {
                    (this.isMultiLine && !this.menu.element.is(":visible")) || (this._move(t, e), e.preventDefault());
                },
                _isContentEditable: function (t) {
                    if (!t.length) return !1;
                    var e = t.prop("contentEditable");
                    return "inherit" === e ? this._isContentEditable(t.parent()) : "true" === e;
                },
            }),
            t.extend(t.ui.autocomplete, {
                escapeRegex: function (t) {
                    return t.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, "\\$&");
                },
                filter: function (e, i) {
                    var n = new RegExp(t.ui.autocomplete.escapeRegex(i), "i");
                    return t.grep(e, function (t) {
                        return n.test(t.label || t.value || t);
                    });
                },
            }),
            t.widget("ui.autocomplete", t.ui.autocomplete, {
                options: {
                    messages: {
                        noResults: "No search results.",
                        results: function (t) {
                            return t + (1 < t ? " results are" : " result is") + " available, use up and down arrow keys to navigate.";
                        },
                    },
                },
                __response: function (e) {
                    var i;
                    this._superApply(arguments),
                        this.options.disabled ||
                            this.cancelSearch ||
                            ((i = e && e.length ? this.options.messages.results(e.length) : this.options.messages.noResults),
                            clearTimeout(this.liveRegionTimer),
                            (this.liveRegionTimer = this._delay(function () {
                                this.liveRegion.html(t("<div>").text(i));
                            }, 100)));
                },
            }),
            t.ui.autocomplete;
        var tt,
            et,
            it = /ui-corner-([a-z]){2,6}/g;
        function nt() {
            (this._curInst = null),
                (this._keyEvent = !1),
                (this._disabledInputs = []),
                (this._datepickerShowing = !1),
                (this._inDialog = !1),
                (this._mainDivId = "ui-datepicker-div"),
                (this._inlineClass = "ui-datepicker-inline"),
                (this._appendClass = "ui-datepicker-append"),
                (this._triggerClass = "ui-datepicker-trigger"),
                (this._dialogClass = "ui-datepicker-dialog"),
                (this._disableClass = "ui-datepicker-disabled"),
                (this._unselectableClass = "ui-datepicker-unselectable"),
                (this._currentClass = "ui-datepicker-current-day"),
                (this._dayOverClass = "ui-datepicker-days-cell-over"),
                (this.regional = []),
                (this.regional[""] = {
                    closeText: "Done",
                    prevText: "Prev",
                    nextText: "Next",
                    currentText: "Today",
                    monthNames: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                    monthNamesShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                    dayNames: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
                    dayNamesShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
                    dayNamesMin: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
                    weekHeader: "Wk",
                    dateFormat: "mm/dd/yy",
                    firstDay: 0,
                    isRTL: !1,
                    showMonthAfterYear: !1,
                    yearSuffix: "",
                    selectMonthLabel: "Select month",
                    selectYearLabel: "Select year",
                }),
                (this._defaults = {
                    showOn: "focus",
                    showAnim: "fadeIn",
                    showOptions: {},
                    defaultDate: null,
                    appendText: "",
                    buttonText: "...",
                    buttonImage: "",
                    buttonImageOnly: !1,
                    hideIfNoPrevNext: !1,
                    navigationAsDateFormat: !1,
                    gotoCurrent: !1,
                    changeMonth: !1,
                    changeYear: !1,
                    yearRange: "c-10:c+10",
                    showOtherMonths: !1,
                    selectOtherMonths: !1,
                    showWeek: !1,
                    calculateWeek: this.iso8601Week,
                    shortYearCutoff: "+10",
                    minDate: null,
                    maxDate: null,
                    duration: "fast",
                    beforeShowDay: null,
                    beforeShow: null,
                    onSelect: null,
                    onChangeMonthYear: null,
                    onClose: null,
                    onUpdateDatepicker: null,
                    numberOfMonths: 1,
                    showCurrentAtPos: 0,
                    stepMonths: 1,
                    stepBigMonths: 12,
                    altField: "",
                    altFormat: "",
                    constrainInput: !0,
                    showButtonPanel: !1,
                    autoSize: !1,
                    disabled: !1,
                }),
                t.extend(this._defaults, this.regional[""]),
                (this.regional.en = t.extend(!0, {}, this.regional[""])),
                (this.regional["en-US"] = t.extend(!0, {}, this.regional.en)),
                (this.dpDiv = st(t("<div id='" + this._mainDivId + "' class='ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all'></div>")));
        }
        function st(e) {
            var i = "button, .ui-datepicker-prev, .ui-datepicker-next, .ui-datepicker-calendar td a";
            return e
                .on("mouseout", i, function () {
                    t(this).removeClass("ui-state-hover"),
                        -1 !== this.className.indexOf("ui-datepicker-prev") && t(this).removeClass("ui-datepicker-prev-hover"),
                        -1 !== this.className.indexOf("ui-datepicker-next") && t(this).removeClass("ui-datepicker-next-hover");
                })
                .on("mouseover", i, ot);
        }
        function ot() {
            t.datepicker._isDisabledDatepicker((et.inline ? et.dpDiv.parent() : et.input)[0]) ||
                (t(this).parents(".ui-datepicker-calendar").find("a").removeClass("ui-state-hover"),
                t(this).addClass("ui-state-hover"),
                -1 !== this.className.indexOf("ui-datepicker-prev") && t(this).addClass("ui-datepicker-prev-hover"),
                -1 !== this.className.indexOf("ui-datepicker-next") && t(this).addClass("ui-datepicker-next-hover"));
        }
        function rt(e, i) {
            for (var n in (t.extend(e, i), i)) null == i[n] && (e[n] = i[n]);
            return e;
        }
        t.widget("ui.controlgroup", {
            version: "1.13.2",
            defaultElement: "<div>",
            options: {
                direction: "horizontal",
                disabled: null,
                onlyVisible: !0,
                items: {
                    button: "input[type=button], input[type=submit], input[type=reset], button, a",
                    controlgroupLabel: ".ui-controlgroup-label",
                    checkboxradio: "input[type='checkbox'], input[type='radio']",
                    selectmenu: "select",
                    spinner: ".ui-spinner-input",
                },
            },
            _create: function () {
                this._enhance();
            },
            _enhance: function () {
                this.element.attr("role", "toolbar"), this.refresh();
            },
            _destroy: function () {
                this._callChildMethod("destroy"),
                    this.childWidgets.removeData("ui-controlgroup-data"),
                    this.element.removeAttr("role"),
                    this.options.items.controlgroupLabel && this.element.find(this.options.items.controlgroupLabel).find(".ui-controlgroup-label-contents").contents().unwrap();
            },
            _initWidgets: function () {
                var e = this,
                    i = [];
                t.each(this.options.items, function (n, s) {
                    var o,
                        r = {};
                    if (s)
                        return "controlgroupLabel" === n
                            ? ((o = e.element.find(s)).each(function () {
                                  var e = t(this);
                                  e.children(".ui-controlgroup-label-contents").length || e.contents().wrapAll("<span class='ui-controlgroup-label-contents'></span>");
                              }),
                              e._addClass(o, null, "ui-widget ui-widget-content ui-state-default"),
                              void (i = i.concat(o.get())))
                            : void (
                                  t.fn[n] &&
                                  ((r = e["_" + n + "Options"] ? e["_" + n + "Options"]("middle") : { classes: {} }),
                                  e.element.find(s).each(function () {
                                      var s = t(this),
                                          o = s[n]("instance"),
                                          a = t.widget.extend({}, r);
                                      ("button" === n && s.parent(".ui-spinner").length) ||
                                          ((o = o || s[n]()[n]("instance")) && (a.classes = e._resolveClassesValues(a.classes, o)), s[n](a), (a = s[n]("widget")), t.data(a[0], "ui-controlgroup-data", o || s[n]("instance")), i.push(a[0]));
                                  }))
                              );
                }),
                    (this.childWidgets = t(t.uniqueSort(i))),
                    this._addClass(this.childWidgets, "ui-controlgroup-item");
            },
            _callChildMethod: function (e) {
                this.childWidgets.each(function () {
                    var i = t(this).data("ui-controlgroup-data");
                    i && i[e] && i[e]();
                });
            },
            _updateCornerClass: function (t, e) {
                (e = this._buildSimpleOptions(e, "label").classes.label), this._removeClass(t, null, "ui-corner-top ui-corner-bottom ui-corner-left ui-corner-right ui-corner-all"), this._addClass(t, null, e);
            },
            _buildSimpleOptions: function (t, e) {
                var i = "vertical" === this.options.direction,
                    n = { classes: {} };
                return (n.classes[e] = { middle: "", first: "ui-corner-" + (i ? "top" : "left"), last: "ui-corner-" + (i ? "bottom" : "right"), only: "ui-corner-all" }[t]), n;
            },
            _spinnerOptions: function (t) {
                return ((t = this._buildSimpleOptions(t, "ui-spinner")).classes["ui-spinner-up"] = ""), (t.classes["ui-spinner-down"] = ""), t;
            },
            _buttonOptions: function (t) {
                return this._buildSimpleOptions(t, "ui-button");
            },
            _checkboxradioOptions: function (t) {
                return this._buildSimpleOptions(t, "ui-checkboxradio-label");
            },
            _selectmenuOptions: function (t) {
                var e = "vertical" === this.options.direction;
                return {
                    width: e && "auto",
                    classes: {
                        middle: { "ui-selectmenu-button-open": "", "ui-selectmenu-button-closed": "" },
                        first: { "ui-selectmenu-button-open": "ui-corner-" + (e ? "top" : "tl"), "ui-selectmenu-button-closed": "ui-corner-" + (e ? "top" : "left") },
                        last: { "ui-selectmenu-button-open": e ? "" : "ui-corner-tr", "ui-selectmenu-button-closed": "ui-corner-" + (e ? "bottom" : "right") },
                        only: { "ui-selectmenu-button-open": "ui-corner-top", "ui-selectmenu-button-closed": "ui-corner-all" },
                    }[t],
                };
            },
            _resolveClassesValues: function (e, i) {
                var n = {};
                return (
                    t.each(e, function (t) {
                        var s = i.options.classes[t] || "";
                        s = String.prototype.trim.call(s.replace(it, ""));
                        n[t] = (s + " " + e[t]).replace(/\s+/g, " ");
                    }),
                    n
                );
            },
            _setOption: function (t, e) {
                "direction" === t && this._removeClass("ui-controlgroup-" + this.options.direction), this._super(t, e), "disabled" !== t ? this.refresh() : this._callChildMethod(e ? "disable" : "enable");
            },
            refresh: function () {
                var e,
                    i = this;
                this._addClass("ui-controlgroup ui-controlgroup-" + this.options.direction),
                    "horizontal" === this.options.direction && this._addClass(null, "ui-helper-clearfix"),
                    this._initWidgets(),
                    (e = this.childWidgets),
                    (e = this.options.onlyVisible ? e.filter(":visible") : e).length &&
                        (t.each(["first", "last"], function (t, n) {
                            var s,
                                o = e[n]().data("ui-controlgroup-data");
                            o && i["_" + o.widgetName + "Options"]
                                ? (((s = i["_" + o.widgetName + "Options"](1 === e.length ? "only" : n)).classes = i._resolveClassesValues(s.classes, o)), o.element[o.widgetName](s))
                                : i._updateCornerClass(e[n](), n);
                        }),
                        this._callChildMethod("refresh"));
            },
        }),
            t.widget("ui.checkboxradio", [
                t.ui.formResetMixin,
                {
                    version: "1.13.2",
                    options: { disabled: null, label: null, icon: !0, classes: { "ui-checkboxradio-label": "ui-corner-all", "ui-checkboxradio-icon": "ui-corner-all" } },
                    _getCreateOptions: function () {
                        var e,
                            i = this._super() || {};
                        return (
                            this._readType(),
                            (e = this.element.labels()),
                            (this.label = t(e[e.length - 1])),
                            this.label.length || t.error("No label found for checkboxradio widget"),
                            (this.originalLabel = ""),
                            (e = this.label.contents().not(this.element[0])).length && (this.originalLabel += e.clone().wrapAll("<div></div>").parent().html()),
                            this.originalLabel && (i.label = this.originalLabel),
                            null != (e = this.element[0].disabled) && (i.disabled = e),
                            i
                        );
                    },
                    _create: function () {
                        var t = this.element[0].checked;
                        this._bindFormResetHandler(),
                            null == this.options.disabled && (this.options.disabled = this.element[0].disabled),
                            this._setOption("disabled", this.options.disabled),
                            this._addClass("ui-checkboxradio", "ui-helper-hidden-accessible"),
                            this._addClass(this.label, "ui-checkboxradio-label", "ui-button ui-widget"),
                            "radio" === this.type && this._addClass(this.label, "ui-checkboxradio-radio-label"),
                            this.options.label && this.options.label !== this.originalLabel ? this._updateLabel() : this.originalLabel && (this.options.label = this.originalLabel),
                            this._enhance(),
                            t && this._addClass(this.label, "ui-checkboxradio-checked", "ui-state-active"),
                            this._on({
                                change: "_toggleClasses",
                                focus: function () {
                                    this._addClass(this.label, null, "ui-state-focus ui-visual-focus");
                                },
                                blur: function () {
                                    this._removeClass(this.label, null, "ui-state-focus ui-visual-focus");
                                },
                            });
                    },
                    _readType: function () {
                        var e = this.element[0].nodeName.toLowerCase();
                        (this.type = this.element[0].type), ("input" === e && /radio|checkbox/.test(this.type)) || t.error("Can't create checkboxradio on element.nodeName=" + e + " and element.type=" + this.type);
                    },
                    _enhance: function () {
                        this._updateIcon(this.element[0].checked);
                    },
                    widget: function () {
                        return this.label;
                    },
                    _getRadioGroup: function () {
                        var e = this.element[0].name,
                            i = "input[name='" + t.escapeSelector(e) + "']";
                        return e
                            ? (this.form.length
                                  ? t(this.form[0].elements).filter(i)
                                  : t(i).filter(function () {
                                        return 0 === t(this)._form().length;
                                    })
                              ).not(this.element)
                            : t([]);
                    },
                    _toggleClasses: function () {
                        var e = this.element[0].checked;
                        this._toggleClass(this.label, "ui-checkboxradio-checked", "ui-state-active", e),
                            this.options.icon && "checkbox" === this.type && this._toggleClass(this.icon, null, "ui-icon-check ui-state-checked", e)._toggleClass(this.icon, null, "ui-icon-blank", !e),
                            "radio" === this.type &&
                                this._getRadioGroup().each(function () {
                                    var e = t(this).checkboxradio("instance");
                                    e && e._removeClass(e.label, "ui-checkboxradio-checked", "ui-state-active");
                                });
                    },
                    _destroy: function () {
                        this._unbindFormResetHandler(), this.icon && (this.icon.remove(), this.iconSpace.remove());
                    },
                    _setOption: function (t, e) {
                        if ("label" !== t || e) {
                            if ((this._super(t, e), "disabled" === t)) return this._toggleClass(this.label, null, "ui-state-disabled", e), void (this.element[0].disabled = e);
                            this.refresh();
                        }
                    },
                    _updateIcon: function (e) {
                        var i = "ui-icon ui-icon-background ";
                        this.options.icon
                            ? (this.icon || ((this.icon = t("<span>")), (this.iconSpace = t("<span> </span>")), this._addClass(this.iconSpace, "ui-checkboxradio-icon-space")),
                              "checkbox" === this.type ? ((i += e ? "ui-icon-check ui-state-checked" : "ui-icon-blank"), this._removeClass(this.icon, null, e ? "ui-icon-blank" : "ui-icon-check")) : (i += "ui-icon-blank"),
                              this._addClass(this.icon, "ui-checkboxradio-icon", i),
                              e || this._removeClass(this.icon, null, "ui-icon-check ui-state-checked"),
                              this.icon.prependTo(this.label).after(this.iconSpace))
                            : void 0 !== this.icon && (this.icon.remove(), this.iconSpace.remove(), delete this.icon);
                    },
                    _updateLabel: function () {
                        var t = this.label.contents().not(this.element[0]);
                        this.icon && (t = t.not(this.icon[0])), (t = this.iconSpace ? t.not(this.iconSpace[0]) : t).remove(), this.label.append(this.options.label);
                    },
                    refresh: function () {
                        var t = this.element[0].checked,
                            e = this.element[0].disabled;
                        this._updateIcon(t),
                            this._toggleClass(this.label, "ui-checkboxradio-checked", "ui-state-active", t),
                            null !== this.options.label && this._updateLabel(),
                            e !== this.options.disabled && this._setOptions({ disabled: e });
                    },
                },
            ]),
            t.ui.checkboxradio,
            t.widget("ui.button", {
                version: "1.13.2",
                defaultElement: "<button>",
                options: { classes: { "ui-button": "ui-corner-all" }, disabled: null, icon: null, iconPosition: "beginning", label: null, showLabel: !0 },
                _getCreateOptions: function () {
                    var t,
                        e = this._super() || {};
                    return (
                        (this.isInput = this.element.is("input")),
                        null != (t = this.element[0].disabled) && (e.disabled = t),
                        (this.originalLabel = this.isInput ? this.element.val() : this.element.html()),
                        this.originalLabel && (e.label = this.originalLabel),
                        e
                    );
                },
                _create: function () {
                    !this.option.showLabel & !this.options.icon && (this.options.showLabel = !0),
                        null == this.options.disabled && (this.options.disabled = this.element[0].disabled || !1),
                        (this.hasTitle = !!this.element.attr("title")),
                        this.options.label && this.options.label !== this.originalLabel && (this.isInput ? this.element.val(this.options.label) : this.element.html(this.options.label)),
                        this._addClass("ui-button", "ui-widget"),
                        this._setOption("disabled", this.options.disabled),
                        this._enhance(),
                        this.element.is("a") &&
                            this._on({
                                keyup: function (e) {
                                    e.keyCode === t.ui.keyCode.SPACE && (e.preventDefault(), this.element[0].click ? this.element[0].click() : this.element.trigger("click"));
                                },
                            });
                },
                _enhance: function () {
                    this.element.is("button") || this.element.attr("role", "button"), this.options.icon && (this._updateIcon("icon", this.options.icon), this._updateTooltip());
                },
                _updateTooltip: function () {
                    (this.title = this.element.attr("title")), this.options.showLabel || this.title || this.element.attr("title", this.options.label);
                },
                _updateIcon: function (e, i) {
                    var n = "iconPosition" !== e,
                        s = n ? this.options.iconPosition : i;
                    e = "top" === s || "bottom" === s;
                    this.icon
                        ? n && this._removeClass(this.icon, null, this.options.icon)
                        : ((this.icon = t("<span>")), this._addClass(this.icon, "ui-button-icon", "ui-icon"), this.options.showLabel || this._addClass("ui-button-icon-only")),
                        n && this._addClass(this.icon, null, i),
                        this._attachIcon(s),
                        e
                            ? (this._addClass(this.icon, null, "ui-widget-icon-block"), this.iconSpace && this.iconSpace.remove())
                            : (this.iconSpace || ((this.iconSpace = t("<span> </span>")), this._addClass(this.iconSpace, "ui-button-icon-space")), this._removeClass(this.icon, null, "ui-wiget-icon-block"), this._attachIconSpace(s));
                },
                _destroy: function () {
                    this.element.removeAttr("role"), this.icon && this.icon.remove(), this.iconSpace && this.iconSpace.remove(), this.hasTitle || this.element.removeAttr("title");
                },
                _attachIconSpace: function (t) {
                    this.icon[/^(?:end|bottom)/.test(t) ? "before" : "after"](this.iconSpace);
                },
                _attachIcon: function (t) {
                    this.element[/^(?:end|bottom)/.test(t) ? "append" : "prepend"](this.icon);
                },
                _setOptions: function (t) {
                    var e = (void 0 === t.showLabel ? this.options : t).showLabel,
                        i = (void 0 === t.icon ? this.options : t).icon;
                    e || i || (t.showLabel = !0), this._super(t);
                },
                _setOption: function (t, e) {
                    "icon" === t && (e ? this._updateIcon(t, e) : this.icon && (this.icon.remove(), this.iconSpace && this.iconSpace.remove())),
                        "iconPosition" === t && this._updateIcon(t, e),
                        "showLabel" === t && (this._toggleClass("ui-button-icon-only", null, !e), this._updateTooltip()),
                        "label" === t && (this.isInput ? this.element.val(e) : (this.element.html(e), this.icon && (this._attachIcon(this.options.iconPosition), this._attachIconSpace(this.options.iconPosition)))),
                        this._super(t, e),
                        "disabled" === t && (this._toggleClass(null, "ui-state-disabled", e), (this.element[0].disabled = e) && this.element.trigger("blur"));
                },
                refresh: function () {
                    var t = this.element.is("input, button") ? this.element[0].disabled : this.element.hasClass("ui-button-disabled");
                    t !== this.options.disabled && this._setOptions({ disabled: t }), this._updateTooltip();
                },
            }),
            !1 !== t.uiBackCompat &&
                (t.widget("ui.button", t.ui.button, {
                    options: { text: !0, icons: { primary: null, secondary: null } },
                    _create: function () {
                        this.options.showLabel && !this.options.text && (this.options.showLabel = this.options.text),
                            !this.options.showLabel && this.options.text && (this.options.text = this.options.showLabel),
                            this.options.icon || (!this.options.icons.primary && !this.options.icons.secondary)
                                ? this.options.icon && (this.options.icons.primary = this.options.icon)
                                : this.options.icons.primary
                                ? (this.options.icon = this.options.icons.primary)
                                : ((this.options.icon = this.options.icons.secondary), (this.options.iconPosition = "end")),
                            this._super();
                    },
                    _setOption: function (t, e) {
                        "text" !== t
                            ? ("showLabel" === t && (this.options.text = e),
                              "icon" === t && (this.options.icons.primary = e),
                              "icons" === t && (e.primary ? (this._super("icon", e.primary), this._super("iconPosition", "beginning")) : e.secondary && (this._super("icon", e.secondary), this._super("iconPosition", "end"))),
                              this._superApply(arguments))
                            : this._super("showLabel", e);
                    },
                }),
                (t.fn.button =
                    ((tt = t.fn.button),
                    function (e) {
                        var i = "string" == typeof e,
                            n = Array.prototype.slice.call(arguments, 1),
                            s = this;
                        return (
                            i
                                ? this.length || "instance" !== e
                                    ? this.each(function () {
                                          var i = t(this).attr("type"),
                                              o = t.data(this, "ui-" + ("checkbox" !== i && "radio" !== i ? "button" : "checkboxradio"));
                                          return "instance" === e
                                              ? ((s = o), !1)
                                              : o
                                              ? "function" != typeof o[e] || "_" === e.charAt(0)
                                                  ? t.error("no such method '" + e + "' for button widget instance")
                                                  : (i = o[e].apply(o, n)) !== o && void 0 !== i
                                                  ? ((s = i && i.jquery ? s.pushStack(i.get()) : i), !1)
                                                  : void 0
                                              : t.error("cannot call methods on button prior to initialization; attempted to call method '" + e + "'");
                                      })
                                    : (s = void 0)
                                : (n.length && (e = t.widget.extend.apply(null, [e].concat(n))),
                                  this.each(function () {
                                      var i,
                                          n = "checkbox" !== (i = t(this).attr("type")) && "radio" !== i ? "button" : "checkboxradio";
                                      (i = t.data(this, "ui-" + n)) ? (i.option(e || {}), i._init && i._init()) : "button" != n ? t(this).checkboxradio(t.extend({ icon: !1 }, e)) : tt.call(t(this), e);
                                  })),
                            s
                        );
                    })),
                (t.fn.buttonset = function () {
                    return (
                        t.ui.controlgroup || t.error("Controlgroup widget missing"),
                        "option" === arguments[0] && "items" === arguments[1] && arguments[2]
                            ? this.controlgroup.apply(this, [arguments[0], "items.button", arguments[2]])
                            : "option" === arguments[0] && "items" === arguments[1]
                            ? this.controlgroup.apply(this, [arguments[0], "items.button"])
                            : ("object" == typeof arguments[0] && arguments[0].items && (arguments[0].items = { button: arguments[0].items }), this.controlgroup.apply(this, arguments))
                    );
                })),
            t.ui.button,
            t.extend(t.ui, { datepicker: { version: "1.13.2" } }),
            t.extend(nt.prototype, {
                markerClassName: "hasDatepicker",
                maxRows: 4,
                _widgetDatepicker: function () {
                    return this.dpDiv;
                },
                setDefaults: function (t) {
                    return rt(this._defaults, t || {}), this;
                },
                _attachDatepicker: function (e, i) {
                    var n,
                        s = e.nodeName.toLowerCase(),
                        o = "div" === s || "span" === s;
                    e.id || ((this.uuid += 1), (e.id = "dp" + this.uuid)), ((n = this._newInst(t(e), o)).settings = t.extend({}, i || {})), "input" === s ? this._connectDatepicker(e, n) : o && this._inlineDatepicker(e, n);
                },
                _newInst: function (e, i) {
                    return {
                        id: e[0].id.replace(/([^A-Za-z0-9_\-])/g, "\\\\$1"),
                        input: e,
                        selectedDay: 0,
                        selectedMonth: 0,
                        selectedYear: 0,
                        drawMonth: 0,
                        drawYear: 0,
                        inline: i,
                        dpDiv: i ? st(t("<div class='" + this._inlineClass + " ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all'></div>")) : this.dpDiv,
                    };
                },
                _connectDatepicker: function (e, i) {
                    var n = t(e);
                    (i.append = t([])),
                        (i.trigger = t([])),
                        n.hasClass(this.markerClassName) ||
                            (this._attachments(n, i),
                            n.addClass(this.markerClassName).on("keydown", this._doKeyDown).on("keypress", this._doKeyPress).on("keyup", this._doKeyUp),
                            this._autoSize(i),
                            t.data(e, "datepicker", i),
                            i.settings.disabled && this._disableDatepicker(e));
                },
                _attachments: function (e, i) {
                    var n,
                        s = this._get(i, "appendText"),
                        o = this._get(i, "isRTL");
                    i.append && i.append.remove(),
                        s && ((i.append = t("<span>").addClass(this._appendClass).text(s)), e[o ? "before" : "after"](i.append)),
                        e.off("focus", this._showDatepicker),
                        i.trigger && i.trigger.remove(),
                        ("focus" !== (n = this._get(i, "showOn")) && "both" !== n) || e.on("focus", this._showDatepicker),
                        ("button" !== n && "both" !== n) ||
                            ((s = this._get(i, "buttonText")),
                            (n = this._get(i, "buttonImage")),
                            this._get(i, "buttonImageOnly")
                                ? (i.trigger = t("<img>").addClass(this._triggerClass).attr({ src: n, alt: s, title: s }))
                                : ((i.trigger = t("<button type='button'>").addClass(this._triggerClass)), n ? i.trigger.html(t("<img>").attr({ src: n, alt: s, title: s })) : i.trigger.text(s)),
                            e[o ? "before" : "after"](i.trigger),
                            i.trigger.on("click", function () {
                                return (
                                    t.datepicker._datepickerShowing && t.datepicker._lastInput === e[0]
                                        ? t.datepicker._hideDatepicker()
                                        : (t.datepicker._datepickerShowing && t.datepicker._lastInput !== e[0] && t.datepicker._hideDatepicker(), t.datepicker._showDatepicker(e[0])),
                                    !1
                                );
                            }));
                },
                _autoSize: function (t) {
                    var e, i, n, s, o, r;
                    this._get(t, "autoSize") &&
                        !t.inline &&
                        ((o = new Date(2009, 11, 20)),
                        (r = this._get(t, "dateFormat")).match(/[DM]/) &&
                            ((e = function (t) {
                                for (s = n = i = 0; s < t.length; s++) t[s].length > i && ((i = t[s].length), (n = s));
                                return n;
                            }),
                            o.setMonth(e(this._get(t, r.match(/MM/) ? "monthNames" : "monthNamesShort"))),
                            o.setDate(e(this._get(t, r.match(/DD/) ? "dayNames" : "dayNamesShort")) + 20 - o.getDay())),
                        t.input.attr("size", this._formatDate(t, o).length));
                },
                _inlineDatepicker: function (e, i) {
                    var n = t(e);
                    n.hasClass(this.markerClassName) ||
                        (n.addClass(this.markerClassName).append(i.dpDiv),
                        t.data(e, "datepicker", i),
                        this._setDate(i, this._getDefaultDate(i), !0),
                        this._updateDatepicker(i),
                        this._updateAlternate(i),
                        i.settings.disabled && this._disableDatepicker(e),
                        i.dpDiv.css("display", "block"));
                },
                _dialogDatepicker: function (e, i, n, s, o) {
                    var r,
                        a = this._dialogInst;
                    return (
                        a ||
                            ((this.uuid += 1),
                            (r = "dp" + this.uuid),
                            (this._dialogInput = t("<input type='text' id='" + r + "' style='position: absolute; top: -100px; width: 0px;'/>")),
                            this._dialogInput.on("keydown", this._doKeyDown),
                            t("body").append(this._dialogInput),
                            ((a = this._dialogInst = this._newInst(this._dialogInput, !1)).settings = {}),
                            t.data(this._dialogInput[0], "datepicker", a)),
                        rt(a.settings, s || {}),
                        (i = i && i.constructor === Date ? this._formatDate(a, i) : i),
                        this._dialogInput.val(i),
                        (this._pos = o ? (o.length ? o : [o.pageX, o.pageY]) : null),
                        this._pos ||
                            ((r = document.documentElement.clientWidth),
                            (s = document.documentElement.clientHeight),
                            (i = document.documentElement.scrollLeft || document.body.scrollLeft),
                            (o = document.documentElement.scrollTop || document.body.scrollTop),
                            (this._pos = [r / 2 - 100 + i, s / 2 - 150 + o])),
                        this._dialogInput.css("left", this._pos[0] + 20 + "px").css("top", this._pos[1] + "px"),
                        (a.settings.onSelect = n),
                        (this._inDialog = !0),
                        this.dpDiv.addClass(this._dialogClass),
                        this._showDatepicker(this._dialogInput[0]),
                        t.blockUI && t.blockUI(this.dpDiv),
                        t.data(this._dialogInput[0], "datepicker", a),
                        this
                    );
                },
                _destroyDatepicker: function (e) {
                    var i,
                        n = t(e),
                        s = t.data(e, "datepicker");
                    n.hasClass(this.markerClassName) &&
                        ((i = e.nodeName.toLowerCase()),
                        t.removeData(e, "datepicker"),
                        "input" === i
                            ? (s.append.remove(), s.trigger.remove(), n.removeClass(this.markerClassName).off("focus", this._showDatepicker).off("keydown", this._doKeyDown).off("keypress", this._doKeyPress).off("keyup", this._doKeyUp))
                            : ("div" !== i && "span" !== i) || n.removeClass(this.markerClassName).empty(),
                        et === s && ((et = null), (this._curInst = null)));
                },
                _enableDatepicker: function (e) {
                    var i,
                        n = t(e),
                        s = t.data(e, "datepicker");
                    n.hasClass(this.markerClassName) &&
                        ("input" === (i = e.nodeName.toLowerCase())
                            ? ((e.disabled = !1),
                              s.trigger
                                  .filter("button")
                                  .each(function () {
                                      this.disabled = !1;
                                  })
                                  .end()
                                  .filter("img")
                                  .css({ opacity: "1.0", cursor: "" }))
                            : ("div" !== i && "span" !== i) || ((n = n.children("." + this._inlineClass)).children().removeClass("ui-state-disabled"), n.find("select.ui-datepicker-month, select.ui-datepicker-year").prop("disabled", !1)),
                        (this._disabledInputs = t.map(this._disabledInputs, function (t) {
                            return t === e ? null : t;
                        })));
                },
                _disableDatepicker: function (e) {
                    var i,
                        n = t(e),
                        s = t.data(e, "datepicker");
                    n.hasClass(this.markerClassName) &&
                        ("input" === (i = e.nodeName.toLowerCase())
                            ? ((e.disabled = !0),
                              s.trigger
                                  .filter("button")
                                  .each(function () {
                                      this.disabled = !0;
                                  })
                                  .end()
                                  .filter("img")
                                  .css({ opacity: "0.5", cursor: "default" }))
                            : ("div" !== i && "span" !== i) || ((n = n.children("." + this._inlineClass)).children().addClass("ui-state-disabled"), n.find("select.ui-datepicker-month, select.ui-datepicker-year").prop("disabled", !0)),
                        (this._disabledInputs = t.map(this._disabledInputs, function (t) {
                            return t === e ? null : t;
                        })),
                        (this._disabledInputs[this._disabledInputs.length] = e));
                },
                _isDisabledDatepicker: function (t) {
                    if (!t) return !1;
                    for (var e = 0; e < this._disabledInputs.length; e++) if (this._disabledInputs[e] === t) return !0;
                    return !1;
                },
                _getInst: function (e) {
                    try {
                        return t.data(e, "datepicker");
                    } catch (e) {
                        throw "Missing instance data for this datepicker";
                    }
                },
                _optionDatepicker: function (e, i, n) {
                    var s,
                        o,
                        r = this._getInst(e);
                    if (2 === arguments.length && "string" == typeof i) return "defaults" === i ? t.extend({}, t.datepicker._defaults) : r ? ("all" === i ? t.extend({}, r.settings) : this._get(r, i)) : null;
                    (s = i || {}),
                        "string" == typeof i && ((s = {})[i] = n),
                        r &&
                            (this._curInst === r && this._hideDatepicker(),
                            (o = this._getDateDatepicker(e, !0)),
                            (i = this._getMinMaxDate(r, "min")),
                            (n = this._getMinMaxDate(r, "max")),
                            rt(r.settings, s),
                            null !== i && void 0 !== s.dateFormat && void 0 === s.minDate && (r.settings.minDate = this._formatDate(r, i)),
                            null !== n && void 0 !== s.dateFormat && void 0 === s.maxDate && (r.settings.maxDate = this._formatDate(r, n)),
                            "disabled" in s && (s.disabled ? this._disableDatepicker(e) : this._enableDatepicker(e)),
                            this._attachments(t(e), r),
                            this._autoSize(r),
                            this._setDate(r, o),
                            this._updateAlternate(r),
                            this._updateDatepicker(r));
                },
                _changeDatepicker: function (t, e, i) {
                    this._optionDatepicker(t, e, i);
                },
                _refreshDatepicker: function (t) {
                    (t = this._getInst(t)) && this._updateDatepicker(t);
                },
                _setDateDatepicker: function (t, e) {
                    (t = this._getInst(t)) && (this._setDate(t, e), this._updateDatepicker(t), this._updateAlternate(t));
                },
                _getDateDatepicker: function (t, e) {
                    return (t = this._getInst(t)) && !t.inline && this._setDateFromField(t, e), t ? this._getDate(t) : null;
                },
                _doKeyDown: function (e) {
                    var i,
                        n,
                        s = t.datepicker._getInst(e.target),
                        o = !0,
                        r = s.dpDiv.is(".ui-datepicker-rtl");
                    if (((s._keyEvent = !0), t.datepicker._datepickerShowing))
                        switch (e.keyCode) {
                            case 9:
                                t.datepicker._hideDatepicker(), (o = !1);
                                break;
                            case 13:
                                return (
                                    (n = t("td." + t.datepicker._dayOverClass + ":not(." + t.datepicker._currentClass + ")", s.dpDiv))[0] && t.datepicker._selectDay(e.target, s.selectedMonth, s.selectedYear, n[0]),
                                    (i = t.datepicker._get(s, "onSelect")) ? ((n = t.datepicker._formatDate(s)), i.apply(s.input ? s.input[0] : null, [n, s])) : t.datepicker._hideDatepicker(),
                                    !1
                                );
                            case 27:
                                t.datepicker._hideDatepicker();
                                break;
                            case 33:
                                t.datepicker._adjustDate(e.target, e.ctrlKey ? -t.datepicker._get(s, "stepBigMonths") : -t.datepicker._get(s, "stepMonths"), "M");
                                break;
                            case 34:
                                t.datepicker._adjustDate(e.target, e.ctrlKey ? +t.datepicker._get(s, "stepBigMonths") : +t.datepicker._get(s, "stepMonths"), "M");
                                break;
                            case 35:
                                (e.ctrlKey || e.metaKey) && t.datepicker._clearDate(e.target), (o = e.ctrlKey || e.metaKey);
                                break;
                            case 36:
                                (e.ctrlKey || e.metaKey) && t.datepicker._gotoToday(e.target), (o = e.ctrlKey || e.metaKey);
                                break;
                            case 37:
                                (e.ctrlKey || e.metaKey) && t.datepicker._adjustDate(e.target, r ? 1 : -1, "D"),
                                    (o = e.ctrlKey || e.metaKey),
                                    e.originalEvent.altKey && t.datepicker._adjustDate(e.target, e.ctrlKey ? -t.datepicker._get(s, "stepBigMonths") : -t.datepicker._get(s, "stepMonths"), "M");
                                break;
                            case 38:
                                (e.ctrlKey || e.metaKey) && t.datepicker._adjustDate(e.target, -7, "D"), (o = e.ctrlKey || e.metaKey);
                                break;
                            case 39:
                                (e.ctrlKey || e.metaKey) && t.datepicker._adjustDate(e.target, r ? -1 : 1, "D"),
                                    (o = e.ctrlKey || e.metaKey),
                                    e.originalEvent.altKey && t.datepicker._adjustDate(e.target, e.ctrlKey ? +t.datepicker._get(s, "stepBigMonths") : +t.datepicker._get(s, "stepMonths"), "M");
                                break;
                            case 40:
                                (e.ctrlKey || e.metaKey) && t.datepicker._adjustDate(e.target, 7, "D"), (o = e.ctrlKey || e.metaKey);
                                break;
                            default:
                                o = !1;
                        }
                    else 36 === e.keyCode && e.ctrlKey ? t.datepicker._showDatepicker(this) : (o = !1);
                    o && (e.preventDefault(), e.stopPropagation());
                },
                _doKeyPress: function (e) {
                    var i,
                        n = t.datepicker._getInst(e.target);
                    if (t.datepicker._get(n, "constrainInput"))
                        return (i = t.datepicker._possibleChars(t.datepicker._get(n, "dateFormat"))), (n = String.fromCharCode(null == e.charCode ? e.keyCode : e.charCode)), e.ctrlKey || e.metaKey || n < " " || !i || -1 < i.indexOf(n);
                },
                _doKeyUp: function (e) {
                    if ((e = t.datepicker._getInst(e.target)).input.val() !== e.lastVal)
                        try {
                            t.datepicker.parseDate(t.datepicker._get(e, "dateFormat"), e.input ? e.input.val() : null, t.datepicker._getFormatConfig(e)) &&
                                (t.datepicker._setDateFromField(e), t.datepicker._updateAlternate(e), t.datepicker._updateDatepicker(e));
                        } catch (e) {}
                    return !0;
                },
                _showDatepicker: function (e) {
                    var i, n, s, o;
                    "input" !== (e = e.target || e).nodeName.toLowerCase() && (e = t("input", e.parentNode)[0]),
                        t.datepicker._isDisabledDatepicker(e) ||
                            t.datepicker._lastInput === e ||
                            ((o = t.datepicker._getInst(e)),
                            t.datepicker._curInst && t.datepicker._curInst !== o && (t.datepicker._curInst.dpDiv.stop(!0, !0), o && t.datepicker._datepickerShowing && t.datepicker._hideDatepicker(t.datepicker._curInst.input[0])),
                            !1 !== (n = (s = t.datepicker._get(o, "beforeShow")) ? s.apply(e, [e, o]) : {}) &&
                                (rt(o.settings, n),
                                (o.lastVal = null),
                                (t.datepicker._lastInput = e),
                                t.datepicker._setDateFromField(o),
                                t.datepicker._inDialog && (e.value = ""),
                                t.datepicker._pos || ((t.datepicker._pos = t.datepicker._findPos(e)), (t.datepicker._pos[1] += e.offsetHeight)),
                                (i = !1),
                                t(e)
                                    .parents()
                                    .each(function () {
                                        return !(i |= "fixed" === t(this).css("position"));
                                    }),
                                (s = { left: t.datepicker._pos[0], top: t.datepicker._pos[1] }),
                                (t.datepicker._pos = null),
                                o.dpDiv.empty(),
                                o.dpDiv.css({ position: "absolute", display: "block", top: "-1000px" }),
                                t.datepicker._updateDatepicker(o),
                                (s = t.datepicker._checkOffset(o, s, i)),
                                o.dpDiv.css({ position: t.datepicker._inDialog && t.blockUI ? "static" : i ? "fixed" : "absolute", display: "none", left: s.left + "px", top: s.top + "px" }),
                                o.inline ||
                                    ((n = t.datepicker._get(o, "showAnim")),
                                    (s = t.datepicker._get(o, "duration")),
                                    o.dpDiv.css(
                                        "z-index",
                                        (function (t) {
                                            for (var e, i; t.length && t[0] !== document; ) {
                                                if (("absolute" === (e = t.css("position")) || "relative" === e || "fixed" === e) && ((i = parseInt(t.css("zIndex"), 10)), !isNaN(i) && 0 !== i)) return i;
                                                t = t.parent();
                                            }
                                            return 0;
                                        })(t(e)) + 1
                                    ),
                                    (t.datepicker._datepickerShowing = !0),
                                    t.effects && t.effects.effect[n] ? o.dpDiv.show(n, t.datepicker._get(o, "showOptions"), s) : o.dpDiv[n || "show"](n ? s : null),
                                    t.datepicker._shouldFocusInput(o) && o.input.trigger("focus"),
                                    (t.datepicker._curInst = o))));
                },
                _updateDatepicker: function (e) {
                    (this.maxRows = 4), (et = e).dpDiv.empty().append(this._generateHTML(e)), this._attachHandlers(e);
                    var i,
                        n = this._getNumberOfMonths(e),
                        s = n[1],
                        o = e.dpDiv.find("." + this._dayOverClass + " a"),
                        r = t.datepicker._get(e, "onUpdateDatepicker");
                    0 < o.length && ot.apply(o.get(0)),
                        e.dpDiv.removeClass("ui-datepicker-multi-2 ui-datepicker-multi-3 ui-datepicker-multi-4").width(""),
                        1 < s && e.dpDiv.addClass("ui-datepicker-multi-" + s).css("width", 17 * s + "em"),
                        e.dpDiv[(1 !== n[0] || 1 !== n[1] ? "add" : "remove") + "Class"]("ui-datepicker-multi"),
                        e.dpDiv[(this._get(e, "isRTL") ? "add" : "remove") + "Class"]("ui-datepicker-rtl"),
                        e === t.datepicker._curInst && t.datepicker._datepickerShowing && t.datepicker._shouldFocusInput(e) && e.input.trigger("focus"),
                        e.yearshtml &&
                            ((i = e.yearshtml),
                            setTimeout(function () {
                                i === e.yearshtml && e.yearshtml && e.dpDiv.find("select.ui-datepicker-year").first().replaceWith(e.yearshtml), (i = e.yearshtml = null);
                            }, 0)),
                        r && r.apply(e.input ? e.input[0] : null, [e]);
                },
                _shouldFocusInput: function (t) {
                    return t.input && t.input.is(":visible") && !t.input.is(":disabled") && !t.input.is(":focus");
                },
                _checkOffset: function (e, i, n) {
                    var s = e.dpDiv.outerWidth(),
                        o = e.dpDiv.outerHeight(),
                        r = e.input ? e.input.outerWidth() : 0,
                        a = e.input ? e.input.outerHeight() : 0,
                        l = document.documentElement.clientWidth + (n ? 0 : t(document).scrollLeft()),
                        h = document.documentElement.clientHeight + (n ? 0 : t(document).scrollTop());
                    return (
                        (i.left -= this._get(e, "isRTL") ? s - r : 0),
                        (i.left -= n && i.left === e.input.offset().left ? t(document).scrollLeft() : 0),
                        (i.top -= n && i.top === e.input.offset().top + a ? t(document).scrollTop() : 0),
                        (i.left -= Math.min(i.left, i.left + s > l && s < l ? Math.abs(i.left + s - l) : 0)),
                        (i.top -= Math.min(i.top, i.top + o > h && o < h ? Math.abs(o + a) : 0)),
                        i
                    );
                },
                _findPos: function (e) {
                    for (var i = this._getInst(e), n = this._get(i, "isRTL"); e && ("hidden" === e.type || 1 !== e.nodeType || t.expr.pseudos.hidden(e)); ) e = e[n ? "previousSibling" : "nextSibling"];
                    return [(i = t(e).offset()).left, i.top];
                },
                _hideDatepicker: function (e) {
                    var i,
                        n,
                        s = this._curInst;
                    !s ||
                        (e && s !== t.data(e, "datepicker")) ||
                        (this._datepickerShowing &&
                            ((i = this._get(s, "showAnim")),
                            (n = this._get(s, "duration")),
                            (e = function () {
                                t.datepicker._tidyDialog(s);
                            }),
                            t.effects && (t.effects.effect[i] || t.effects[i]) ? s.dpDiv.hide(i, t.datepicker._get(s, "showOptions"), n, e) : s.dpDiv["slideDown" === i ? "slideUp" : "fadeIn" === i ? "fadeOut" : "hide"](i ? n : null, e),
                            i || e(),
                            (this._datepickerShowing = !1),
                            (e = this._get(s, "onClose")) && e.apply(s.input ? s.input[0] : null, [s.input ? s.input.val() : "", s]),
                            (this._lastInput = null),
                            this._inDialog && (this._dialogInput.css({ position: "absolute", left: "0", top: "-100px" }), t.blockUI && (t.unblockUI(), t("body").append(this.dpDiv))),
                            (this._inDialog = !1)));
                },
                _tidyDialog: function (t) {
                    t.dpDiv.removeClass(this._dialogClass).off(".ui-datepicker-calendar");
                },
                _checkExternalClick: function (e) {
                    var i;
                    t.datepicker._curInst &&
                        ((i = t(e.target)),
                        (e = t.datepicker._getInst(i[0])),
                        ((i[0].id === t.datepicker._mainDivId ||
                            0 !== i.parents("#" + t.datepicker._mainDivId).length ||
                            i.hasClass(t.datepicker.markerClassName) ||
                            i.closest("." + t.datepicker._triggerClass).length ||
                            !t.datepicker._datepickerShowing ||
                            (t.datepicker._inDialog && t.blockUI)) &&
                            (!i.hasClass(t.datepicker.markerClassName) || t.datepicker._curInst === e)) ||
                            t.datepicker._hideDatepicker());
                },
                _adjustDate: function (e, i, n) {
                    var s = t(e);
                    e = this._getInst(s[0]);
                    this._isDisabledDatepicker(s[0]) || (this._adjustInstDate(e, i, n), this._updateDatepicker(e));
                },
                _gotoToday: function (e) {
                    var i = t(e),
                        n = this._getInst(i[0]);
                    this._get(n, "gotoCurrent") && n.currentDay
                        ? ((n.selectedDay = n.currentDay), (n.drawMonth = n.selectedMonth = n.currentMonth), (n.drawYear = n.selectedYear = n.currentYear))
                        : ((e = new Date()), (n.selectedDay = e.getDate()), (n.drawMonth = n.selectedMonth = e.getMonth()), (n.drawYear = n.selectedYear = e.getFullYear())),
                        this._notifyChange(n),
                        this._adjustDate(i);
                },
                _selectMonthYear: function (e, i, n) {
                    var s = t(e);
                    ((e = this._getInst(s[0]))["selected" + ("M" === n ? "Month" : "Year")] = e["draw" + ("M" === n ? "Month" : "Year")] = parseInt(i.options[i.selectedIndex].value, 10)), this._notifyChange(e), this._adjustDate(s);
                },
                _selectDay: function (e, i, n, s) {
                    var o = t(e);
                    t(s).hasClass(this._unselectableClass) ||
                        this._isDisabledDatepicker(o[0]) ||
                        (((o = this._getInst(o[0])).selectedDay = o.currentDay = parseInt(t("a", s).attr("data-date"))),
                        (o.selectedMonth = o.currentMonth = i),
                        (o.selectedYear = o.currentYear = n),
                        this._selectDate(e, this._formatDate(o, o.currentDay, o.currentMonth, o.currentYear)));
                },
                _clearDate: function (e) {
                    (e = t(e)), this._selectDate(e, "");
                },
                _selectDate: function (e, i) {
                    var n = t(e);
                    e = this._getInst(n[0]);
                    (i = null != i ? i : this._formatDate(e)),
                        e.input && e.input.val(i),
                        this._updateAlternate(e),
                        (n = this._get(e, "onSelect")) ? n.apply(e.input ? e.input[0] : null, [i, e]) : e.input && e.input.trigger("change"),
                        e.inline ? this._updateDatepicker(e) : (this._hideDatepicker(), (this._lastInput = e.input[0]), "object" != typeof e.input[0] && e.input.trigger("focus"), (this._lastInput = null));
                },
                _updateAlternate: function (e) {
                    var i,
                        n,
                        s = this._get(e, "altField");
                    s && ((i = this._get(e, "altFormat") || this._get(e, "dateFormat")), (n = this._getDate(e)), (e = this.formatDate(i, n, this._getFormatConfig(e))), t(document).find(s).val(e));
                },
                noWeekends: function (t) {
                    return [0 < (t = t.getDay()) && t < 6, ""];
                },
                iso8601Week: function (t) {
                    var e = new Date(t.getTime());
                    return e.setDate(e.getDate() + 4 - (e.getDay() || 7)), (t = e.getTime()), e.setMonth(0), e.setDate(1), Math.floor(Math.round((t - e) / 864e5) / 7) + 1;
                },
                parseDate: function (e, i, n) {
                    if (null == e || null == i) throw "Invalid arguments";
                    if ("" === (i = "object" == typeof i ? i.toString() : i + "")) return null;
                    for (
                        var s,
                            o,
                            r,
                            a = 0,
                            l = "string" != typeof (l = (n ? n.shortYearCutoff : null) || this._defaults.shortYearCutoff) ? l : (new Date().getFullYear() % 100) + parseInt(l, 10),
                            h = (n ? n.dayNamesShort : null) || this._defaults.dayNamesShort,
                            c = (n ? n.dayNames : null) || this._defaults.dayNames,
                            u = (n ? n.monthNamesShort : null) || this._defaults.monthNamesShort,
                            d = (n ? n.monthNames : null) || this._defaults.monthNames,
                            p = -1,
                            f = -1,
                            g = -1,
                            m = -1,
                            v = !1,
                            _ = function (t) {
                                return (t = x + 1 < e.length && e.charAt(x + 1) === t) && x++, t;
                            },
                            b = function (t) {
                                var e = _(t);
                                (e = "@" === t ? 14 : "!" === t ? 20 : "y" === t && e ? 4 : "o" === t ? 3 : 2), (e = new RegExp("^\\d{" + ("y" === t ? e : 1) + "," + e + "}"));
                                if (!(e = i.substring(a).match(e))) throw "Missing number at position " + a;
                                return (a += e[0].length), parseInt(e[0], 10);
                            },
                            y = function (e, n, s) {
                                var o = -1;
                                n = t
                                    .map(_(e) ? s : n, function (t, e) {
                                        return [[e, t]];
                                    })
                                    .sort(function (t, e) {
                                        return -(t[1].length - e[1].length);
                                    });
                                if (
                                    (t.each(n, function (t, e) {
                                        var n = e[1];
                                        if (i.substr(a, n.length).toLowerCase() === n.toLowerCase()) return (o = e[0]), (a += n.length), !1;
                                    }),
                                    -1 !== o)
                                )
                                    return o + 1;
                                throw "Unknown name at position " + a;
                            },
                            w = function () {
                                if (i.charAt(a) !== e.charAt(x)) throw "Unexpected literal at position " + a;
                                a++;
                            },
                            x = 0;
                        x < e.length;
                        x++
                    )
                        if (v) "'" !== e.charAt(x) || _("'") ? w() : (v = !1);
                        else
                            switch (e.charAt(x)) {
                                case "d":
                                    g = b("d");
                                    break;
                                case "D":
                                    y("D", h, c);
                                    break;
                                case "o":
                                    m = b("o");
                                    break;
                                case "m":
                                    f = b("m");
                                    break;
                                case "M":
                                    f = y("M", u, d);
                                    break;
                                case "y":
                                    p = b("y");
                                    break;
                                case "@":
                                    (p = (r = new Date(b("@"))).getFullYear()), (f = r.getMonth() + 1), (g = r.getDate());
                                    break;
                                case "!":
                                    (p = (r = new Date((b("!") - this._ticksTo1970) / 1e4)).getFullYear()), (f = r.getMonth() + 1), (g = r.getDate());
                                    break;
                                case "'":
                                    _("'") ? w() : (v = !0);
                                    break;
                                default:
                                    w();
                            }
                    if (a < i.length && ((o = i.substr(a)), !/^\s+/.test(o))) throw "Extra/unparsed characters found in date: " + o;
                    if ((-1 === p ? (p = new Date().getFullYear()) : p < 100 && (p += new Date().getFullYear() - (new Date().getFullYear() % 100) + (p <= l ? 0 : -100)), -1 < m))
                        for (f = 1, g = m; !(g <= (s = this._getDaysInMonth(p, f - 1))); ) f++, (g -= s);
                    if ((r = this._daylightSavingAdjust(new Date(p, f - 1, g))).getFullYear() !== p || r.getMonth() + 1 !== f || r.getDate() !== g) throw "Invalid date";
                    return r;
                },
                ATOM: "yy-mm-dd",
                COOKIE: "D, dd M yy",
                ISO_8601: "yy-mm-dd",
                RFC_822: "D, d M y",
                RFC_850: "DD, dd-M-y",
                RFC_1036: "D, d M y",
                RFC_1123: "D, d M yy",
                RFC_2822: "D, d M yy",
                RSS: "D, d M y",
                TICKS: "!",
                TIMESTAMP: "@",
                W3C: "yy-mm-dd",
                _ticksTo1970: 24 * (718685 + Math.floor(492.5) - Math.floor(19.7) + Math.floor(4.925)) * 60 * 60 * 1e7,
                formatDate: function (t, e, i) {
                    if (!e) return "";
                    function n(t, e, i) {
                        var n = "" + e;
                        if (c(t)) for (; n.length < i; ) n = "0" + n;
                        return n;
                    }
                    function s(t, e, i, n) {
                        return (c(t) ? n : i)[e];
                    }
                    var o,
                        r = (i ? i.dayNamesShort : null) || this._defaults.dayNamesShort,
                        a = (i ? i.dayNames : null) || this._defaults.dayNames,
                        l = (i ? i.monthNamesShort : null) || this._defaults.monthNamesShort,
                        h = (i ? i.monthNames : null) || this._defaults.monthNames,
                        c = function (e) {
                            return (e = o + 1 < t.length && t.charAt(o + 1) === e) && o++, e;
                        },
                        u = "",
                        d = !1;
                    if (e)
                        for (o = 0; o < t.length; o++)
                            if (d) "'" !== t.charAt(o) || c("'") ? (u += t.charAt(o)) : (d = !1);
                            else
                                switch (t.charAt(o)) {
                                    case "d":
                                        u += n("d", e.getDate(), 2);
                                        break;
                                    case "D":
                                        u += s("D", e.getDay(), r, a);
                                        break;
                                    case "o":
                                        u += n("o", Math.round((new Date(e.getFullYear(), e.getMonth(), e.getDate()).getTime() - new Date(e.getFullYear(), 0, 0).getTime()) / 864e5), 3);
                                        break;
                                    case "m":
                                        u += n("m", e.getMonth() + 1, 2);
                                        break;
                                    case "M":
                                        u += s("M", e.getMonth(), l, h);
                                        break;
                                    case "y":
                                        u += c("y") ? e.getFullYear() : (e.getFullYear() % 100 < 10 ? "0" : "") + (e.getFullYear() % 100);
                                        break;
                                    case "@":
                                        u += e.getTime();
                                        break;
                                    case "!":
                                        u += 1e4 * e.getTime() + this._ticksTo1970;
                                        break;
                                    case "'":
                                        c("'") ? (u += "'") : (d = !0);
                                        break;
                                    default:
                                        u += t.charAt(o);
                                }
                    return u;
                },
                _possibleChars: function (t) {
                    for (
                        var e = "",
                            i = !1,
                            n = function (e) {
                                return (e = s + 1 < t.length && t.charAt(s + 1) === e) && s++, e;
                            },
                            s = 0;
                        s < t.length;
                        s++
                    )
                        if (i) "'" !== t.charAt(s) || n("'") ? (e += t.charAt(s)) : (i = !1);
                        else
                            switch (t.charAt(s)) {
                                case "d":
                                case "m":
                                case "y":
                                case "@":
                                    e += "0123456789";
                                    break;
                                case "D":
                                case "M":
                                    return null;
                                case "'":
                                    n("'") ? (e += "'") : (i = !0);
                                    break;
                                default:
                                    e += t.charAt(s);
                            }
                    return e;
                },
                _get: function (t, e) {
                    return (void 0 !== t.settings[e] ? t.settings : this._defaults)[e];
                },
                _setDateFromField: function (t, e) {
                    if (t.input.val() !== t.lastVal) {
                        var i = this._get(t, "dateFormat"),
                            n = (t.lastVal = t.input ? t.input.val() : null),
                            s = this._getDefaultDate(t),
                            o = s,
                            r = this._getFormatConfig(t);
                        try {
                            o = this.parseDate(i, n, r) || s;
                        } catch (t) {
                            n = e ? "" : n;
                        }
                        (t.selectedDay = o.getDate()),
                            (t.drawMonth = t.selectedMonth = o.getMonth()),
                            (t.drawYear = t.selectedYear = o.getFullYear()),
                            (t.currentDay = n ? o.getDate() : 0),
                            (t.currentMonth = n ? o.getMonth() : 0),
                            (t.currentYear = n ? o.getFullYear() : 0),
                            this._adjustInstDate(t);
                    }
                },
                _getDefaultDate: function (t) {
                    return this._restrictMinMax(t, this._determineDate(t, this._get(t, "defaultDate"), new Date()));
                },
                _determineDate: function (e, i, n) {
                    var s, o;
                    i =
                        null == i || "" === i
                            ? n
                            : "string" == typeof i
                            ? (function (i) {
                                  try {
                                      return t.datepicker.parseDate(t.datepicker._get(e, "dateFormat"), i, t.datepicker._getFormatConfig(e));
                                  } catch (i) {}
                                  for (
                                      var n = (i.toLowerCase().match(/^c/) ? t.datepicker._getDate(e) : null) || new Date(), s = n.getFullYear(), o = n.getMonth(), r = n.getDate(), a = /([+\-]?[0-9]+)\s*(d|D|w|W|m|M|y|Y)?/g, l = a.exec(i);
                                      l;

                                  ) {
                                      switch (l[2] || "d") {
                                          case "d":
                                          case "D":
                                              r += parseInt(l[1], 10);
                                              break;
                                          case "w":
                                          case "W":
                                              r += 7 * parseInt(l[1], 10);
                                              break;
                                          case "m":
                                          case "M":
                                              (o += parseInt(l[1], 10)), (r = Math.min(r, t.datepicker._getDaysInMonth(s, o)));
                                              break;
                                          case "y":
                                          case "Y":
                                              (s += parseInt(l[1], 10)), (r = Math.min(r, t.datepicker._getDaysInMonth(s, o)));
                                      }
                                      l = a.exec(i);
                                  }
                                  return new Date(s, o, r);
                              })(i)
                            : "number" == typeof i
                            ? isNaN(i)
                                ? n
                                : ((s = i), (o = new Date()).setDate(o.getDate() + s), o)
                            : new Date(i.getTime());
                    return (i = i && "Invalid Date" === i.toString() ? n : i) && (i.setHours(0), i.setMinutes(0), i.setSeconds(0), i.setMilliseconds(0)), this._daylightSavingAdjust(i);
                },
                _daylightSavingAdjust: function (t) {
                    return t ? (t.setHours(12 < t.getHours() ? t.getHours() + 2 : 0), t) : null;
                },
                _setDate: function (t, e, i) {
                    var n = !e,
                        s = t.selectedMonth,
                        o = t.selectedYear;
                    e = this._restrictMinMax(t, this._determineDate(t, e, new Date()));
                    (t.selectedDay = t.currentDay = e.getDate()),
                        (t.drawMonth = t.selectedMonth = t.currentMonth = e.getMonth()),
                        (t.drawYear = t.selectedYear = t.currentYear = e.getFullYear()),
                        (s === t.selectedMonth && o === t.selectedYear) || i || this._notifyChange(t),
                        this._adjustInstDate(t),
                        t.input && t.input.val(n ? "" : this._formatDate(t));
                },
                _getDate: function (t) {
                    return !t.currentYear || (t.input && "" === t.input.val()) ? null : this._daylightSavingAdjust(new Date(t.currentYear, t.currentMonth, t.currentDay));
                },
                _attachHandlers: function (e) {
                    var i = this._get(e, "stepMonths"),
                        n = "#" + e.id.replace(/\\\\/g, "\\");
                    e.dpDiv.find("[data-handler]").map(function () {
                        var e = {
                            prev: function () {
                                t.datepicker._adjustDate(n, -i, "M");
                            },
                            next: function () {
                                t.datepicker._adjustDate(n, +i, "M");
                            },
                            hide: function () {
                                t.datepicker._hideDatepicker();
                            },
                            today: function () {
                                t.datepicker._gotoToday(n);
                            },
                            selectDay: function () {
                                return t.datepicker._selectDay(n, +this.getAttribute("data-month"), +this.getAttribute("data-year"), this), !1;
                            },
                            selectMonth: function () {
                                return t.datepicker._selectMonthYear(n, this, "M"), !1;
                            },
                            selectYear: function () {
                                return t.datepicker._selectMonthYear(n, this, "Y"), !1;
                            },
                        };
                        t(this).on(this.getAttribute("data-event"), e[this.getAttribute("data-handler")]);
                    });
                },
                _generateHTML: function (e) {
                    var i,
                        n,
                        s,
                        o,
                        r,
                        a,
                        l,
                        h,
                        c,
                        u,
                        d,
                        p,
                        f,
                        g,
                        m,
                        v,
                        _,
                        b,
                        y,
                        w,
                        x,
                        k,
                        C,
                        T,
                        D,
                        S,
                        A,
                        E,
                        I,
                        P,
                        M,
                        O,
                        H = new Date(),
                        L = this._daylightSavingAdjust(new Date(H.getFullYear(), H.getMonth(), H.getDate())),
                        N = this._get(e, "isRTL"),
                        W = this._get(e, "showButtonPanel"),
                        R = this._get(e, "hideIfNoPrevNext"),
                        z = this._get(e, "navigationAsDateFormat"),
                        j = this._getNumberOfMonths(e),
                        F = this._get(e, "showCurrentAtPos"),
                        q = ((H = this._get(e, "stepMonths")), 1 !== j[0] || 1 !== j[1]),
                        Y = this._daylightSavingAdjust(e.currentDay ? new Date(e.currentYear, e.currentMonth, e.currentDay) : new Date(9999, 9, 9)),
                        B = this._getMinMaxDate(e, "min"),
                        $ = this._getMinMaxDate(e, "max"),
                        X = e.drawMonth - F,
                        U = e.drawYear;
                    if ((X < 0 && ((X += 12), U--), $))
                        for (i = this._daylightSavingAdjust(new Date($.getFullYear(), $.getMonth() - j[0] * j[1] + 1, $.getDate())), i = B && i < B ? B : i; this._daylightSavingAdjust(new Date(U, X, 1)) > i; ) --X < 0 && ((X = 11), U--);
                    for (
                        e.drawMonth = X,
                            e.drawYear = U,
                            F = this._get(e, "prevText"),
                            F = z ? this.formatDate(F, this._daylightSavingAdjust(new Date(U, X - H, 1)), this._getFormatConfig(e)) : F,
                            n = this._canAdjustMonth(e, -1, U, X)
                                ? t("<a>")
                                      .attr({ class: "ui-datepicker-prev ui-corner-all", "data-handler": "prev", "data-event": "click", title: F })
                                      .append(
                                          t("<span>")
                                              .addClass("ui-icon ui-icon-circle-triangle-" + (N ? "e" : "w"))
                                              .text(F)
                                      )[0].outerHTML
                                : R
                                ? ""
                                : t("<a>")
                                      .attr({ class: "ui-datepicker-prev ui-corner-all ui-state-disabled", title: F })
                                      .append(
                                          t("<span>")
                                              .addClass("ui-icon ui-icon-circle-triangle-" + (N ? "e" : "w"))
                                              .text(F)
                                      )[0].outerHTML,
                            F = this._get(e, "nextText"),
                            F = z ? this.formatDate(F, this._daylightSavingAdjust(new Date(U, X + H, 1)), this._getFormatConfig(e)) : F,
                            s = this._canAdjustMonth(e, 1, U, X)
                                ? t("<a>")
                                      .attr({ class: "ui-datepicker-next ui-corner-all", "data-handler": "next", "data-event": "click", title: F })
                                      .append(
                                          t("<span>")
                                              .addClass("ui-icon ui-icon-circle-triangle-" + (N ? "w" : "e"))
                                              .text(F)
                                      )[0].outerHTML
                                : R
                                ? ""
                                : t("<a>")
                                      .attr({ class: "ui-datepicker-next ui-corner-all ui-state-disabled", title: F })
                                      .append(
                                          t("<span>")
                                              .attr("class", "ui-icon ui-icon-circle-triangle-" + (N ? "w" : "e"))
                                              .text(F)
                                      )[0].outerHTML,
                            H = this._get(e, "currentText"),
                            R = this._get(e, "gotoCurrent") && e.currentDay ? Y : L,
                            H = z ? this.formatDate(H, R, this._getFormatConfig(e)) : H,
                            F = "",
                            e.inline ||
                                (F = t("<button>").attr({ type: "button", class: "ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all", "data-handler": "hide", "data-event": "click" }).text(this._get(e, "closeText"))[0]
                                    .outerHTML),
                            z = "",
                            W &&
                                (z = t("<div class='ui-datepicker-buttonpane ui-widget-content'>")
                                    .append(N ? F : "")
                                    .append(
                                        this._isInRange(e, R)
                                            ? t("<button>").attr({ type: "button", class: "ui-datepicker-current ui-state-default ui-priority-secondary ui-corner-all", "data-handler": "today", "data-event": "click" }).text(H)
                                            : ""
                                    )
                                    .append(N ? "" : F)[0].outerHTML),
                            o = parseInt(this._get(e, "firstDay"), 10),
                            o = isNaN(o) ? 0 : o,
                            r = this._get(e, "showWeek"),
                            a = this._get(e, "dayNames"),
                            l = this._get(e, "dayNamesMin"),
                            h = this._get(e, "monthNames"),
                            c = this._get(e, "monthNamesShort"),
                            u = this._get(e, "beforeShowDay"),
                            d = this._get(e, "showOtherMonths"),
                            p = this._get(e, "selectOtherMonths"),
                            f = this._getDefaultDate(e),
                            g = "",
                            v = 0;
                        v < j[0];
                        v++
                    ) {
                        for (_ = "", this.maxRows = 4, b = 0; b < j[1]; b++) {
                            if (((y = this._daylightSavingAdjust(new Date(U, X, e.selectedDay))), (w = " ui-corner-all"), (x = ""), q)) {
                                if (((x += "<div class='ui-datepicker-group"), 1 < j[1]))
                                    switch (b) {
                                        case 0:
                                            (x += " ui-datepicker-group-first"), (w = " ui-corner-" + (N ? "right" : "left"));
                                            break;
                                        case j[1] - 1:
                                            (x += " ui-datepicker-group-last"), (w = " ui-corner-" + (N ? "left" : "right"));
                                            break;
                                        default:
                                            (x += " ui-datepicker-group-middle"), (w = "");
                                    }
                                x += "'>";
                            }
                            for (
                                x +=
                                    "<div class='ui-datepicker-header ui-widget-header ui-helper-clearfix" +
                                    w +
                                    "'>" +
                                    (/all|left/.test(w) && 0 === v ? (N ? s : n) : "") +
                                    (/all|right/.test(w) && 0 === v ? (N ? n : s) : "") +
                                    this._generateMonthYearHeader(e, X, U, B, $, 0 < v || 0 < b, h, c) +
                                    "</div><table class='ui-datepicker-calendar'><thead><tr>",
                                    k = r ? "<th class='ui-datepicker-week-col'>" + this._get(e, "weekHeader") + "</th>" : "",
                                    m = 0;
                                m < 7;
                                m++
                            )
                                k += "<th scope='col'" + (5 <= (m + o + 6) % 7 ? " class='ui-datepicker-week-end'" : "") + "><span title='" + a[(C = (m + o) % 7)] + "'>" + l[C] + "</span></th>";
                            for (
                                x += k + "</tr></thead><tbody>",
                                    D = this._getDaysInMonth(U, X),
                                    U === e.selectedYear && X === e.selectedMonth && (e.selectedDay = Math.min(e.selectedDay, D)),
                                    T = (this._getFirstDayOfMonth(U, X) - o + 7) % 7,
                                    D = Math.ceil((T + D) / 7),
                                    S = q && this.maxRows > D ? this.maxRows : D,
                                    this.maxRows = S,
                                    A = this._daylightSavingAdjust(new Date(U, X, 1 - T)),
                                    E = 0;
                                E < S;
                                E++
                            ) {
                                for (x += "<tr>", I = r ? "<td class='ui-datepicker-week-col'>" + this._get(e, "calculateWeek")(A) + "</td>" : "", m = 0; m < 7; m++)
                                    (P = u ? u.apply(e.input ? e.input[0] : null, [A]) : [!0, ""]),
                                        (O = ((M = A.getMonth() !== X) && !p) || !P[0] || (B && A < B) || ($ && $ < A)),
                                        (I +=
                                            "<td class='" +
                                            (5 <= (m + o + 6) % 7 ? " ui-datepicker-week-end" : "") +
                                            (M ? " ui-datepicker-other-month" : "") +
                                            ((A.getTime() === y.getTime() && X === e.selectedMonth && e._keyEvent) || (f.getTime() === A.getTime() && f.getTime() === y.getTime()) ? " " + this._dayOverClass : "") +
                                            (O ? " " + this._unselectableClass + " ui-state-disabled" : "") +
                                            (M && !d ? "" : " " + P[1] + (A.getTime() === Y.getTime() ? " " + this._currentClass : "") + (A.getTime() === L.getTime() ? " ui-datepicker-today" : "")) +
                                            "'" +
                                            ((M && !d) || !P[2] ? "" : " title='" + P[2].replace(/'/g, "&#39;") + "'") +
                                            (O ? "" : " data-handler='selectDay' data-event='click' data-month='" + A.getMonth() + "' data-year='" + A.getFullYear() + "'") +
                                            ">" +
                                            (M && !d
                                                ? "&#xa0;"
                                                : O
                                                ? "<span class='ui-state-default'>" + A.getDate() + "</span>"
                                                : "<a class='ui-state-default" +
                                                  (A.getTime() === L.getTime() ? " ui-state-highlight" : "") +
                                                  (A.getTime() === Y.getTime() ? " ui-state-active" : "") +
                                                  (M ? " ui-priority-secondary" : "") +
                                                  "' href='#' aria-current='" +
                                                  (A.getTime() === Y.getTime() ? "true" : "false") +
                                                  "' data-date='" +
                                                  A.getDate() +
                                                  "'>" +
                                                  A.getDate() +
                                                  "</a>") +
                                            "</td>"),
                                        A.setDate(A.getDate() + 1),
                                        (A = this._daylightSavingAdjust(A));
                                x += I + "</tr>";
                            }
                            11 < ++X && ((X = 0), U++), (_ += x += "</tbody></table>" + (q ? "</div>" + (0 < j[0] && b === j[1] - 1 ? "<div class='ui-datepicker-row-break'></div>" : "") : ""));
                        }
                        g += _;
                    }
                    return (g += z), (e._keyEvent = !1), g;
                },
                _generateMonthYearHeader: function (t, e, i, n, s, o, r, a) {
                    var l,
                        h,
                        c,
                        u,
                        d,
                        p,
                        f = this._get(t, "changeMonth"),
                        g = this._get(t, "changeYear"),
                        m = this._get(t, "showMonthAfterYear"),
                        v = this._get(t, "selectMonthLabel"),
                        _ = this._get(t, "selectYearLabel"),
                        b = "<div class='ui-datepicker-title'>",
                        y = "";
                    if (o || !f) y += "<span class='ui-datepicker-month'>" + r[e] + "</span>";
                    else {
                        for (l = n && n.getFullYear() === i, h = s && s.getFullYear() === i, y += "<select class='ui-datepicker-month' aria-label='" + v + "' data-handler='selectMonth' data-event='change'>", c = 0; c < 12; c++)
                            (!l || c >= n.getMonth()) && (!h || c <= s.getMonth()) && (y += "<option value='" + c + "'" + (c === e ? " selected='selected'" : "") + ">" + a[c] + "</option>");
                        y += "</select>";
                    }
                    if ((m || (b += y + (!o && f && g ? "" : "&#xa0;")), !t.yearshtml))
                        if (((t.yearshtml = ""), o || !g)) b += "<span class='ui-datepicker-year'>" + i + "</span>";
                        else {
                            for (
                                r = this._get(t, "yearRange").split(":"),
                                    u = new Date().getFullYear(),
                                    d = (v = function (t) {
                                        return (t = t.match(/c[+\-].*/) ? i + parseInt(t.substring(1), 10) : t.match(/[+\-].*/) ? u + parseInt(t, 10) : parseInt(t, 10)), isNaN(t) ? u : t;
                                    })(r[0]),
                                    p = Math.max(d, v(r[1] || "")),
                                    d = n ? Math.max(d, n.getFullYear()) : d,
                                    p = s ? Math.min(p, s.getFullYear()) : p,
                                    t.yearshtml += "<select class='ui-datepicker-year' aria-label='" + _ + "' data-handler='selectYear' data-event='change'>";
                                d <= p;
                                d++
                            )
                                t.yearshtml += "<option value='" + d + "'" + (d === i ? " selected='selected'" : "") + ">" + d + "</option>";
                            (t.yearshtml += "</select>"), (b += t.yearshtml), (t.yearshtml = null);
                        }
                    return (b += this._get(t, "yearSuffix")), m && (b += (!o && f && g ? "" : "&#xa0;") + y), b + "</div>";
                },
                _adjustInstDate: function (t, e, i) {
                    var n = t.selectedYear + ("Y" === i ? e : 0),
                        s = t.selectedMonth + ("M" === i ? e : 0);
                    (e = Math.min(t.selectedDay, this._getDaysInMonth(n, s)) + ("D" === i ? e : 0)), (e = this._restrictMinMax(t, this._daylightSavingAdjust(new Date(n, s, e))));
                    (t.selectedDay = e.getDate()), (t.drawMonth = t.selectedMonth = e.getMonth()), (t.drawYear = t.selectedYear = e.getFullYear()), ("M" !== i && "Y" !== i) || this._notifyChange(t);
                },
                _restrictMinMax: function (t, e) {
                    var i = this._getMinMaxDate(t, "min");
                    e = i && e < i ? i : e;
                    return (t = this._getMinMaxDate(t, "max")) && t < e ? t : e;
                },
                _notifyChange: function (t) {
                    var e = this._get(t, "onChangeMonthYear");
                    e && e.apply(t.input ? t.input[0] : null, [t.selectedYear, t.selectedMonth + 1, t]);
                },
                _getNumberOfMonths: function (t) {
                    return null == (t = this._get(t, "numberOfMonths")) ? [1, 1] : "number" == typeof t ? [1, t] : t;
                },
                _getMinMaxDate: function (t, e) {
                    return this._determineDate(t, this._get(t, e + "Date"), null);
                },
                _getDaysInMonth: function (t, e) {
                    return 32 - this._daylightSavingAdjust(new Date(t, e, 32)).getDate();
                },
                _getFirstDayOfMonth: function (t, e) {
                    return new Date(t, e, 1).getDay();
                },
                _canAdjustMonth: function (t, e, i, n) {
                    var s = this._getNumberOfMonths(t);
                    s = this._daylightSavingAdjust(new Date(i, n + (e < 0 ? e : s[0] * s[1]), 1));
                    return e < 0 && s.setDate(this._getDaysInMonth(s.getFullYear(), s.getMonth())), this._isInRange(t, s);
                },
                _isInRange: function (t, e) {
                    var i = this._getMinMaxDate(t, "min"),
                        n = this._getMinMaxDate(t, "max"),
                        s = null,
                        o = null,
                        r = this._get(t, "yearRange");
                    return (
                        r && ((t = r.split(":")), (r = new Date().getFullYear()), (s = parseInt(t[0], 10)), (o = parseInt(t[1], 10)), t[0].match(/[+\-].*/) && (s += r), t[1].match(/[+\-].*/) && (o += r)),
                        (!i || e.getTime() >= i.getTime()) && (!n || e.getTime() <= n.getTime()) && (!s || e.getFullYear() >= s) && (!o || e.getFullYear() <= o)
                    );
                },
                _getFormatConfig: function (t) {
                    var e = this._get(t, "shortYearCutoff");
                    return {
                        shortYearCutoff: (e = "string" != typeof e ? e : (new Date().getFullYear() % 100) + parseInt(e, 10)),
                        dayNamesShort: this._get(t, "dayNamesShort"),
                        dayNames: this._get(t, "dayNames"),
                        monthNamesShort: this._get(t, "monthNamesShort"),
                        monthNames: this._get(t, "monthNames"),
                    };
                },
                _formatDate: function (t, e, i, n) {
                    return (
                        e || ((t.currentDay = t.selectedDay), (t.currentMonth = t.selectedMonth), (t.currentYear = t.selectedYear)),
                        (e = e ? ("object" == typeof e ? e : this._daylightSavingAdjust(new Date(n, i, e))) : this._daylightSavingAdjust(new Date(t.currentYear, t.currentMonth, t.currentDay))),
                        this.formatDate(this._get(t, "dateFormat"), e, this._getFormatConfig(t))
                    );
                },
            }),
            (t.fn.datepicker = function (e) {
                if (!this.length) return this;
                t.datepicker.initialized || (t(document).on("mousedown", t.datepicker._checkExternalClick), (t.datepicker.initialized = !0)), 0 === t("#" + t.datepicker._mainDivId).length && t("body").append(t.datepicker.dpDiv);
                var i = Array.prototype.slice.call(arguments, 1);
                return ("string" == typeof e && ("isDisabled" === e || "getDate" === e || "widget" === e)) || ("option" === e && 2 === arguments.length && "string" == typeof arguments[1])
                    ? t.datepicker["_" + e + "Datepicker"].apply(t.datepicker, [this[0]].concat(i))
                    : this.each(function () {
                          "string" == typeof e ? t.datepicker["_" + e + "Datepicker"].apply(t.datepicker, [this].concat(i)) : t.datepicker._attachDatepicker(this, e);
                      });
            }),
            (t.datepicker = new nt()),
            (t.datepicker.initialized = !1),
            (t.datepicker.uuid = new Date().getTime()),
            (t.datepicker.version = "1.13.2"),
            t.datepicker,
            (t.ui.ie = !!/msie [\w.]+/.exec(navigator.userAgent.toLowerCase()));
        var at,
            lt = !1;
        function ht(t, e, i) {
            return e <= t && t < e + i;
        }
        function ct(t) {
            return function () {
                var e = this.element.val();
                t.apply(this, arguments), this._refresh(), e !== this.element.val() && this._trigger("change");
            };
        }
        t(document).on("mouseup", function () {
            lt = !1;
        }),
            t.widget("ui.mouse", {
                version: "1.13.2",
                options: { cancel: "input, textarea, button, select, option", distance: 1, delay: 0 },
                _mouseInit: function () {
                    var e = this;
                    this.element
                        .on("mousedown." + this.widgetName, function (t) {
                            return e._mouseDown(t);
                        })
                        .on("click." + this.widgetName, function (i) {
                            if (!0 === t.data(i.target, e.widgetName + ".preventClickEvent")) return t.removeData(i.target, e.widgetName + ".preventClickEvent"), i.stopImmediatePropagation(), !1;
                        }),
                        (this.started = !1);
                },
                _mouseDestroy: function () {
                    this.element.off("." + this.widgetName), this._mouseMoveDelegate && this.document.off("mousemove." + this.widgetName, this._mouseMoveDelegate).off("mouseup." + this.widgetName, this._mouseUpDelegate);
                },
                _mouseDown: function (e) {
                    if (!lt) {
                        (this._mouseMoved = !1), this._mouseStarted && this._mouseUp(e), (this._mouseDownEvent = e);
                        var i = this,
                            n = 1 === e.which,
                            s = !("string" != typeof this.options.cancel || !e.target.nodeName) && t(e.target).closest(this.options.cancel).length;
                        return (
                            !(n && !s && this._mouseCapture(e)) ||
                            ((this.mouseDelayMet = !this.options.delay),
                            this.mouseDelayMet ||
                                (this._mouseDelayTimer = setTimeout(function () {
                                    i.mouseDelayMet = !0;
                                }, this.options.delay)),
                            this._mouseDistanceMet(e) && this._mouseDelayMet(e) && ((this._mouseStarted = !1 !== this._mouseStart(e)), !this._mouseStarted)
                                ? (e.preventDefault(), !0)
                                : (!0 === t.data(e.target, this.widgetName + ".preventClickEvent") && t.removeData(e.target, this.widgetName + ".preventClickEvent"),
                                  (this._mouseMoveDelegate = function (t) {
                                      return i._mouseMove(t);
                                  }),
                                  (this._mouseUpDelegate = function (t) {
                                      return i._mouseUp(t);
                                  }),
                                  this.document.on("mousemove." + this.widgetName, this._mouseMoveDelegate).on("mouseup." + this.widgetName, this._mouseUpDelegate),
                                  e.preventDefault(),
                                  (lt = !0)))
                        );
                    }
                },
                _mouseMove: function (e) {
                    if (this._mouseMoved) {
                        if (t.ui.ie && (!document.documentMode || document.documentMode < 9) && !e.button) return this._mouseUp(e);
                        if (!e.which)
                            if (e.originalEvent.altKey || e.originalEvent.ctrlKey || e.originalEvent.metaKey || e.originalEvent.shiftKey) this.ignoreMissingWhich = !0;
                            else if (!this.ignoreMissingWhich) return this._mouseUp(e);
                    }
                    return (
                        (e.which || e.button) && (this._mouseMoved = !0),
                        this._mouseStarted
                            ? (this._mouseDrag(e), e.preventDefault())
                            : (this._mouseDistanceMet(e) && this._mouseDelayMet(e) && ((this._mouseStarted = !1 !== this._mouseStart(this._mouseDownEvent, e)), this._mouseStarted ? this._mouseDrag(e) : this._mouseUp(e)),
                              !this._mouseStarted)
                    );
                },
                _mouseUp: function (e) {
                    this.document.off("mousemove." + this.widgetName, this._mouseMoveDelegate).off("mouseup." + this.widgetName, this._mouseUpDelegate),
                        this._mouseStarted && ((this._mouseStarted = !1), e.target === this._mouseDownEvent.target && t.data(e.target, this.widgetName + ".preventClickEvent", !0), this._mouseStop(e)),
                        this._mouseDelayTimer && (clearTimeout(this._mouseDelayTimer), delete this._mouseDelayTimer),
                        (this.ignoreMissingWhich = !1),
                        (lt = !1),
                        e.preventDefault();
                },
                _mouseDistanceMet: function (t) {
                    return Math.max(Math.abs(this._mouseDownEvent.pageX - t.pageX), Math.abs(this._mouseDownEvent.pageY - t.pageY)) >= this.options.distance;
                },
                _mouseDelayMet: function () {
                    return this.mouseDelayMet;
                },
                _mouseStart: function () {},
                _mouseDrag: function () {},
                _mouseStop: function () {},
                _mouseCapture: function () {
                    return !0;
                },
            }),
            (t.ui.plugin = {
                add: function (e, i, n) {
                    var s,
                        o = t.ui[e].prototype;
                    for (s in n) (o.plugins[s] = o.plugins[s] || []), o.plugins[s].push([i, n[s]]);
                },
                call: function (t, e, i, n) {
                    var s,
                        o = t.plugins[e];
                    if (o && (n || (t.element[0].parentNode && 11 !== t.element[0].parentNode.nodeType))) for (s = 0; s < o.length; s++) t.options[o[s][0]] && o[s][1].apply(t.element, i);
                },
            }),
            (t.ui.safeBlur = function (e) {
                e && "body" !== e.nodeName.toLowerCase() && t(e).trigger("blur");
            }),
            t.widget("ui.draggable", t.ui.mouse, {
                version: "1.13.2",
                widgetEventPrefix: "drag",
                options: {
                    addClasses: !0,
                    appendTo: "parent",
                    axis: !1,
                    connectToSortable: !1,
                    containment: !1,
                    cursor: "auto",
                    cursorAt: !1,
                    grid: !1,
                    handle: !1,
                    helper: "original",
                    iframeFix: !1,
                    opacity: !1,
                    refreshPositions: !1,
                    revert: !1,
                    revertDuration: 500,
                    scope: "default",
                    scroll: !0,
                    scrollSensitivity: 20,
                    scrollSpeed: 20,
                    snap: !1,
                    snapMode: "both",
                    snapTolerance: 20,
                    stack: !1,
                    zIndex: !1,
                    drag: null,
                    start: null,
                    stop: null,
                },
                _create: function () {
                    "original" === this.options.helper && this._setPositionRelative(), this.options.addClasses && this._addClass("ui-draggable"), this._setHandleClassName(), this._mouseInit();
                },
                _setOption: function (t, e) {
                    this._super(t, e), "handle" === t && (this._removeHandleClassName(), this._setHandleClassName());
                },
                _destroy: function () {
                    (this.helper || this.element).is(".ui-draggable-dragging") ? (this.destroyOnClear = !0) : (this._removeHandleClassName(), this._mouseDestroy());
                },
                _mouseCapture: function (e) {
                    var i = this.options;
                    return !(
                        this.helper ||
                        i.disabled ||
                        0 < t(e.target).closest(".ui-resizable-handle").length ||
                        ((this.handle = this._getHandle(e)), !this.handle || (this._blurActiveElement(e), this._blockFrames(!0 === i.iframeFix ? "iframe" : i.iframeFix), 0))
                    );
                },
                _blockFrames: function (e) {
                    this.iframeBlocks = this.document.find(e).map(function () {
                        var e = t(this);
                        return t("<div>").css("position", "absolute").appendTo(e.parent()).outerWidth(e.outerWidth()).outerHeight(e.outerHeight()).offset(e.offset())[0];
                    });
                },
                _unblockFrames: function () {
                    this.iframeBlocks && (this.iframeBlocks.remove(), delete this.iframeBlocks);
                },
                _blurActiveElement: function (e) {
                    var i = t.ui.safeActiveElement(this.document[0]);
                    t(e.target).closest(i).length || t.ui.safeBlur(i);
                },
                _mouseStart: function (e) {
                    var i = this.options;
                    return (
                        (this.helper = this._createHelper(e)),
                        this._addClass(this.helper, "ui-draggable-dragging"),
                        this._cacheHelperProportions(),
                        t.ui.ddmanager && (t.ui.ddmanager.current = this),
                        this._cacheMargins(),
                        (this.cssPosition = this.helper.css("position")),
                        (this.scrollParent = this.helper.scrollParent(!0)),
                        (this.offsetParent = this.helper.offsetParent()),
                        (this.hasFixedAncestor =
                            0 <
                            this.helper.parents().filter(function () {
                                return "fixed" === t(this).css("position");
                            }).length),
                        (this.positionAbs = this.element.offset()),
                        this._refreshOffsets(e),
                        (this.originalPosition = this.position = this._generatePosition(e, !1)),
                        (this.originalPageX = e.pageX),
                        (this.originalPageY = e.pageY),
                        i.cursorAt && this._adjustOffsetFromHelper(i.cursorAt),
                        this._setContainment(),
                        !1 === this._trigger("start", e)
                            ? (this._clear(), !1)
                            : (this._cacheHelperProportions(), t.ui.ddmanager && !i.dropBehaviour && t.ui.ddmanager.prepareOffsets(this, e), this._mouseDrag(e, !0), t.ui.ddmanager && t.ui.ddmanager.dragStart(this, e), !0)
                    );
                },
                _refreshOffsets: function (t) {
                    (this.offset = { top: this.positionAbs.top - this.margins.top, left: this.positionAbs.left - this.margins.left, scroll: !1, parent: this._getParentOffset(), relative: this._getRelativeOffset() }),
                        (this.offset.click = { left: t.pageX - this.offset.left, top: t.pageY - this.offset.top });
                },
                _mouseDrag: function (e, i) {
                    if ((this.hasFixedAncestor && (this.offset.parent = this._getParentOffset()), (this.position = this._generatePosition(e, !0)), (this.positionAbs = this._convertPositionTo("absolute")), !i)) {
                        if (((i = this._uiHash()), !1 === this._trigger("drag", e, i))) return this._mouseUp(new t.Event("mouseup", e)), !1;
                        this.position = i.position;
                    }
                    return (this.helper[0].style.left = this.position.left + "px"), (this.helper[0].style.top = this.position.top + "px"), t.ui.ddmanager && t.ui.ddmanager.drag(this, e), !1;
                },
                _mouseStop: function (e) {
                    var i = this,
                        n = !1;
                    return (
                        t.ui.ddmanager && !this.options.dropBehaviour && (n = t.ui.ddmanager.drop(this, e)),
                        this.dropped && ((n = this.dropped), (this.dropped = !1)),
                        ("invalid" === this.options.revert && !n) || ("valid" === this.options.revert && n) || !0 === this.options.revert || ("function" == typeof this.options.revert && this.options.revert.call(this.element, n))
                            ? t(this.helper).animate(this.originalPosition, parseInt(this.options.revertDuration, 10), function () {
                                  !1 !== i._trigger("stop", e) && i._clear();
                              })
                            : !1 !== this._trigger("stop", e) && this._clear(),
                        !1
                    );
                },
                _mouseUp: function (e) {
                    return this._unblockFrames(), t.ui.ddmanager && t.ui.ddmanager.dragStop(this, e), this.handleElement.is(e.target) && this.element.trigger("focus"), t.ui.mouse.prototype._mouseUp.call(this, e);
                },
                cancel: function () {
                    return this.helper.is(".ui-draggable-dragging") ? this._mouseUp(new t.Event("mouseup", { target: this.element[0] })) : this._clear(), this;
                },
                _getHandle: function (e) {
                    return !this.options.handle || !!t(e.target).closest(this.element.find(this.options.handle)).length;
                },
                _setHandleClassName: function () {
                    (this.handleElement = this.options.handle ? this.element.find(this.options.handle) : this.element), this._addClass(this.handleElement, "ui-draggable-handle");
                },
                _removeHandleClassName: function () {
                    this._removeClass(this.handleElement, "ui-draggable-handle");
                },
                _createHelper: function (e) {
                    var i = this.options,
                        n = "function" == typeof i.helper;
                    return (
                        (e = n ? t(i.helper.apply(this.element[0], [e])) : "clone" === i.helper ? this.element.clone().removeAttr("id") : this.element).parents("body").length ||
                            e.appendTo("parent" === i.appendTo ? this.element[0].parentNode : i.appendTo),
                        n && e[0] === this.element[0] && this._setPositionRelative(),
                        e[0] === this.element[0] || /(fixed|absolute)/.test(e.css("position")) || e.css("position", "absolute"),
                        e
                    );
                },
                _setPositionRelative: function () {
                    /^(?:r|a|f)/.test(this.element.css("position")) || (this.element[0].style.position = "relative");
                },
                _adjustOffsetFromHelper: function (t) {
                    "string" == typeof t && (t = t.split(" ")),
                        "left" in (t = Array.isArray(t) ? { left: +t[0], top: +t[1] || 0 } : t) && (this.offset.click.left = t.left + this.margins.left),
                        "right" in t && (this.offset.click.left = this.helperProportions.width - t.right + this.margins.left),
                        "top" in t && (this.offset.click.top = t.top + this.margins.top),
                        "bottom" in t && (this.offset.click.top = this.helperProportions.height - t.bottom + this.margins.top);
                },
                _isRootNode: function (t) {
                    return /(html|body)/i.test(t.tagName) || t === this.document[0];
                },
                _getParentOffset: function () {
                    var e = this.offsetParent.offset(),
                        i = this.document[0];
                    return (
                        "absolute" === this.cssPosition && this.scrollParent[0] !== i && t.contains(this.scrollParent[0], this.offsetParent[0]) && ((e.left += this.scrollParent.scrollLeft()), (e.top += this.scrollParent.scrollTop())),
                        {
                            top: (e = this._isRootNode(this.offsetParent[0]) ? { top: 0, left: 0 } : e).top + (parseInt(this.offsetParent.css("borderTopWidth"), 10) || 0),
                            left: e.left + (parseInt(this.offsetParent.css("borderLeftWidth"), 10) || 0),
                        }
                    );
                },
                _getRelativeOffset: function () {
                    if ("relative" !== this.cssPosition) return { top: 0, left: 0 };
                    var t = this.element.position(),
                        e = this._isRootNode(this.scrollParent[0]);
                    return { top: t.top - (parseInt(this.helper.css("top"), 10) || 0) + (e ? 0 : this.scrollParent.scrollTop()), left: t.left - (parseInt(this.helper.css("left"), 10) || 0) + (e ? 0 : this.scrollParent.scrollLeft()) };
                },
                _cacheMargins: function () {
                    this.margins = {
                        left: parseInt(this.element.css("marginLeft"), 10) || 0,
                        top: parseInt(this.element.css("marginTop"), 10) || 0,
                        right: parseInt(this.element.css("marginRight"), 10) || 0,
                        bottom: parseInt(this.element.css("marginBottom"), 10) || 0,
                    };
                },
                _cacheHelperProportions: function () {
                    this.helperProportions = { width: this.helper.outerWidth(), height: this.helper.outerHeight() };
                },
                _setContainment: function () {
                    var e,
                        i,
                        n,
                        s = this.options,
                        o = this.document[0];
                    (this.relativeContainer = null),
                        s.containment
                            ? "window" !== s.containment
                                ? "document" !== s.containment
                                    ? s.containment.constructor !== Array
                                        ? ("parent" === s.containment && (s.containment = this.helper[0].parentNode),
                                          (n = (i = t(s.containment))[0]) &&
                                              ((e = /(scroll|auto)/.test(i.css("overflow"))),
                                              (this.containment = [
                                                  (parseInt(i.css("borderLeftWidth"), 10) || 0) + (parseInt(i.css("paddingLeft"), 10) || 0),
                                                  (parseInt(i.css("borderTopWidth"), 10) || 0) + (parseInt(i.css("paddingTop"), 10) || 0),
                                                  (e ? Math.max(n.scrollWidth, n.offsetWidth) : n.offsetWidth) -
                                                      (parseInt(i.css("borderRightWidth"), 10) || 0) -
                                                      (parseInt(i.css("paddingRight"), 10) || 0) -
                                                      this.helperProportions.width -
                                                      this.margins.left -
                                                      this.margins.right,
                                                  (e ? Math.max(n.scrollHeight, n.offsetHeight) : n.offsetHeight) -
                                                      (parseInt(i.css("borderBottomWidth"), 10) || 0) -
                                                      (parseInt(i.css("paddingBottom"), 10) || 0) -
                                                      this.helperProportions.height -
                                                      this.margins.top -
                                                      this.margins.bottom,
                                              ]),
                                              (this.relativeContainer = i)))
                                        : (this.containment = s.containment)
                                    : (this.containment = [0, 0, t(o).width() - this.helperProportions.width - this.margins.left, (t(o).height() || o.body.parentNode.scrollHeight) - this.helperProportions.height - this.margins.top])
                                : (this.containment = [
                                      t(window).scrollLeft() - this.offset.relative.left - this.offset.parent.left,
                                      t(window).scrollTop() - this.offset.relative.top - this.offset.parent.top,
                                      t(window).scrollLeft() + t(window).width() - this.helperProportions.width - this.margins.left,
                                      t(window).scrollTop() + (t(window).height() || o.body.parentNode.scrollHeight) - this.helperProportions.height - this.margins.top,
                                  ])
                            : (this.containment = null);
                },
                _convertPositionTo: function (t, e) {
                    e = e || this.position;
                    var i = "absolute" === t ? 1 : -1;
                    t = this._isRootNode(this.scrollParent[0]);
                    return {
                        top: e.top + this.offset.relative.top * i + this.offset.parent.top * i - ("fixed" === this.cssPosition ? -this.offset.scroll.top : t ? 0 : this.offset.scroll.top) * i,
                        left: e.left + this.offset.relative.left * i + this.offset.parent.left * i - ("fixed" === this.cssPosition ? -this.offset.scroll.left : t ? 0 : this.offset.scroll.left) * i,
                    };
                },
                _generatePosition: function (t, e) {
                    var i,
                        n = this.options,
                        s = this._isRootNode(this.scrollParent[0]),
                        o = t.pageX,
                        r = t.pageY;
                    return (
                        (s && this.offset.scroll) || (this.offset.scroll = { top: this.scrollParent.scrollTop(), left: this.scrollParent.scrollLeft() }),
                        e &&
                            (this.containment &&
                                ((i = this.relativeContainer
                                    ? ((i = this.relativeContainer.offset()), [this.containment[0] + i.left, this.containment[1] + i.top, this.containment[2] + i.left, this.containment[3] + i.top])
                                    : this.containment),
                                t.pageX - this.offset.click.left < i[0] && (o = i[0] + this.offset.click.left),
                                t.pageY - this.offset.click.top < i[1] && (r = i[1] + this.offset.click.top),
                                t.pageX - this.offset.click.left > i[2] && (o = i[2] + this.offset.click.left),
                                t.pageY - this.offset.click.top > i[3] && (r = i[3] + this.offset.click.top)),
                            n.grid &&
                                ((t = n.grid[1] ? this.originalPageY + Math.round((r - this.originalPageY) / n.grid[1]) * n.grid[1] : this.originalPageY),
                                (r = !i || t - this.offset.click.top >= i[1] || t - this.offset.click.top > i[3] ? t : t - this.offset.click.top >= i[1] ? t - n.grid[1] : t + n.grid[1]),
                                (t = n.grid[0] ? this.originalPageX + Math.round((o - this.originalPageX) / n.grid[0]) * n.grid[0] : this.originalPageX),
                                (o = !i || t - this.offset.click.left >= i[0] || t - this.offset.click.left > i[2] ? t : t - this.offset.click.left >= i[0] ? t - n.grid[0] : t + n.grid[0])),
                            "y" === n.axis && (o = this.originalPageX),
                            "x" === n.axis && (r = this.originalPageY)),
                        {
                            top: r - this.offset.click.top - this.offset.relative.top - this.offset.parent.top + ("fixed" === this.cssPosition ? -this.offset.scroll.top : s ? 0 : this.offset.scroll.top),
                            left: o - this.offset.click.left - this.offset.relative.left - this.offset.parent.left + ("fixed" === this.cssPosition ? -this.offset.scroll.left : s ? 0 : this.offset.scroll.left),
                        }
                    );
                },
                _clear: function () {
                    this._removeClass(this.helper, "ui-draggable-dragging"),
                        this.helper[0] === this.element[0] || this.cancelHelperRemoval || this.helper.remove(),
                        (this.helper = null),
                        (this.cancelHelperRemoval = !1),
                        this.destroyOnClear && this.destroy();
                },
                _trigger: function (e, i, n) {
                    return (
                        (n = n || this._uiHash()),
                        t.ui.plugin.call(this, e, [i, n, this], !0),
                        /^(drag|start|stop)/.test(e) && ((this.positionAbs = this._convertPositionTo("absolute")), (n.offset = this.positionAbs)),
                        t.Widget.prototype._trigger.call(this, e, i, n)
                    );
                },
                plugins: {},
                _uiHash: function () {
                    return { helper: this.helper, position: this.position, originalPosition: this.originalPosition, offset: this.positionAbs };
                },
            }),
            t.ui.plugin.add("draggable", "connectToSortable", {
                start: function (e, i, n) {
                    var s = t.extend({}, i, { item: n.element });
                    (n.sortables = []),
                        t(n.options.connectToSortable).each(function () {
                            var i = t(this).sortable("instance");
                            i && !i.options.disabled && (n.sortables.push(i), i.refreshPositions(), i._trigger("activate", e, s));
                        });
                },
                stop: function (e, i, n) {
                    var s = t.extend({}, i, { item: n.element });
                    (n.cancelHelperRemoval = !1),
                        t.each(n.sortables, function () {
                            var t = this;
                            t.isOver
                                ? ((t.isOver = 0),
                                  (n.cancelHelperRemoval = !0),
                                  (t.cancelHelperRemoval = !1),
                                  (t._storedCSS = { position: t.placeholder.css("position"), top: t.placeholder.css("top"), left: t.placeholder.css("left") }),
                                  t._mouseStop(e),
                                  (t.options.helper = t.options._helper))
                                : ((t.cancelHelperRemoval = !0), t._trigger("deactivate", e, s));
                        });
                },
                drag: function (e, i, n) {
                    t.each(n.sortables, function () {
                        var s = !1,
                            o = this;
                        (o.positionAbs = n.positionAbs),
                            (o.helperProportions = n.helperProportions),
                            (o.offset.click = n.offset.click),
                            o._intersectsWith(o.containerCache) &&
                                ((s = !0),
                                t.each(n.sortables, function () {
                                    return (
                                        (this.positionAbs = n.positionAbs),
                                        (this.helperProportions = n.helperProportions),
                                        (this.offset.click = n.offset.click),
                                        (s = (this === o || !this._intersectsWith(this.containerCache) || !t.contains(o.element[0], this.element[0])) && s)
                                    );
                                })),
                            s
                                ? (o.isOver ||
                                      ((o.isOver = 1),
                                      (n._parent = i.helper.parent()),
                                      (o.currentItem = i.helper.appendTo(o.element).data("ui-sortable-item", !0)),
                                      (o.options._helper = o.options.helper),
                                      (o.options.helper = function () {
                                          return i.helper[0];
                                      }),
                                      (e.target = o.currentItem[0]),
                                      o._mouseCapture(e, !0),
                                      o._mouseStart(e, !0, !0),
                                      (o.offset.click.top = n.offset.click.top),
                                      (o.offset.click.left = n.offset.click.left),
                                      (o.offset.parent.left -= n.offset.parent.left - o.offset.parent.left),
                                      (o.offset.parent.top -= n.offset.parent.top - o.offset.parent.top),
                                      n._trigger("toSortable", e),
                                      (n.dropped = o.element),
                                      t.each(n.sortables, function () {
                                          this.refreshPositions();
                                      }),
                                      (n.currentItem = n.element),
                                      (o.fromOutside = n)),
                                  o.currentItem && (o._mouseDrag(e), (i.position = o.position)))
                                : o.isOver &&
                                  ((o.isOver = 0),
                                  (o.cancelHelperRemoval = !0),
                                  (o.options._revert = o.options.revert),
                                  (o.options.revert = !1),
                                  o._trigger("out", e, o._uiHash(o)),
                                  o._mouseStop(e, !0),
                                  (o.options.revert = o.options._revert),
                                  (o.options.helper = o.options._helper),
                                  o.placeholder && o.placeholder.remove(),
                                  i.helper.appendTo(n._parent),
                                  n._refreshOffsets(e),
                                  (i.position = n._generatePosition(e, !0)),
                                  n._trigger("fromSortable", e),
                                  (n.dropped = !1),
                                  t.each(n.sortables, function () {
                                      this.refreshPositions();
                                  }));
                    });
                },
            }),
            t.ui.plugin.add("draggable", "cursor", {
                start: function (e, i, n) {
                    var s = t("body");
                    n = n.options;
                    s.css("cursor") && (n._cursor = s.css("cursor")), s.css("cursor", n.cursor);
                },
                stop: function (e, i, n) {
                    (n = n.options)._cursor && t("body").css("cursor", n._cursor);
                },
            }),
            t.ui.plugin.add("draggable", "opacity", {
                start: function (e, i, n) {
                    (i = t(i.helper)), (n = n.options), i.css("opacity") && (n._opacity = i.css("opacity")), i.css("opacity", n.opacity);
                },
                stop: function (e, i, n) {
                    (n = n.options)._opacity && t(i.helper).css("opacity", n._opacity);
                },
            }),
            t.ui.plugin.add("draggable", "scroll", {
                start: function (t, e, i) {
                    i.scrollParentNotHidden || (i.scrollParentNotHidden = i.helper.scrollParent(!1)),
                        i.scrollParentNotHidden[0] !== i.document[0] && "HTML" !== i.scrollParentNotHidden[0].tagName && (i.overflowOffset = i.scrollParentNotHidden.offset());
                },
                drag: function (e, i, n) {
                    var s = n.options,
                        o = !1,
                        r = n.scrollParentNotHidden[0],
                        a = n.document[0];
                    r !== a && "HTML" !== r.tagName
                        ? ((s.axis && "x" === s.axis) ||
                              (n.overflowOffset.top + r.offsetHeight - e.pageY < s.scrollSensitivity
                                  ? (r.scrollTop = o = r.scrollTop + s.scrollSpeed)
                                  : e.pageY - n.overflowOffset.top < s.scrollSensitivity && (r.scrollTop = o = r.scrollTop - s.scrollSpeed)),
                          (s.axis && "y" === s.axis) ||
                              (n.overflowOffset.left + r.offsetWidth - e.pageX < s.scrollSensitivity
                                  ? (r.scrollLeft = o = r.scrollLeft + s.scrollSpeed)
                                  : e.pageX - n.overflowOffset.left < s.scrollSensitivity && (r.scrollLeft = o = r.scrollLeft - s.scrollSpeed)))
                        : ((s.axis && "x" === s.axis) ||
                              (e.pageY - t(a).scrollTop() < s.scrollSensitivity
                                  ? (o = t(a).scrollTop(t(a).scrollTop() - s.scrollSpeed))
                                  : t(window).height() - (e.pageY - t(a).scrollTop()) < s.scrollSensitivity && (o = t(a).scrollTop(t(a).scrollTop() + s.scrollSpeed))),
                          (s.axis && "y" === s.axis) ||
                              (e.pageX - t(a).scrollLeft() < s.scrollSensitivity
                                  ? (o = t(a).scrollLeft(t(a).scrollLeft() - s.scrollSpeed))
                                  : t(window).width() - (e.pageX - t(a).scrollLeft()) < s.scrollSensitivity && (o = t(a).scrollLeft(t(a).scrollLeft() + s.scrollSpeed)))),
                        !1 !== o && t.ui.ddmanager && !s.dropBehaviour && t.ui.ddmanager.prepareOffsets(n, e);
                },
            }),
            t.ui.plugin.add("draggable", "snap", {
                start: function (e, i, n) {
                    var s = n.options;
                    (n.snapElements = []),
                        t(s.snap.constructor !== String ? s.snap.items || ":data(ui-draggable)" : s.snap).each(function () {
                            var e = t(this),
                                i = e.offset();
                            this !== n.element[0] && n.snapElements.push({ item: this, width: e.outerWidth(), height: e.outerHeight(), top: i.top, left: i.left });
                        });
                },
                drag: function (e, i, n) {
                    for (
                        var s, o, r, a, l, h, c, u, d, p = n.options, f = p.snapTolerance, g = i.offset.left, m = g + n.helperProportions.width, v = i.offset.top, _ = v + n.helperProportions.height, b = n.snapElements.length - 1;
                        0 <= b;
                        b--
                    )
                        (h = (l = n.snapElements[b].left - n.margins.left) + n.snapElements[b].width),
                            (u = (c = n.snapElements[b].top - n.margins.top) + n.snapElements[b].height),
                            m < l - f || h + f < g || _ < c - f || u + f < v || !t.contains(n.snapElements[b].item.ownerDocument, n.snapElements[b].item)
                                ? (n.snapElements[b].snapping && n.options.snap.release && n.options.snap.release.call(n.element, e, t.extend(n._uiHash(), { snapItem: n.snapElements[b].item })), (n.snapElements[b].snapping = !1))
                                : ("inner" !== p.snapMode &&
                                      ((s = Math.abs(c - _) <= f),
                                      (o = Math.abs(u - v) <= f),
                                      (r = Math.abs(l - m) <= f),
                                      (a = Math.abs(h - g) <= f),
                                      s && (i.position.top = n._convertPositionTo("relative", { top: c - n.helperProportions.height, left: 0 }).top),
                                      o && (i.position.top = n._convertPositionTo("relative", { top: u, left: 0 }).top),
                                      r && (i.position.left = n._convertPositionTo("relative", { top: 0, left: l - n.helperProportions.width }).left),
                                      a && (i.position.left = n._convertPositionTo("relative", { top: 0, left: h }).left)),
                                  (d = s || o || r || a),
                                  "outer" !== p.snapMode &&
                                      ((s = Math.abs(c - v) <= f),
                                      (o = Math.abs(u - _) <= f),
                                      (r = Math.abs(l - g) <= f),
                                      (a = Math.abs(h - m) <= f),
                                      s && (i.position.top = n._convertPositionTo("relative", { top: c, left: 0 }).top),
                                      o && (i.position.top = n._convertPositionTo("relative", { top: u - n.helperProportions.height, left: 0 }).top),
                                      r && (i.position.left = n._convertPositionTo("relative", { top: 0, left: l }).left),
                                      a && (i.position.left = n._convertPositionTo("relative", { top: 0, left: h - n.helperProportions.width }).left)),
                                  !n.snapElements[b].snapping && (s || o || r || a || d) && n.options.snap.snap && n.options.snap.snap.call(n.element, e, t.extend(n._uiHash(), { snapItem: n.snapElements[b].item })),
                                  (n.snapElements[b].snapping = s || o || r || a || d));
                },
            }),
            t.ui.plugin.add("draggable", "stack", {
                start: function (e, i, n) {
                    var s;
                    (n = n.options),
                        (n = t.makeArray(t(n.stack)).sort(function (e, i) {
                            return (parseInt(t(e).css("zIndex"), 10) || 0) - (parseInt(t(i).css("zIndex"), 10) || 0);
                        }));
                    n.length &&
                        ((s = parseInt(t(n[0]).css("zIndex"), 10) || 0),
                        t(n).each(function (e) {
                            t(this).css("zIndex", s + e);
                        }),
                        this.css("zIndex", s + n.length));
                },
            }),
            t.ui.plugin.add("draggable", "zIndex", {
                start: function (e, i, n) {
                    (i = t(i.helper)), (n = n.options), i.css("zIndex") && (n._zIndex = i.css("zIndex")), i.css("zIndex", n.zIndex);
                },
                stop: function (e, i, n) {
                    (n = n.options)._zIndex && t(i.helper).css("zIndex", n._zIndex);
                },
            }),
            t.ui.draggable,
            t.widget("ui.resizable", t.ui.mouse, {
                version: "1.13.2",
                widgetEventPrefix: "resize",
                options: {
                    alsoResize: !1,
                    animate: !1,
                    animateDuration: "slow",
                    animateEasing: "swing",
                    aspectRatio: !1,
                    autoHide: !1,
                    classes: { "ui-resizable-se": "ui-icon ui-icon-gripsmall-diagonal-se" },
                    containment: !1,
                    ghost: !1,
                    grid: !1,
                    handles: "e,s,se",
                    helper: !1,
                    maxHeight: null,
                    maxWidth: null,
                    minHeight: 10,
                    minWidth: 10,
                    zIndex: 90,
                    resize: null,
                    start: null,
                    stop: null,
                },
                _num: function (t) {
                    return parseFloat(t) || 0;
                },
                _isNumber: function (t) {
                    return !isNaN(parseFloat(t));
                },
                _hasScroll: function (e, i) {
                    if ("hidden" === t(e).css("overflow")) return !1;
                    var n = i && "left" === i ? "scrollLeft" : "scrollTop";
                    i = !1;
                    if (0 < e[n]) return !0;
                    try {
                        (e[n] = 1), (i = 0 < e[n]), (e[n] = 0);
                    } catch (e) {}
                    return i;
                },
                _create: function () {
                    var e,
                        i = this.options,
                        n = this;
                    this._addClass("ui-resizable"),
                        t.extend(this, {
                            _aspectRatio: !!i.aspectRatio,
                            aspectRatio: i.aspectRatio,
                            originalElement: this.element,
                            _proportionallyResizeElements: [],
                            _helper: i.helper || i.ghost || i.animate ? i.helper || "ui-resizable-helper" : null,
                        }),
                        this.element[0].nodeName.match(/^(canvas|textarea|input|select|button|img)$/i) &&
                            (this.element.wrap(
                                t("<div class='ui-wrapper'></div>").css({
                                    overflow: "hidden",
                                    position: this.element.css("position"),
                                    width: this.element.outerWidth(),
                                    height: this.element.outerHeight(),
                                    top: this.element.css("top"),
                                    left: this.element.css("left"),
                                })
                            ),
                            (this.element = this.element.parent().data("ui-resizable", this.element.resizable("instance"))),
                            (this.elementIsWrapper = !0),
                            (e = {
                                marginTop: this.originalElement.css("marginTop"),
                                marginRight: this.originalElement.css("marginRight"),
                                marginBottom: this.originalElement.css("marginBottom"),
                                marginLeft: this.originalElement.css("marginLeft"),
                            }),
                            this.element.css(e),
                            this.originalElement.css("margin", 0),
                            (this.originalResizeStyle = this.originalElement.css("resize")),
                            this.originalElement.css("resize", "none"),
                            this._proportionallyResizeElements.push(this.originalElement.css({ position: "static", zoom: 1, display: "block" })),
                            this.originalElement.css(e),
                            this._proportionallyResize()),
                        this._setupHandles(),
                        i.autoHide &&
                            t(this.element)
                                .on("mouseenter", function () {
                                    i.disabled || (n._removeClass("ui-resizable-autohide"), n._handles.show());
                                })
                                .on("mouseleave", function () {
                                    i.disabled || n.resizing || (n._addClass("ui-resizable-autohide"), n._handles.hide());
                                }),
                        this._mouseInit();
                },
                _destroy: function () {
                    function e(e) {
                        t(e).removeData("resizable").removeData("ui-resizable").off(".resizable");
                    }
                    var i;
                    return (
                        this._mouseDestroy(),
                        this._addedHandles.remove(),
                        this.elementIsWrapper &&
                            (e(this.element), (i = this.element), this.originalElement.css({ position: i.css("position"), width: i.outerWidth(), height: i.outerHeight(), top: i.css("top"), left: i.css("left") }).insertAfter(i), i.remove()),
                        this.originalElement.css("resize", this.originalResizeStyle),
                        e(this.originalElement),
                        this
                    );
                },
                _setOption: function (t, e) {
                    switch ((this._super(t, e), t)) {
                        case "handles":
                            this._removeHandles(), this._setupHandles();
                            break;
                        case "aspectRatio":
                            this._aspectRatio = !!e;
                    }
                },
                _setupHandles: function () {
                    var e,
                        i,
                        n,
                        s,
                        o,
                        r = this.options,
                        a = this;
                    if (
                        ((this.handles =
                            r.handles ||
                            (t(".ui-resizable-handle", this.element).length
                                ? { n: ".ui-resizable-n", e: ".ui-resizable-e", s: ".ui-resizable-s", w: ".ui-resizable-w", se: ".ui-resizable-se", sw: ".ui-resizable-sw", ne: ".ui-resizable-ne", nw: ".ui-resizable-nw" }
                                : "e,s,se")),
                        (this._handles = t()),
                        (this._addedHandles = t()),
                        this.handles.constructor === String)
                    )
                        for ("all" === this.handles && (this.handles = "n,e,s,w,se,sw,ne,nw"), n = this.handles.split(","), this.handles = {}, i = 0; i < n.length; i++)
                            (s = "ui-resizable-" + (e = String.prototype.trim.call(n[i]))),
                                (o = t("<div>")),
                                this._addClass(o, "ui-resizable-handle " + s),
                                o.css({ zIndex: r.zIndex }),
                                (this.handles[e] = ".ui-resizable-" + e),
                                this.element.children(this.handles[e]).length || (this.element.append(o), (this._addedHandles = this._addedHandles.add(o)));
                    (this._renderAxis = function (e) {
                        var i, n, s;
                        for (i in ((e = e || this.element), this.handles))
                            this.handles[i].constructor === String
                                ? (this.handles[i] = this.element.children(this.handles[i]).first().show())
                                : (this.handles[i].jquery || this.handles[i].nodeType) && ((this.handles[i] = t(this.handles[i])), this._on(this.handles[i], { mousedown: a._mouseDown })),
                                this.elementIsWrapper &&
                                    this.originalElement[0].nodeName.match(/^(textarea|input|select|button)$/i) &&
                                    ((n = t(this.handles[i], this.element)),
                                    (s = /sw|ne|nw|se|n|s/.test(i) ? n.outerHeight() : n.outerWidth()),
                                    (n = ["padding", /ne|nw|n/.test(i) ? "Top" : /se|sw|s/.test(i) ? "Bottom" : /^e$/.test(i) ? "Right" : "Left"].join("")),
                                    e.css(n, s),
                                    this._proportionallyResize()),
                                (this._handles = this._handles.add(this.handles[i]));
                    }),
                        this._renderAxis(this.element),
                        (this._handles = this._handles.add(this.element.find(".ui-resizable-handle"))),
                        this._handles.disableSelection(),
                        this._handles.on("mouseover", function () {
                            a.resizing || (this.className && (o = this.className.match(/ui-resizable-(se|sw|ne|nw|n|e|s|w)/i)), (a.axis = o && o[1] ? o[1] : "se"));
                        }),
                        r.autoHide && (this._handles.hide(), this._addClass("ui-resizable-autohide"));
                },
                _removeHandles: function () {
                    this._addedHandles.remove();
                },
                _mouseCapture: function (e) {
                    var i,
                        n,
                        s = !1;
                    for (i in this.handles) ((n = t(this.handles[i])[0]) !== e.target && !t.contains(n, e.target)) || (s = !0);
                    return !this.options.disabled && s;
                },
                _mouseStart: function (e) {
                    var i,
                        n,
                        s = this.options,
                        o = this.element;
                    return (
                        (this.resizing = !0),
                        this._renderProxy(),
                        (i = this._num(this.helper.css("left"))),
                        (n = this._num(this.helper.css("top"))),
                        s.containment && ((i += t(s.containment).scrollLeft() || 0), (n += t(s.containment).scrollTop() || 0)),
                        (this.offset = this.helper.offset()),
                        (this.position = { left: i, top: n }),
                        (this.size = this._helper ? { width: this.helper.width(), height: this.helper.height() } : { width: o.width(), height: o.height() }),
                        (this.originalSize = this._helper ? { width: o.outerWidth(), height: o.outerHeight() } : { width: o.width(), height: o.height() }),
                        (this.sizeDiff = { width: o.outerWidth() - o.width(), height: o.outerHeight() - o.height() }),
                        (this.originalPosition = { left: i, top: n }),
                        (this.originalMousePosition = { left: e.pageX, top: e.pageY }),
                        (this.aspectRatio = "number" == typeof s.aspectRatio ? s.aspectRatio : this.originalSize.width / this.originalSize.height || 1),
                        (s = t(".ui-resizable-" + this.axis).css("cursor")),
                        t("body").css("cursor", "auto" === s ? this.axis + "-resize" : s),
                        this._addClass("ui-resizable-resizing"),
                        this._propagate("start", e),
                        !0
                    );
                },
                _mouseDrag: function (e) {
                    var i = this.originalMousePosition,
                        n = this.axis,
                        s = e.pageX - i.left || 0;
                    (i = e.pageY - i.top || 0), (n = this._change[n]);
                    return (
                        this._updatePrevProperties(),
                        n &&
                            ((i = n.apply(this, [e, s, i])),
                            this._updateVirtualBoundaries(e.shiftKey),
                            (this._aspectRatio || e.shiftKey) && (i = this._updateRatio(i, e)),
                            (i = this._respectSize(i, e)),
                            this._updateCache(i),
                            this._propagate("resize", e),
                            (i = this._applyChanges()),
                            !this._helper && this._proportionallyResizeElements.length && this._proportionallyResize(),
                            t.isEmptyObject(i) || (this._updatePrevProperties(), this._trigger("resize", e, this.ui()), this._applyChanges())),
                        !1
                    );
                },
                _mouseStop: function (e) {
                    this.resizing = !1;
                    var i,
                        n,
                        s,
                        o = this.options,
                        r = this;
                    return (
                        this._helper &&
                            ((s = (i = (n = this._proportionallyResizeElements).length && /textarea/i.test(n[0].nodeName)) && this._hasScroll(n[0], "left") ? 0 : r.sizeDiff.height),
                            (n = i ? 0 : r.sizeDiff.width),
                            (i = { width: r.helper.width() - n, height: r.helper.height() - s }),
                            (n = parseFloat(r.element.css("left")) + (r.position.left - r.originalPosition.left) || null),
                            (s = parseFloat(r.element.css("top")) + (r.position.top - r.originalPosition.top) || null),
                            o.animate || this.element.css(t.extend(i, { top: s, left: n })),
                            r.helper.height(r.size.height),
                            r.helper.width(r.size.width),
                            this._helper && !o.animate && this._proportionallyResize()),
                        t("body").css("cursor", "auto"),
                        this._removeClass("ui-resizable-resizing"),
                        this._propagate("stop", e),
                        this._helper && this.helper.remove(),
                        !1
                    );
                },
                _updatePrevProperties: function () {
                    (this.prevPosition = { top: this.position.top, left: this.position.left }), (this.prevSize = { width: this.size.width, height: this.size.height });
                },
                _applyChanges: function () {
                    var t = {};
                    return (
                        this.position.top !== this.prevPosition.top && (t.top = this.position.top + "px"),
                        this.position.left !== this.prevPosition.left && (t.left = this.position.left + "px"),
                        this.size.width !== this.prevSize.width && (t.width = this.size.width + "px"),
                        this.size.height !== this.prevSize.height && (t.height = this.size.height + "px"),
                        this.helper.css(t),
                        t
                    );
                },
                _updateVirtualBoundaries: function (t) {
                    var e,
                        i,
                        n = this.options,
                        s = {
                            minWidth: this._isNumber(n.minWidth) ? n.minWidth : 0,
                            maxWidth: this._isNumber(n.maxWidth) ? n.maxWidth : 1 / 0,
                            minHeight: this._isNumber(n.minHeight) ? n.minHeight : 0,
                            maxHeight: this._isNumber(n.maxHeight) ? n.maxHeight : 1 / 0,
                        };
                    (this._aspectRatio || t) &&
                        ((e = s.minHeight * this.aspectRatio),
                        (i = s.minWidth / this.aspectRatio),
                        (n = s.maxHeight * this.aspectRatio),
                        (t = s.maxWidth / this.aspectRatio),
                        e > s.minWidth && (s.minWidth = e),
                        i > s.minHeight && (s.minHeight = i),
                        n < s.maxWidth && (s.maxWidth = n),
                        t < s.maxHeight && (s.maxHeight = t)),
                        (this._vBoundaries = s);
                },
                _updateCache: function (t) {
                    (this.offset = this.helper.offset()),
                        this._isNumber(t.left) && (this.position.left = t.left),
                        this._isNumber(t.top) && (this.position.top = t.top),
                        this._isNumber(t.height) && (this.size.height = t.height),
                        this._isNumber(t.width) && (this.size.width = t.width);
                },
                _updateRatio: function (t) {
                    var e = this.position,
                        i = this.size,
                        n = this.axis;
                    return (
                        this._isNumber(t.height) ? (t.width = t.height * this.aspectRatio) : this._isNumber(t.width) && (t.height = t.width / this.aspectRatio),
                        "sw" === n && ((t.left = e.left + (i.width - t.width)), (t.top = null)),
                        "nw" === n && ((t.top = e.top + (i.height - t.height)), (t.left = e.left + (i.width - t.width))),
                        t
                    );
                },
                _respectSize: function (t) {
                    var e = this._vBoundaries,
                        i = this.axis,
                        n = this._isNumber(t.width) && e.maxWidth && e.maxWidth < t.width,
                        s = this._isNumber(t.height) && e.maxHeight && e.maxHeight < t.height,
                        o = this._isNumber(t.width) && e.minWidth && e.minWidth > t.width,
                        r = this._isNumber(t.height) && e.minHeight && e.minHeight > t.height,
                        a = this.originalPosition.left + this.originalSize.width,
                        l = this.originalPosition.top + this.originalSize.height,
                        h = /sw|nw|w/.test(i);
                    i = /nw|ne|n/.test(i);
                    return (
                        o && (t.width = e.minWidth),
                        r && (t.height = e.minHeight),
                        n && (t.width = e.maxWidth),
                        s && (t.height = e.maxHeight),
                        o && h && (t.left = a - e.minWidth),
                        n && h && (t.left = a - e.maxWidth),
                        r && i && (t.top = l - e.minHeight),
                        s && i && (t.top = l - e.maxHeight),
                        t.width || t.height || t.left || !t.top ? t.width || t.height || t.top || !t.left || (t.left = null) : (t.top = null),
                        t
                    );
                },
                _getPaddingPlusBorderDimensions: function (t) {
                    for (
                        var e = 0,
                            i = [],
                            n = [t.css("borderTopWidth"), t.css("borderRightWidth"), t.css("borderBottomWidth"), t.css("borderLeftWidth")],
                            s = [t.css("paddingTop"), t.css("paddingRight"), t.css("paddingBottom"), t.css("paddingLeft")];
                        e < 4;
                        e++
                    )
                        (i[e] = parseFloat(n[e]) || 0), (i[e] += parseFloat(s[e]) || 0);
                    return { height: i[0] + i[2], width: i[1] + i[3] };
                },
                _proportionallyResize: function () {
                    if (this._proportionallyResizeElements.length)
                        for (var t, e = 0, i = this.helper || this.element; e < this._proportionallyResizeElements.length; e++)
                            (t = this._proportionallyResizeElements[e]),
                                this.outerDimensions || (this.outerDimensions = this._getPaddingPlusBorderDimensions(t)),
                                t.css({ height: i.height() - this.outerDimensions.height || 0, width: i.width() - this.outerDimensions.width || 0 });
                },
                _renderProxy: function () {
                    var e = this.element,
                        i = this.options;
                    (this.elementOffset = e.offset()),
                        this._helper
                            ? ((this.helper = this.helper || t("<div></div>").css({ overflow: "hidden" })),
                              this._addClass(this.helper, this._helper),
                              this.helper.css({ width: this.element.outerWidth(), height: this.element.outerHeight(), position: "absolute", left: this.elementOffset.left + "px", top: this.elementOffset.top + "px", zIndex: ++i.zIndex }),
                              this.helper.appendTo("body").disableSelection())
                            : (this.helper = this.element);
                },
                _change: {
                    e: function (t, e) {
                        return { width: this.originalSize.width + e };
                    },
                    w: function (t, e) {
                        var i = this.originalSize;
                        return { left: this.originalPosition.left + e, width: i.width - e };
                    },
                    n: function (t, e, i) {
                        var n = this.originalSize;
                        return { top: this.originalPosition.top + i, height: n.height - i };
                    },
                    s: function (t, e, i) {
                        return { height: this.originalSize.height + i };
                    },
                    se: function (e, i, n) {
                        return t.extend(this._change.s.apply(this, arguments), this._change.e.apply(this, [e, i, n]));
                    },
                    sw: function (e, i, n) {
                        return t.extend(this._change.s.apply(this, arguments), this._change.w.apply(this, [e, i, n]));
                    },
                    ne: function (e, i, n) {
                        return t.extend(this._change.n.apply(this, arguments), this._change.e.apply(this, [e, i, n]));
                    },
                    nw: function (e, i, n) {
                        return t.extend(this._change.n.apply(this, arguments), this._change.w.apply(this, [e, i, n]));
                    },
                },
                _propagate: function (e, i) {
                    t.ui.plugin.call(this, e, [i, this.ui()]), "resize" !== e && this._trigger(e, i, this.ui());
                },
                plugins: {},
                ui: function () {
                    return { originalElement: this.originalElement, element: this.element, helper: this.helper, position: this.position, size: this.size, originalSize: this.originalSize, originalPosition: this.originalPosition };
                },
            }),
            t.ui.plugin.add("resizable", "animate", {
                stop: function (e) {
                    var i = t(this).resizable("instance"),
                        n = i.options,
                        s = i._proportionallyResizeElements,
                        o = (a = s.length && /textarea/i.test(s[0].nodeName)) && i._hasScroll(s[0], "left") ? 0 : i.sizeDiff.height,
                        r = a ? 0 : i.sizeDiff.width,
                        a = { width: i.size.width - r, height: i.size.height - o };
                    (r = parseFloat(i.element.css("left")) + (i.position.left - i.originalPosition.left) || null), (o = parseFloat(i.element.css("top")) + (i.position.top - i.originalPosition.top) || null);
                    i.element.animate(t.extend(a, o && r ? { top: o, left: r } : {}), {
                        duration: n.animateDuration,
                        easing: n.animateEasing,
                        step: function () {
                            var n = { width: parseFloat(i.element.css("width")), height: parseFloat(i.element.css("height")), top: parseFloat(i.element.css("top")), left: parseFloat(i.element.css("left")) };
                            s && s.length && t(s[0]).css({ width: n.width, height: n.height }), i._updateCache(n), i._propagate("resize", e);
                        },
                    });
                },
            }),
            t.ui.plugin.add("resizable", "containment", {
                start: function () {
                    var e,
                        i,
                        n = t(this).resizable("instance"),
                        s = n.options,
                        o = n.element,
                        r = s.containment,
                        a = r instanceof t ? r.get(0) : /parent/.test(r) ? o.parent().get(0) : r;
                    a &&
                        ((n.containerElement = t(a)),
                        /document/.test(r) || r === document
                            ? ((n.containerOffset = { left: 0, top: 0 }),
                              (n.containerPosition = { left: 0, top: 0 }),
                              (n.parentData = { element: t(document), left: 0, top: 0, width: t(document).width(), height: t(document).height() || document.body.parentNode.scrollHeight }))
                            : ((e = t(a)),
                              (i = []),
                              t(["Top", "Right", "Left", "Bottom"]).each(function (t, s) {
                                  i[t] = n._num(e.css("padding" + s));
                              }),
                              (n.containerOffset = e.offset()),
                              (n.containerPosition = e.position()),
                              (n.containerSize = { height: e.innerHeight() - i[3], width: e.innerWidth() - i[1] }),
                              (s = n.containerOffset),
                              (o = n.containerSize.height),
                              (r = n.containerSize.width),
                              (r = n._hasScroll(a, "left") ? a.scrollWidth : r),
                              (o = n._hasScroll(a) ? a.scrollHeight : o),
                              (n.parentData = { element: a, left: s.left, top: s.top, width: r, height: o })));
                },
                resize: function (e) {
                    var i = t(this).resizable("instance"),
                        n = i.options,
                        s = i.containerOffset,
                        o = i.position,
                        r = i._aspectRatio || e.shiftKey,
                        a = { top: 0, left: 0 },
                        l = i.containerElement;
                    e = !0;
                    l[0] !== document && /static/.test(l.css("position")) && (a = s),
                        o.left < (i._helper ? s.left : 0) &&
                            ((i.size.width = i.size.width + (i._helper ? i.position.left - s.left : i.position.left - a.left)), r && ((i.size.height = i.size.width / i.aspectRatio), (e = !1)), (i.position.left = n.helper ? s.left : 0)),
                        o.top < (i._helper ? s.top : 0) &&
                            ((i.size.height = i.size.height + (i._helper ? i.position.top - s.top : i.position.top)), r && ((i.size.width = i.size.height * i.aspectRatio), (e = !1)), (i.position.top = i._helper ? s.top : 0)),
                        (n = i.containerElement.get(0) === i.element.parent().get(0)),
                        (o = /relative|absolute/.test(i.containerElement.css("position"))),
                        n && o ? ((i.offset.left = i.parentData.left + i.position.left), (i.offset.top = i.parentData.top + i.position.top)) : ((i.offset.left = i.element.offset().left), (i.offset.top = i.element.offset().top)),
                        (o = Math.abs(i.sizeDiff.width + (i._helper ? i.offset.left - a.left : i.offset.left - s.left))),
                        (s = Math.abs(i.sizeDiff.height + (i._helper ? i.offset.top - a.top : i.offset.top - s.top))),
                        o + i.size.width >= i.parentData.width && ((i.size.width = i.parentData.width - o), r && ((i.size.height = i.size.width / i.aspectRatio), (e = !1))),
                        s + i.size.height >= i.parentData.height && ((i.size.height = i.parentData.height - s), r && ((i.size.width = i.size.height * i.aspectRatio), (e = !1))),
                        e || ((i.position.left = i.prevPosition.left), (i.position.top = i.prevPosition.top), (i.size.width = i.prevSize.width), (i.size.height = i.prevSize.height));
                },
                stop: function () {
                    var e = t(this).resizable("instance"),
                        i = e.options,
                        n = e.containerOffset,
                        s = e.containerPosition,
                        o = e.containerElement,
                        r = (l = t(e.helper)).offset(),
                        a = l.outerWidth() - e.sizeDiff.width,
                        l = l.outerHeight() - e.sizeDiff.height;
                    e._helper && !i.animate && /relative/.test(o.css("position")) && t(this).css({ left: r.left - s.left - n.left, width: a, height: l }),
                        e._helper && !i.animate && /static/.test(o.css("position")) && t(this).css({ left: r.left - s.left - n.left, width: a, height: l });
                },
            }),
            t.ui.plugin.add("resizable", "alsoResize", {
                start: function () {
                    var e = t(this).resizable("instance").options;
                    t(e.alsoResize).each(function () {
                        var e = t(this);
                        e.data("ui-resizable-alsoresize", { width: parseFloat(e.width()), height: parseFloat(e.height()), left: parseFloat(e.css("left")), top: parseFloat(e.css("top")) });
                    });
                },
                resize: function (e, i) {
                    var n = t(this).resizable("instance"),
                        s = n.options,
                        o = n.originalSize,
                        r = n.originalPosition,
                        a = { height: n.size.height - o.height || 0, width: n.size.width - o.width || 0, top: n.position.top - r.top || 0, left: n.position.left - r.left || 0 };
                    t(s.alsoResize).each(function () {
                        var e = t(this),
                            n = t(this).data("ui-resizable-alsoresize"),
                            s = {},
                            o = e.parents(i.originalElement[0]).length ? ["width", "height"] : ["width", "height", "top", "left"];
                        t.each(o, function (t, e) {
                            var i = (n[e] || 0) + (a[e] || 0);
                            i && 0 <= i && (s[e] = i || null);
                        }),
                            e.css(s);
                    });
                },
                stop: function () {
                    t(this).removeData("ui-resizable-alsoresize");
                },
            }),
            t.ui.plugin.add("resizable", "ghost", {
                start: function () {
                    var e = t(this).resizable("instance"),
                        i = e.size;
                    (e.ghost = e.originalElement.clone()),
                        e.ghost.css({ opacity: 0.25, display: "block", position: "relative", height: i.height, width: i.width, margin: 0, left: 0, top: 0 }),
                        e._addClass(e.ghost, "ui-resizable-ghost"),
                        !1 !== t.uiBackCompat && "string" == typeof e.options.ghost && e.ghost.addClass(this.options.ghost),
                        e.ghost.appendTo(e.helper);
                },
                resize: function () {
                    var e = t(this).resizable("instance");
                    e.ghost && e.ghost.css({ position: "relative", height: e.size.height, width: e.size.width });
                },
                stop: function () {
                    var e = t(this).resizable("instance");
                    e.ghost && e.helper && e.helper.get(0).removeChild(e.ghost.get(0));
                },
            }),
            t.ui.plugin.add("resizable", "grid", {
                resize: function () {
                    var e,
                        i = t(this).resizable("instance"),
                        n = i.options,
                        s = i.size,
                        o = i.originalSize,
                        r = i.originalPosition,
                        a = i.axis,
                        l = "number" == typeof n.grid ? [n.grid, n.grid] : n.grid,
                        h = l[0] || 1,
                        c = l[1] || 1,
                        u = Math.round((s.width - o.width) / h) * h,
                        d = Math.round((s.height - o.height) / c) * c,
                        p = o.width + u,
                        f = o.height + d,
                        g = n.maxWidth && n.maxWidth < p,
                        m = n.maxHeight && n.maxHeight < f,
                        v = n.minWidth && n.minWidth > p;
                    s = n.minHeight && n.minHeight > f;
                    (n.grid = l),
                        v && (p += h),
                        s && (f += c),
                        g && (p -= h),
                        m && (f -= c),
                        /^(se|s|e)$/.test(a)
                            ? ((i.size.width = p), (i.size.height = f))
                            : /^(ne)$/.test(a)
                            ? ((i.size.width = p), (i.size.height = f), (i.position.top = r.top - d))
                            : /^(sw)$/.test(a)
                            ? ((i.size.width = p), (i.size.height = f), (i.position.left = r.left - u))
                            : ((f - c <= 0 || p - h <= 0) && (e = i._getPaddingPlusBorderDimensions(this)),
                              0 < f - c ? ((i.size.height = f), (i.position.top = r.top - d)) : ((f = c - e.height), (i.size.height = f), (i.position.top = r.top + o.height - f)),
                              0 < p - h ? ((i.size.width = p), (i.position.left = r.left - u)) : ((p = h - e.width), (i.size.width = p), (i.position.left = r.left + o.width - p)));
                },
            }),
            t.ui.resizable,
            t.widget("ui.dialog", {
                version: "1.13.2",
                options: {
                    appendTo: "body",
                    autoOpen: !0,
                    buttons: [],
                    classes: { "ui-dialog": "ui-corner-all", "ui-dialog-titlebar": "ui-corner-all" },
                    closeOnEscape: !0,
                    closeText: "Close",
                    draggable: !0,
                    hide: null,
                    height: "auto",
                    maxHeight: null,
                    maxWidth: null,
                    minHeight: 150,
                    minWidth: 150,
                    modal: !1,
                    position: {
                        my: "center",
                        at: "center",
                        of: window,
                        collision: "fit",
                        using: function (e) {
                            var i = t(this).css(e).offset().top;
                            i < 0 && t(this).css("top", e.top - i);
                        },
                    },
                    resizable: !0,
                    show: null,
                    title: null,
                    width: 300,
                    beforeClose: null,
                    close: null,
                    drag: null,
                    dragStart: null,
                    dragStop: null,
                    focus: null,
                    open: null,
                    resize: null,
                    resizeStart: null,
                    resizeStop: null,
                },
                sizeRelatedOptions: { buttons: !0, height: !0, maxHeight: !0, maxWidth: !0, minHeight: !0, minWidth: !0, width: !0 },
                resizableRelatedOptions: { maxHeight: !0, maxWidth: !0, minHeight: !0, minWidth: !0 },
                _create: function () {
                    (this.originalCss = {
                        display: this.element[0].style.display,
                        width: this.element[0].style.width,
                        minHeight: this.element[0].style.minHeight,
                        maxHeight: this.element[0].style.maxHeight,
                        height: this.element[0].style.height,
                    }),
                        (this.originalPosition = { parent: this.element.parent(), index: this.element.parent().children().index(this.element) }),
                        (this.originalTitle = this.element.attr("title")),
                        null == this.options.title && null != this.originalTitle && (this.options.title = this.originalTitle),
                        this.options.disabled && (this.options.disabled = !1),
                        this._createWrapper(),
                        this.element.show().removeAttr("title").appendTo(this.uiDialog),
                        this._addClass("ui-dialog-content", "ui-widget-content"),
                        this._createTitlebar(),
                        this._createButtonPane(),
                        this.options.draggable && t.fn.draggable && this._makeDraggable(),
                        this.options.resizable && t.fn.resizable && this._makeResizable(),
                        (this._isOpen = !1),
                        this._trackFocus();
                },
                _init: function () {
                    this.options.autoOpen && this.open();
                },
                _appendTo: function () {
                    var e = this.options.appendTo;
                    return e && (e.jquery || e.nodeType) ? t(e) : this.document.find(e || "body").eq(0);
                },
                _destroy: function () {
                    var t,
                        e = this.originalPosition;
                    this._untrackInstance(),
                        this._destroyOverlay(),
                        this.element.removeUniqueId().css(this.originalCss).detach(),
                        this.uiDialog.remove(),
                        this.originalTitle && this.element.attr("title", this.originalTitle),
                        (t = e.parent.children().eq(e.index)).length && t[0] !== this.element[0] ? t.before(this.element) : e.parent.append(this.element);
                },
                widget: function () {
                    return this.uiDialog;
                },
                disable: t.noop,
                enable: t.noop,
                close: function (e) {
                    var i = this;
                    this._isOpen &&
                        !1 !== this._trigger("beforeClose", e) &&
                        ((this._isOpen = !1),
                        (this._focusedElement = null),
                        this._destroyOverlay(),
                        this._untrackInstance(),
                        this.opener.filter(":focusable").trigger("focus").length || t.ui.safeBlur(t.ui.safeActiveElement(this.document[0])),
                        this._hide(this.uiDialog, this.options.hide, function () {
                            i._trigger("close", e);
                        }));
                },
                isOpen: function () {
                    return this._isOpen;
                },
                moveToTop: function () {
                    this._moveToTop();
                },
                _moveToTop: function (e, i) {
                    var n = !1,
                        s = this.uiDialog
                            .siblings(".ui-front:visible")
                            .map(function () {
                                return +t(this).css("z-index");
                            })
                            .get();
                    return (s = Math.max.apply(null, s)) >= +this.uiDialog.css("z-index") && (this.uiDialog.css("z-index", s + 1), (n = !0)), n && !i && this._trigger("focus", e), n;
                },
                open: function () {
                    var e = this;
                    this._isOpen
                        ? this._moveToTop() && this._focusTabbable()
                        : ((this._isOpen = !0),
                          (this.opener = t(t.ui.safeActiveElement(this.document[0]))),
                          this._size(),
                          this._position(),
                          this._createOverlay(),
                          this._moveToTop(null, !0),
                          this.overlay && this.overlay.css("z-index", this.uiDialog.css("z-index") - 1),
                          this._show(this.uiDialog, this.options.show, function () {
                              e._focusTabbable(), e._trigger("focus");
                          }),
                          this._makeFocusTarget(),
                          this._trigger("open"));
                },
                _focusTabbable: function () {
                    var t = this._focusedElement;
                    (t = (t = (t = (t = (t = t || this.element.find("[autofocus]")).length ? t : this.element.find(":tabbable")).length ? t : this.uiDialogButtonPane.find(":tabbable")).length
                        ? t
                        : this.uiDialogTitlebarClose.filter(":tabbable")).length
                        ? t
                        : this.uiDialog)
                        .eq(0)
                        .trigger("focus");
                },
                _restoreTabbableFocus: function () {
                    var e = t.ui.safeActiveElement(this.document[0]);
                    this.uiDialog[0] === e || t.contains(this.uiDialog[0], e) || this._focusTabbable();
                },
                _keepFocus: function (t) {
                    t.preventDefault(), this._restoreTabbableFocus(), this._delay(this._restoreTabbableFocus);
                },
                _createWrapper: function () {
                    (this.uiDialog = t("<div>").hide().attr({ tabIndex: -1, role: "dialog" }).appendTo(this._appendTo())),
                        this._addClass(this.uiDialog, "ui-dialog", "ui-widget ui-widget-content ui-front"),
                        this._on(this.uiDialog, {
                            keydown: function (e) {
                                if (this.options.closeOnEscape && !e.isDefaultPrevented() && e.keyCode && e.keyCode === t.ui.keyCode.ESCAPE) return e.preventDefault(), void this.close(e);
                                var i, n, s;
                                e.keyCode !== t.ui.keyCode.TAB ||
                                    e.isDefaultPrevented() ||
                                    ((i = this.uiDialog.find(":tabbable")),
                                    (n = i.first()),
                                    (s = i.last()),
                                    (e.target !== s[0] && e.target !== this.uiDialog[0]) || e.shiftKey
                                        ? (e.target !== n[0] && e.target !== this.uiDialog[0]) ||
                                          !e.shiftKey ||
                                          (this._delay(function () {
                                              s.trigger("focus");
                                          }),
                                          e.preventDefault())
                                        : (this._delay(function () {
                                              n.trigger("focus");
                                          }),
                                          e.preventDefault()));
                            },
                            mousedown: function (t) {
                                this._moveToTop(t) && this._focusTabbable();
                            },
                        }),
                        this.element.find("[aria-describedby]").length || this.uiDialog.attr({ "aria-describedby": this.element.uniqueId().attr("id") });
                },
                _createTitlebar: function () {
                    var e;
                    (this.uiDialogTitlebar = t("<div>")),
                        this._addClass(this.uiDialogTitlebar, "ui-dialog-titlebar", "ui-widget-header ui-helper-clearfix"),
                        this._on(this.uiDialogTitlebar, {
                            mousedown: function (e) {
                                t(e.target).closest(".ui-dialog-titlebar-close") || this.uiDialog.trigger("focus");
                            },
                        }),
                        (this.uiDialogTitlebarClose = t("<button type='button'></button>")
                            .button({ label: t("<a>").text(this.options.closeText).html(), icon: "ui-icon-closethick", showLabel: !1 })
                            .appendTo(this.uiDialogTitlebar)),
                        this._addClass(this.uiDialogTitlebarClose, "ui-dialog-titlebar-close"),
                        this._on(this.uiDialogTitlebarClose, {
                            click: function (t) {
                                t.preventDefault(), this.close(t);
                            },
                        }),
                        (e = t("<span>").uniqueId().prependTo(this.uiDialogTitlebar)),
                        this._addClass(e, "ui-dialog-title"),
                        this._title(e),
                        this.uiDialogTitlebar.prependTo(this.uiDialog),
                        this.uiDialog.attr({ "aria-labelledby": e.attr("id") });
                },
                _title: function (t) {
                    this.options.title ? t.text(this.options.title) : t.html("&#160;");
                },
                _createButtonPane: function () {
                    (this.uiDialogButtonPane = t("<div>")),
                        this._addClass(this.uiDialogButtonPane, "ui-dialog-buttonpane", "ui-widget-content ui-helper-clearfix"),
                        (this.uiButtonSet = t("<div>").appendTo(this.uiDialogButtonPane)),
                        this._addClass(this.uiButtonSet, "ui-dialog-buttonset"),
                        this._createButtons();
                },
                _createButtons: function () {
                    var e = this,
                        i = this.options.buttons;
                    this.uiDialogButtonPane.remove(),
                        this.uiButtonSet.empty(),
                        t.isEmptyObject(i) || (Array.isArray(i) && !i.length)
                            ? this._removeClass(this.uiDialog, "ui-dialog-buttons")
                            : (t.each(i, function (i, n) {
                                  var s;
                                  (n = t.extend({ type: "button" }, (n = "function" == typeof n ? { click: n, text: i } : n))),
                                      (s = n.click),
                                      (i = { icon: n.icon, iconPosition: n.iconPosition, showLabel: n.showLabel, icons: n.icons, text: n.text }),
                                      delete n.click,
                                      delete n.icon,
                                      delete n.iconPosition,
                                      delete n.showLabel,
                                      delete n.icons,
                                      "boolean" == typeof n.text && delete n.text,
                                      t("<button></button>", n)
                                          .button(i)
                                          .appendTo(e.uiButtonSet)
                                          .on("click", function () {
                                              s.apply(e.element[0], arguments);
                                          });
                              }),
                              this._addClass(this.uiDialog, "ui-dialog-buttons"),
                              this.uiDialogButtonPane.appendTo(this.uiDialog));
                },
                _makeDraggable: function () {
                    var e = this,
                        i = this.options;
                    function n(t) {
                        return { position: t.position, offset: t.offset };
                    }
                    this.uiDialog.draggable({
                        cancel: ".ui-dialog-content, .ui-dialog-titlebar-close",
                        handle: ".ui-dialog-titlebar",
                        containment: "document",
                        start: function (i, s) {
                            e._addClass(t(this), "ui-dialog-dragging"), e._blockFrames(), e._trigger("dragStart", i, n(s));
                        },
                        drag: function (t, i) {
                            e._trigger("drag", t, n(i));
                        },
                        stop: function (s, o) {
                            var r = o.offset.left - e.document.scrollLeft(),
                                a = o.offset.top - e.document.scrollTop();
                            (i.position = { my: "left top", at: "left" + (0 <= r ? "+" : "") + r + " top" + (0 <= a ? "+" : "") + a, of: e.window }),
                                e._removeClass(t(this), "ui-dialog-dragging"),
                                e._unblockFrames(),
                                e._trigger("dragStop", s, n(o));
                        },
                    });
                },
                _makeResizable: function () {
                    var e = this,
                        i = this.options,
                        n = i.resizable,
                        s = this.uiDialog.css("position");
                    n = "string" == typeof n ? n : "n,e,s,w,se,sw,ne,nw";
                    function o(t) {
                        return { originalPosition: t.originalPosition, originalSize: t.originalSize, position: t.position, size: t.size };
                    }
                    this.uiDialog
                        .resizable({
                            cancel: ".ui-dialog-content",
                            containment: "document",
                            alsoResize: this.element,
                            maxWidth: i.maxWidth,
                            maxHeight: i.maxHeight,
                            minWidth: i.minWidth,
                            minHeight: this._minHeight(),
                            handles: n,
                            start: function (i, n) {
                                e._addClass(t(this), "ui-dialog-resizing"), e._blockFrames(), e._trigger("resizeStart", i, o(n));
                            },
                            resize: function (t, i) {
                                e._trigger("resize", t, o(i));
                            },
                            stop: function (n, s) {
                                var r = (a = e.uiDialog.offset()).left - e.document.scrollLeft(),
                                    a = a.top - e.document.scrollTop();
                                (i.height = e.uiDialog.height()),
                                    (i.width = e.uiDialog.width()),
                                    (i.position = { my: "left top", at: "left" + (0 <= r ? "+" : "") + r + " top" + (0 <= a ? "+" : "") + a, of: e.window }),
                                    e._removeClass(t(this), "ui-dialog-resizing"),
                                    e._unblockFrames(),
                                    e._trigger("resizeStop", n, o(s));
                            },
                        })
                        .css("position", s);
                },
                _trackFocus: function () {
                    this._on(this.widget(), {
                        focusin: function (e) {
                            this._makeFocusTarget(), (this._focusedElement = t(e.target));
                        },
                    });
                },
                _makeFocusTarget: function () {
                    this._untrackInstance(), this._trackingInstances().unshift(this);
                },
                _untrackInstance: function () {
                    var e = this._trackingInstances(),
                        i = t.inArray(this, e);
                    -1 !== i && e.splice(i, 1);
                },
                _trackingInstances: function () {
                    var t = this.document.data("ui-dialog-instances");
                    return t || this.document.data("ui-dialog-instances", (t = [])), t;
                },
                _minHeight: function () {
                    var t = this.options;
                    return "auto" === t.height ? t.minHeight : Math.min(t.minHeight, t.height);
                },
                _position: function () {
                    var t = this.uiDialog.is(":visible");
                    t || this.uiDialog.show(), this.uiDialog.position(this.options.position), t || this.uiDialog.hide();
                },
                _setOptions: function (e) {
                    var i = this,
                        n = !1,
                        s = {};
                    t.each(e, function (t, e) {
                        i._setOption(t, e), t in i.sizeRelatedOptions && (n = !0), t in i.resizableRelatedOptions && (s[t] = e);
                    }),
                        n && (this._size(), this._position()),
                        this.uiDialog.is(":data(ui-resizable)") && this.uiDialog.resizable("option", s);
                },
                _setOption: function (e, i) {
                    var n,
                        s = this.uiDialog;
                    "disabled" !== e &&
                        (this._super(e, i),
                        "appendTo" === e && this.uiDialog.appendTo(this._appendTo()),
                        "buttons" === e && this._createButtons(),
                        "closeText" === e &&
                            this.uiDialogTitlebarClose.button({
                                label: t("<a>")
                                    .text("" + this.options.closeText)
                                    .html(),
                            }),
                        "draggable" === e && ((n = s.is(":data(ui-draggable)")) && !i && s.draggable("destroy"), !n && i && this._makeDraggable()),
                        "position" === e && this._position(),
                        "resizable" === e && ((n = s.is(":data(ui-resizable)")) && !i && s.resizable("destroy"), n && "string" == typeof i && s.resizable("option", "handles", i), n || !1 === i || this._makeResizable()),
                        "title" === e && this._title(this.uiDialogTitlebar.find(".ui-dialog-title")));
                },
                _size: function () {
                    var t,
                        e,
                        i,
                        n = this.options;
                    this.element.show().css({ width: "auto", minHeight: 0, maxHeight: "none", height: 0 }),
                        n.minWidth > n.width && (n.width = n.minWidth),
                        (t = this.uiDialog.css({ height: "auto", width: n.width }).outerHeight()),
                        (e = Math.max(0, n.minHeight - t)),
                        (i = "number" == typeof n.maxHeight ? Math.max(0, n.maxHeight - t) : "none"),
                        "auto" === n.height ? this.element.css({ minHeight: e, maxHeight: i, height: "auto" }) : this.element.height(Math.max(0, n.height - t)),
                        this.uiDialog.is(":data(ui-resizable)") && this.uiDialog.resizable("option", "minHeight", this._minHeight());
                },
                _blockFrames: function () {
                    this.iframeBlocks = this.document.find("iframe").map(function () {
                        var e = t(this);
                        return t("<div>").css({ position: "absolute", width: e.outerWidth(), height: e.outerHeight() }).appendTo(e.parent()).offset(e.offset())[0];
                    });
                },
                _unblockFrames: function () {
                    this.iframeBlocks && (this.iframeBlocks.remove(), delete this.iframeBlocks);
                },
                _allowInteraction: function (e) {
                    return !!t(e.target).closest(".ui-dialog").length || !!t(e.target).closest(".ui-datepicker").length;
                },
                _createOverlay: function () {
                    var e, i;
                    this.options.modal &&
                        ((e = t.fn.jquery.substring(0, 4)),
                        (i = !0),
                        this._delay(function () {
                            i = !1;
                        }),
                        this.document.data("ui-dialog-overlays") ||
                            this.document.on(
                                "focusin.ui-dialog",
                                function (t) {
                                    var n;
                                    i || (n = this._trackingInstances()[0])._allowInteraction(t) || (t.preventDefault(), n._focusTabbable(), ("3.4." !== e && "3.5." !== e) || n._delay(n._restoreTabbableFocus));
                                }.bind(this)
                            ),
                        (this.overlay = t("<div>").appendTo(this._appendTo())),
                        this._addClass(this.overlay, null, "ui-widget-overlay ui-front"),
                        this._on(this.overlay, { mousedown: "_keepFocus" }),
                        this.document.data("ui-dialog-overlays", (this.document.data("ui-dialog-overlays") || 0) + 1));
                },
                _destroyOverlay: function () {
                    var t;
                    this.options.modal &&
                        this.overlay &&
                        ((t = this.document.data("ui-dialog-overlays") - 1) ? this.document.data("ui-dialog-overlays", t) : (this.document.off("focusin.ui-dialog"), this.document.removeData("ui-dialog-overlays")),
                        this.overlay.remove(),
                        (this.overlay = null));
                },
            }),
            !1 !== t.uiBackCompat &&
                t.widget("ui.dialog", t.ui.dialog, {
                    options: { dialogClass: "" },
                    _createWrapper: function () {
                        this._super(), this.uiDialog.addClass(this.options.dialogClass);
                    },
                    _setOption: function (t, e) {
                        "dialogClass" === t && this.uiDialog.removeClass(this.options.dialogClass).addClass(e), this._superApply(arguments);
                    },
                }),
            t.ui.dialog,
            t.widget("ui.droppable", {
                version: "1.13.2",
                widgetEventPrefix: "drop",
                options: { accept: "*", addClasses: !0, greedy: !1, scope: "default", tolerance: "intersect", activate: null, deactivate: null, drop: null, out: null, over: null },
                _create: function () {
                    var t,
                        e = this.options,
                        i = e.accept;
                    (this.isover = !1),
                        (this.isout = !0),
                        (this.accept =
                            "function" == typeof i
                                ? i
                                : function (t) {
                                      return t.is(i);
                                  }),
                        (this.proportions = function () {
                            if (!arguments.length) return (t = t || { width: this.element[0].offsetWidth, height: this.element[0].offsetHeight });
                            t = arguments[0];
                        }),
                        this._addToManager(e.scope),
                        e.addClasses && this._addClass("ui-droppable");
                },
                _addToManager: function (e) {
                    (t.ui.ddmanager.droppables[e] = t.ui.ddmanager.droppables[e] || []), t.ui.ddmanager.droppables[e].push(this);
                },
                _splice: function (t) {
                    for (var e = 0; e < t.length; e++) t[e] === this && t.splice(e, 1);
                },
                _destroy: function () {
                    var e = t.ui.ddmanager.droppables[this.options.scope];
                    this._splice(e);
                },
                _setOption: function (e, i) {
                    var n;
                    "accept" === e
                        ? (this.accept =
                              "function" == typeof i
                                  ? i
                                  : function (t) {
                                        return t.is(i);
                                    })
                        : "scope" === e && ((n = t.ui.ddmanager.droppables[this.options.scope]), this._splice(n), this._addToManager(i)),
                        this._super(e, i);
                },
                _activate: function (e) {
                    var i = t.ui.ddmanager.current;
                    this._addActiveClass(), i && this._trigger("activate", e, this.ui(i));
                },
                _deactivate: function (e) {
                    var i = t.ui.ddmanager.current;
                    this._removeActiveClass(), i && this._trigger("deactivate", e, this.ui(i));
                },
                _over: function (e) {
                    var i = t.ui.ddmanager.current;
                    i && (i.currentItem || i.element)[0] !== this.element[0] && this.accept.call(this.element[0], i.currentItem || i.element) && (this._addHoverClass(), this._trigger("over", e, this.ui(i)));
                },
                _out: function (e) {
                    var i = t.ui.ddmanager.current;
                    i && (i.currentItem || i.element)[0] !== this.element[0] && this.accept.call(this.element[0], i.currentItem || i.element) && (this._removeHoverClass(), this._trigger("out", e, this.ui(i)));
                },
                _drop: function (e, i) {
                    var n = i || t.ui.ddmanager.current,
                        s = !1;
                    return (
                        !(!n || (n.currentItem || n.element)[0] === this.element[0]) &&
                        (this.element
                            .find(":data(ui-droppable)")
                            .not(".ui-draggable-dragging")
                            .each(function () {
                                var i = t(this).droppable("instance");
                                if (
                                    i.options.greedy &&
                                    !i.options.disabled &&
                                    i.options.scope === n.options.scope &&
                                    i.accept.call(i.element[0], n.currentItem || n.element) &&
                                    t.ui.intersect(n, t.extend(i, { offset: i.element.offset() }), i.options.tolerance, e)
                                )
                                    return !(s = !0);
                            }),
                        !s && !!this.accept.call(this.element[0], n.currentItem || n.element) && (this._removeActiveClass(), this._removeHoverClass(), this._trigger("drop", e, this.ui(n)), this.element))
                    );
                },
                ui: function (t) {
                    return { draggable: t.currentItem || t.element, helper: t.helper, position: t.position, offset: t.positionAbs };
                },
                _addHoverClass: function () {
                    this._addClass("ui-droppable-hover");
                },
                _removeHoverClass: function () {
                    this._removeClass("ui-droppable-hover");
                },
                _addActiveClass: function () {
                    this._addClass("ui-droppable-active");
                },
                _removeActiveClass: function () {
                    this._removeClass("ui-droppable-active");
                },
            }),
            (t.ui.intersect = function (t, e, i, n) {
                if (!e.offset) return !1;
                var s = (t.positionAbs || t.position.absolute).left + t.margins.left,
                    o = (t.positionAbs || t.position.absolute).top + t.margins.top,
                    r = s + t.helperProportions.width,
                    a = o + t.helperProportions.height,
                    l = e.offset.left,
                    h = e.offset.top,
                    c = l + e.proportions().width,
                    u = h + e.proportions().height;
                switch (i) {
                    case "fit":
                        return l <= s && r <= c && h <= o && a <= u;
                    case "intersect":
                        return l < s + t.helperProportions.width / 2 && r - t.helperProportions.width / 2 < c && h < o + t.helperProportions.height / 2 && a - t.helperProportions.height / 2 < u;
                    case "pointer":
                        return ht(n.pageY, h, e.proportions().height) && ht(n.pageX, l, e.proportions().width);
                    case "touch":
                        return ((h <= o && o <= u) || (h <= a && a <= u) || (o < h && u < a)) && ((l <= s && s <= c) || (l <= r && r <= c) || (s < l && c < r));
                    default:
                        return !1;
                }
            }),
            !(t.ui.ddmanager = {
                current: null,
                droppables: { default: [] },
                prepareOffsets: function (e, i) {
                    var n,
                        s,
                        o = t.ui.ddmanager.droppables[e.options.scope] || [],
                        r = i ? i.type : null,
                        a = (e.currentItem || e.element).find(":data(ui-droppable)").addBack();
                    t: for (n = 0; n < o.length; n++)
                        if (!(o[n].options.disabled || (e && !o[n].accept.call(o[n].element[0], e.currentItem || e.element)))) {
                            for (s = 0; s < a.length; s++)
                                if (a[s] === o[n].element[0]) {
                                    o[n].proportions().height = 0;
                                    continue t;
                                }
                            (o[n].visible = "none" !== o[n].element.css("display")),
                                o[n].visible && ("mousedown" === r && o[n]._activate.call(o[n], i), (o[n].offset = o[n].element.offset()), o[n].proportions({ width: o[n].element[0].offsetWidth, height: o[n].element[0].offsetHeight }));
                        }
                },
                drop: function (e, i) {
                    var n = !1;
                    return (
                        t.each((t.ui.ddmanager.droppables[e.options.scope] || []).slice(), function () {
                            this.options &&
                                (!this.options.disabled && this.visible && t.ui.intersect(e, this, this.options.tolerance, i) && (n = this._drop.call(this, i) || n),
                                !this.options.disabled && this.visible && this.accept.call(this.element[0], e.currentItem || e.element) && ((this.isout = !0), (this.isover = !1), this._deactivate.call(this, i)));
                        }),
                        n
                    );
                },
                dragStart: function (e, i) {
                    e.element.parentsUntil("body").on("scroll.droppable", function () {
                        e.options.refreshPositions || t.ui.ddmanager.prepareOffsets(e, i);
                    });
                },
                drag: function (e, i) {
                    e.options.refreshPositions && t.ui.ddmanager.prepareOffsets(e, i),
                        t.each(t.ui.ddmanager.droppables[e.options.scope] || [], function () {
                            var n, s, o, r;
                            this.options.disabled ||
                                this.greedyChild ||
                                !this.visible ||
                                ((r = !(o = t.ui.intersect(e, this, this.options.tolerance, i)) && this.isover ? "isout" : o && !this.isover ? "isover" : null) &&
                                    (this.options.greedy &&
                                        ((s = this.options.scope),
                                        (o = this.element.parents(":data(ui-droppable)").filter(function () {
                                            return t(this).droppable("instance").options.scope === s;
                                        })).length && ((n = t(o[0]).droppable("instance")).greedyChild = "isover" === r)),
                                    n && "isover" === r && ((n.isover = !1), (n.isout = !0), n._out.call(n, i)),
                                    (this[r] = !0),
                                    (this["isout" === r ? "isover" : "isout"] = !1),
                                    this["isover" === r ? "_over" : "_out"].call(this, i),
                                    n && "isout" === r && ((n.isout = !1), (n.isover = !0), n._over.call(n, i))));
                        });
                },
                dragStop: function (e, i) {
                    e.element.parentsUntil("body").off("scroll.droppable"), e.options.refreshPositions || t.ui.ddmanager.prepareOffsets(e, i);
                },
            }) !== t.uiBackCompat &&
                t.widget("ui.droppable", t.ui.droppable, {
                    options: { hoverClass: !1, activeClass: !1 },
                    _addActiveClass: function () {
                        this._super(), this.options.activeClass && this.element.addClass(this.options.activeClass);
                    },
                    _removeActiveClass: function () {
                        this._super(), this.options.activeClass && this.element.removeClass(this.options.activeClass);
                    },
                    _addHoverClass: function () {
                        this._super(), this.options.hoverClass && this.element.addClass(this.options.hoverClass);
                    },
                    _removeHoverClass: function () {
                        this._super(), this.options.hoverClass && this.element.removeClass(this.options.hoverClass);
                    },
                }),
            t.ui.droppable,
            t.widget("ui.progressbar", {
                version: "1.13.2",
                options: { classes: { "ui-progressbar": "ui-corner-all", "ui-progressbar-value": "ui-corner-left", "ui-progressbar-complete": "ui-corner-right" }, max: 100, value: 0, change: null, complete: null },
                min: 0,
                _create: function () {
                    (this.oldValue = this.options.value = this._constrainedValue()),
                        this.element.attr({ role: "progressbar", "aria-valuemin": this.min }),
                        this._addClass("ui-progressbar", "ui-widget ui-widget-content"),
                        (this.valueDiv = t("<div>").appendTo(this.element)),
                        this._addClass(this.valueDiv, "ui-progressbar-value", "ui-widget-header"),
                        this._refreshValue();
                },
                _destroy: function () {
                    this.element.removeAttr("role aria-valuemin aria-valuemax aria-valuenow"), this.valueDiv.remove();
                },
                value: function (t) {
                    if (void 0 === t) return this.options.value;
                    (this.options.value = this._constrainedValue(t)), this._refreshValue();
                },
                _constrainedValue: function (t) {
                    return void 0 === t && (t = this.options.value), (this.indeterminate = !1 === t), "number" != typeof t && (t = 0), !this.indeterminate && Math.min(this.options.max, Math.max(this.min, t));
                },
                _setOptions: function (t) {
                    var e = t.value;
                    delete t.value, this._super(t), (this.options.value = this._constrainedValue(e)), this._refreshValue();
                },
                _setOption: function (t, e) {
                    "max" === t && (e = Math.max(this.min, e)), this._super(t, e);
                },
                _setOptionDisabled: function (t) {
                    this._super(t), this.element.attr("aria-disabled", t), this._toggleClass(null, "ui-state-disabled", !!t);
                },
                _percentage: function () {
                    return this.indeterminate ? 100 : (100 * (this.options.value - this.min)) / (this.options.max - this.min);
                },
                _refreshValue: function () {
                    var e = this.options.value,
                        i = this._percentage();
                    this.valueDiv.toggle(this.indeterminate || e > this.min).width(i.toFixed(0) + "%"),
                        this._toggleClass(this.valueDiv, "ui-progressbar-complete", null, e === this.options.max)._toggleClass("ui-progressbar-indeterminate", null, this.indeterminate),
                        this.indeterminate
                            ? (this.element.removeAttr("aria-valuenow"), this.overlayDiv || ((this.overlayDiv = t("<div>").appendTo(this.valueDiv)), this._addClass(this.overlayDiv, "ui-progressbar-overlay")))
                            : (this.element.attr({ "aria-valuemax": this.options.max, "aria-valuenow": e }), this.overlayDiv && (this.overlayDiv.remove(), (this.overlayDiv = null))),
                        this.oldValue !== e && ((this.oldValue = e), this._trigger("change")),
                        e === this.options.max && this._trigger("complete");
                },
            }),
            t.widget("ui.selectable", t.ui.mouse, {
                version: "1.13.2",
                options: { appendTo: "body", autoRefresh: !0, distance: 0, filter: "*", tolerance: "touch", selected: null, selecting: null, start: null, stop: null, unselected: null, unselecting: null },
                _create: function () {
                    var e = this;
                    this._addClass("ui-selectable"),
                        (this.dragged = !1),
                        (this.refresh = function () {
                            (e.elementPos = t(e.element[0]).offset()),
                                (e.selectees = t(e.options.filter, e.element[0])),
                                e._addClass(e.selectees, "ui-selectee"),
                                e.selectees.each(function () {
                                    var i = t(this),
                                        n = { left: (n = i.offset()).left - e.elementPos.left, top: n.top - e.elementPos.top };
                                    t.data(this, "selectable-item", {
                                        element: this,
                                        $element: i,
                                        left: n.left,
                                        top: n.top,
                                        right: n.left + i.outerWidth(),
                                        bottom: n.top + i.outerHeight(),
                                        startselected: !1,
                                        selected: i.hasClass("ui-selected"),
                                        selecting: i.hasClass("ui-selecting"),
                                        unselecting: i.hasClass("ui-unselecting"),
                                    });
                                });
                        }),
                        this.refresh(),
                        this._mouseInit(),
                        (this.helper = t("<div>")),
                        this._addClass(this.helper, "ui-selectable-helper");
                },
                _destroy: function () {
                    this.selectees.removeData("selectable-item"), this._mouseDestroy();
                },
                _mouseStart: function (e) {
                    var i = this,
                        n = this.options;
                    (this.opos = [e.pageX, e.pageY]),
                        (this.elementPos = t(this.element[0]).offset()),
                        this.options.disabled ||
                            ((this.selectees = t(n.filter, this.element[0])),
                            this._trigger("start", e),
                            t(n.appendTo).append(this.helper),
                            this.helper.css({ left: e.pageX, top: e.pageY, width: 0, height: 0 }),
                            n.autoRefresh && this.refresh(),
                            this.selectees.filter(".ui-selected").each(function () {
                                var n = t.data(this, "selectable-item");
                                (n.startselected = !0),
                                    e.metaKey ||
                                        e.ctrlKey ||
                                        (i._removeClass(n.$element, "ui-selected"), (n.selected = !1), i._addClass(n.$element, "ui-unselecting"), (n.unselecting = !0), i._trigger("unselecting", e, { unselecting: n.element }));
                            }),
                            t(e.target)
                                .parents()
                                .addBack()
                                .each(function () {
                                    var n,
                                        s = t.data(this, "selectable-item");
                                    if (s)
                                        return (
                                            (n = (!e.metaKey && !e.ctrlKey) || !s.$element.hasClass("ui-selected")),
                                            i._removeClass(s.$element, n ? "ui-unselecting" : "ui-selected")._addClass(s.$element, n ? "ui-selecting" : "ui-unselecting"),
                                            (s.unselecting = !n),
                                            (s.selecting = n),
                                            (s.selected = n) ? i._trigger("selecting", e, { selecting: s.element }) : i._trigger("unselecting", e, { unselecting: s.element }),
                                            !1
                                        );
                                }));
                },
                _mouseDrag: function (e) {
                    if (((this.dragged = !0), !this.options.disabled)) {
                        var i,
                            n = this,
                            s = this.options,
                            o = this.opos[0],
                            r = this.opos[1],
                            a = e.pageX,
                            l = e.pageY;
                        return (
                            a < o && ((i = a), (a = o), (o = i)),
                            l < r && ((i = l), (l = r), (r = i)),
                            this.helper.css({ left: o, top: r, width: a - o, height: l - r }),
                            this.selectees.each(function () {
                                var i = t.data(this, "selectable-item"),
                                    h = !1,
                                    c = {};
                                i &&
                                    i.element !== n.element[0] &&
                                    ((c.left = i.left + n.elementPos.left),
                                    (c.right = i.right + n.elementPos.left),
                                    (c.top = i.top + n.elementPos.top),
                                    (c.bottom = i.bottom + n.elementPos.top),
                                    "touch" === s.tolerance ? (h = !(c.left > a || c.right < o || c.top > l || c.bottom < r)) : "fit" === s.tolerance && (h = c.left > o && c.right < a && c.top > r && c.bottom < l),
                                    h
                                        ? (i.selected && (n._removeClass(i.$element, "ui-selected"), (i.selected = !1)),
                                          i.unselecting && (n._removeClass(i.$element, "ui-unselecting"), (i.unselecting = !1)),
                                          i.selecting || (n._addClass(i.$element, "ui-selecting"), (i.selecting = !0), n._trigger("selecting", e, { selecting: i.element })))
                                        : (i.selecting &&
                                              ((e.metaKey || e.ctrlKey) && i.startselected
                                                  ? (n._removeClass(i.$element, "ui-selecting"), (i.selecting = !1), n._addClass(i.$element, "ui-selected"), (i.selected = !0))
                                                  : (n._removeClass(i.$element, "ui-selecting"),
                                                    (i.selecting = !1),
                                                    i.startselected && (n._addClass(i.$element, "ui-unselecting"), (i.unselecting = !0)),
                                                    n._trigger("unselecting", e, { unselecting: i.element }))),
                                          i.selected &&
                                              (e.metaKey ||
                                                  e.ctrlKey ||
                                                  i.startselected ||
                                                  (n._removeClass(i.$element, "ui-selected"), (i.selected = !1), n._addClass(i.$element, "ui-unselecting"), (i.unselecting = !0), n._trigger("unselecting", e, { unselecting: i.element })))));
                            }),
                            !1
                        );
                    }
                },
                _mouseStop: function (e) {
                    var i = this;
                    return (
                        (this.dragged = !1),
                        t(".ui-unselecting", this.element[0]).each(function () {
                            var n = t.data(this, "selectable-item");
                            i._removeClass(n.$element, "ui-unselecting"), (n.unselecting = !1), (n.startselected = !1), i._trigger("unselected", e, { unselected: n.element });
                        }),
                        t(".ui-selecting", this.element[0]).each(function () {
                            var n = t.data(this, "selectable-item");
                            i._removeClass(n.$element, "ui-selecting")._addClass(n.$element, "ui-selected"), (n.selecting = !1), (n.selected = !0), (n.startselected = !0), i._trigger("selected", e, { selected: n.element });
                        }),
                        this._trigger("stop", e),
                        this.helper.remove(),
                        !1
                    );
                },
            }),
            t.widget("ui.selectmenu", [
                t.ui.formResetMixin,
                {
                    version: "1.13.2",
                    defaultElement: "<select>",
                    options: {
                        appendTo: null,
                        classes: { "ui-selectmenu-button-open": "ui-corner-top", "ui-selectmenu-button-closed": "ui-corner-all" },
                        disabled: null,
                        icons: { button: "ui-icon-triangle-1-s" },
                        position: { my: "left top", at: "left bottom", collision: "none" },
                        width: !1,
                        change: null,
                        close: null,
                        focus: null,
                        open: null,
                        select: null,
                    },
                    _create: function () {
                        var e = this.element.uniqueId().attr("id");
                        (this.ids = { element: e, button: e + "-button", menu: e + "-menu" }), this._drawButton(), this._drawMenu(), this._bindFormResetHandler(), (this._rendered = !1), (this.menuItems = t());
                    },
                    _drawButton: function () {
                        var e,
                            i = this,
                            n = this._parseOption(this.element.find("option:selected"), this.element[0].selectedIndex);
                        (this.labels = this.element.labels().attr("for", this.ids.button)),
                            this._on(this.labels, {
                                click: function (t) {
                                    this.button.trigger("focus"), t.preventDefault();
                                },
                            }),
                            this.element.hide(),
                            (this.button = t("<span>", {
                                tabindex: this.options.disabled ? -1 : 0,
                                id: this.ids.button,
                                role: "combobox",
                                "aria-expanded": "false",
                                "aria-autocomplete": "list",
                                "aria-owns": this.ids.menu,
                                "aria-haspopup": "true",
                                title: this.element.attr("title"),
                            }).insertAfter(this.element)),
                            this._addClass(this.button, "ui-selectmenu-button ui-selectmenu-button-closed", "ui-button ui-widget"),
                            (e = t("<span>").appendTo(this.button)),
                            this._addClass(e, "ui-selectmenu-icon", "ui-icon " + this.options.icons.button),
                            (this.buttonItem = this._renderButtonItem(n).appendTo(this.button)),
                            !1 !== this.options.width && this._resizeButton(),
                            this._on(this.button, this._buttonEvents),
                            this.button.one("focusin", function () {
                                i._rendered || i._refreshMenu();
                            });
                    },
                    _drawMenu: function () {
                        var e = this;
                        (this.menu = t("<ul>", { "aria-hidden": "true", "aria-labelledby": this.ids.button, id: this.ids.menu })),
                            (this.menuWrap = t("<div>").append(this.menu)),
                            this._addClass(this.menuWrap, "ui-selectmenu-menu", "ui-front"),
                            this.menuWrap.appendTo(this._appendTo()),
                            (this.menuInstance = this.menu
                                .menu({
                                    classes: { "ui-menu": "ui-corner-bottom" },
                                    role: "listbox",
                                    select: function (t, i) {
                                        t.preventDefault(), e._setSelection(), e._select(i.item.data("ui-selectmenu-item"), t);
                                    },
                                    focus: function (t, i) {
                                        (i = i.item.data("ui-selectmenu-item")),
                                            null != e.focusIndex && i.index !== e.focusIndex && (e._trigger("focus", t, { item: i }), e.isOpen || e._select(i, t)),
                                            (e.focusIndex = i.index),
                                            e.button.attr("aria-activedescendant", e.menuItems.eq(i.index).attr("id"));
                                    },
                                })
                                .menu("instance")),
                            this.menuInstance._off(this.menu, "mouseleave"),
                            (this.menuInstance._closeOnDocumentClick = function () {
                                return !1;
                            }),
                            (this.menuInstance._isDivider = function () {
                                return !1;
                            });
                    },
                    refresh: function () {
                        this._refreshMenu(), this.buttonItem.replaceWith((this.buttonItem = this._renderButtonItem(this._getSelectedItem().data("ui-selectmenu-item") || {}))), null === this.options.width && this._resizeButton();
                    },
                    _refreshMenu: function () {
                        var t = this.element.find("option");
                        this.menu.empty(),
                            this._parseOptions(t),
                            this._renderMenu(this.menu, this.items),
                            this.menuInstance.refresh(),
                            (this.menuItems = this.menu.find("li").not(".ui-selectmenu-optgroup").find(".ui-menu-item-wrapper")),
                            (this._rendered = !0),
                            t.length && ((t = this._getSelectedItem()), this.menuInstance.focus(null, t), this._setAria(t.data("ui-selectmenu-item")), this._setOption("disabled", this.element.prop("disabled")));
                    },
                    open: function (t) {
                        this.options.disabled ||
                            (this._rendered ? (this._removeClass(this.menu.find(".ui-state-active"), null, "ui-state-active"), this.menuInstance.focus(null, this._getSelectedItem())) : this._refreshMenu(),
                            this.menuItems.length && ((this.isOpen = !0), this._toggleAttr(), this._resizeMenu(), this._position(), this._on(this.document, this._documentClick), this._trigger("open", t)));
                    },
                    _position: function () {
                        this.menuWrap.position(t.extend({ of: this.button }, this.options.position));
                    },
                    close: function (t) {
                        this.isOpen && ((this.isOpen = !1), this._toggleAttr(), (this.range = null), this._off(this.document), this._trigger("close", t));
                    },
                    widget: function () {
                        return this.button;
                    },
                    menuWidget: function () {
                        return this.menu;
                    },
                    _renderButtonItem: function (e) {
                        var i = t("<span>");
                        return this._setText(i, e.label), this._addClass(i, "ui-selectmenu-text"), i;
                    },
                    _renderMenu: function (e, i) {
                        var n = this,
                            s = "";
                        t.each(i, function (i, o) {
                            var r;
                            o.optgroup !== s &&
                                ((r = t("<li>", { text: o.optgroup })),
                                n._addClass(r, "ui-selectmenu-optgroup", "ui-menu-divider" + (o.element.parent("optgroup").prop("disabled") ? " ui-state-disabled" : "")),
                                r.appendTo(e),
                                (s = o.optgroup)),
                                n._renderItemData(e, o);
                        });
                    },
                    _renderItemData: function (t, e) {
                        return this._renderItem(t, e).data("ui-selectmenu-item", e);
                    },
                    _renderItem: function (e, i) {
                        var n = t("<li>"),
                            s = t("<div>", { title: i.element.attr("title") });
                        return i.disabled && this._addClass(n, null, "ui-state-disabled"), this._setText(s, i.label), n.append(s).appendTo(e);
                    },
                    _setText: function (t, e) {
                        e ? t.text(e) : t.html("&#160;");
                    },
                    _move: function (t, e) {
                        var i,
                            n = ".ui-menu-item";
                        this.isOpen ? (i = this.menuItems.eq(this.focusIndex).parent("li")) : ((i = this.menuItems.eq(this.element[0].selectedIndex).parent("li")), (n += ":not(.ui-state-disabled)")),
                            (n = "first" === t || "last" === t ? i["first" === t ? "prevAll" : "nextAll"](n).eq(-1) : i[t + "All"](n).eq(0)).length && this.menuInstance.focus(e, n);
                    },
                    _getSelectedItem: function () {
                        return this.menuItems.eq(this.element[0].selectedIndex).parent("li");
                    },
                    _toggle: function (t) {
                        this[this.isOpen ? "close" : "open"](t);
                    },
                    _setSelection: function () {
                        var t;
                        this.range && (window.getSelection ? ((t = window.getSelection()).removeAllRanges(), t.addRange(this.range)) : this.range.select(), this.button.trigger("focus"));
                    },
                    _documentClick: {
                        mousedown: function (e) {
                            this.isOpen && (t(e.target).closest(".ui-selectmenu-menu, #" + t.escapeSelector(this.ids.button)).length || this.close(e));
                        },
                    },
                    _buttonEvents: {
                        mousedown: function () {
                            var t;
                            window.getSelection ? (t = window.getSelection()).rangeCount && (this.range = t.getRangeAt(0)) : (this.range = document.selection.createRange());
                        },
                        click: function (t) {
                            this._setSelection(), this._toggle(t);
                        },
                        keydown: function (e) {
                            var i = !0;
                            switch (e.keyCode) {
                                case t.ui.keyCode.TAB:
                                case t.ui.keyCode.ESCAPE:
                                    this.close(e), (i = !1);
                                    break;
                                case t.ui.keyCode.ENTER:
                                    this.isOpen && this._selectFocusedItem(e);
                                    break;
                                case t.ui.keyCode.UP:
                                    e.altKey ? this._toggle(e) : this._move("prev", e);
                                    break;
                                case t.ui.keyCode.DOWN:
                                    e.altKey ? this._toggle(e) : this._move("next", e);
                                    break;
                                case t.ui.keyCode.SPACE:
                                    this.isOpen ? this._selectFocusedItem(e) : this._toggle(e);
                                    break;
                                case t.ui.keyCode.LEFT:
                                    this._move("prev", e);
                                    break;
                                case t.ui.keyCode.RIGHT:
                                    this._move("next", e);
                                    break;
                                case t.ui.keyCode.HOME:
                                case t.ui.keyCode.PAGE_UP:
                                    this._move("first", e);
                                    break;
                                case t.ui.keyCode.END:
                                case t.ui.keyCode.PAGE_DOWN:
                                    this._move("last", e);
                                    break;
                                default:
                                    this.menu.trigger(e), (i = !1);
                            }
                            i && e.preventDefault();
                        },
                    },
                    _selectFocusedItem: function (t) {
                        var e = this.menuItems.eq(this.focusIndex).parent("li");
                        e.hasClass("ui-state-disabled") || this._select(e.data("ui-selectmenu-item"), t);
                    },
                    _select: function (t, e) {
                        var i = this.element[0].selectedIndex;
                        (this.element[0].selectedIndex = t.index),
                            this.buttonItem.replaceWith((this.buttonItem = this._renderButtonItem(t))),
                            this._setAria(t),
                            this._trigger("select", e, { item: t }),
                            t.index !== i && this._trigger("change", e, { item: t }),
                            this.close(e);
                    },
                    _setAria: function (t) {
                        (t = this.menuItems.eq(t.index).attr("id")), this.button.attr({ "aria-labelledby": t, "aria-activedescendant": t }), this.menu.attr("aria-activedescendant", t);
                    },
                    _setOption: function (t, e) {
                        var i;
                        "icons" === t && ((i = this.button.find("span.ui-icon")), this._removeClass(i, null, this.options.icons.button)._addClass(i, null, e.button)),
                            this._super(t, e),
                            "appendTo" === t && this.menuWrap.appendTo(this._appendTo()),
                            "width" === t && this._resizeButton();
                    },
                    _setOptionDisabled: function (t) {
                        this._super(t),
                            this.menuInstance.option("disabled", t),
                            this.button.attr("aria-disabled", t),
                            this._toggleClass(this.button, null, "ui-state-disabled", t),
                            this.element.prop("disabled", t),
                            t ? (this.button.attr("tabindex", -1), this.close()) : this.button.attr("tabindex", 0);
                    },
                    _appendTo: function () {
                        var e = this.options.appendTo;
                        return (e = (e = e && (e.jquery || e.nodeType ? t(e) : this.document.find(e).eq(0))) && e[0] ? e : this.element.closest(".ui-front, dialog")).length ? e : this.document[0].body;
                    },
                    _toggleAttr: function () {
                        this.button.attr("aria-expanded", this.isOpen),
                            this._removeClass(this.button, "ui-selectmenu-button-" + (this.isOpen ? "closed" : "open"))
                                ._addClass(this.button, "ui-selectmenu-button-" + (this.isOpen ? "open" : "closed"))
                                ._toggleClass(this.menuWrap, "ui-selectmenu-open", null, this.isOpen),
                            this.menu.attr("aria-hidden", !this.isOpen);
                    },
                    _resizeButton: function () {
                        var t = this.options.width;
                        !1 !== t ? (null === t && ((t = this.element.show().outerWidth()), this.element.hide()), this.button.outerWidth(t)) : this.button.css("width", "");
                    },
                    _resizeMenu: function () {
                        this.menu.outerWidth(Math.max(this.button.outerWidth(), this.menu.width("").outerWidth() + 1));
                    },
                    _getCreateOptions: function () {
                        var t = this._super();
                        return (t.disabled = this.element.prop("disabled")), t;
                    },
                    _parseOptions: function (e) {
                        var i = this,
                            n = [];
                        e.each(function (e, s) {
                            s.hidden || n.push(i._parseOption(t(s), e));
                        }),
                            (this.items = n);
                    },
                    _parseOption: function (t, e) {
                        var i = t.parent("optgroup");
                        return { element: t, index: e, value: t.val(), label: t.text(), optgroup: i.attr("label") || "", disabled: i.prop("disabled") || t.prop("disabled") };
                    },
                    _destroy: function () {
                        this._unbindFormResetHandler(), this.menuWrap.remove(), this.button.remove(), this.element.show(), this.element.removeUniqueId(), this.labels.attr("for", this.ids.element);
                    },
                },
            ]),
            t.widget("ui.slider", t.ui.mouse, {
                version: "1.13.2",
                widgetEventPrefix: "slide",
                options: {
                    animate: !1,
                    classes: { "ui-slider": "ui-corner-all", "ui-slider-handle": "ui-corner-all", "ui-slider-range": "ui-corner-all ui-widget-header" },
                    distance: 0,
                    max: 100,
                    min: 0,
                    orientation: "horizontal",
                    range: !1,
                    step: 1,
                    value: 0,
                    values: null,
                    change: null,
                    slide: null,
                    start: null,
                    stop: null,
                },
                numPages: 5,
                _create: function () {
                    (this._keySliding = !1),
                        (this._mouseSliding = !1),
                        (this._animateOff = !0),
                        (this._handleIndex = null),
                        this._detectOrientation(),
                        this._mouseInit(),
                        this._calculateNewMax(),
                        this._addClass("ui-slider ui-slider-" + this.orientation, "ui-widget ui-widget-content"),
                        this._refresh(),
                        (this._animateOff = !1);
                },
                _refresh: function () {
                    this._createRange(), this._createHandles(), this._setupEvents(), this._refreshValue();
                },
                _createHandles: function () {
                    var e,
                        i = this.options,
                        n = this.element.find(".ui-slider-handle"),
                        s = [],
                        o = (i.values && i.values.length) || 1;
                    for (n.length > o && (n.slice(o).remove(), (n = n.slice(0, o))), e = n.length; e < o; e++) s.push("<span tabindex='0'></span>");
                    (this.handles = n.add(t(s.join("")).appendTo(this.element))),
                        this._addClass(this.handles, "ui-slider-handle", "ui-state-default"),
                        (this.handle = this.handles.eq(0)),
                        this.handles.each(function (e) {
                            t(this).data("ui-slider-handle-index", e).attr("tabIndex", 0);
                        });
                },
                _createRange: function () {
                    var e = this.options;
                    e.range
                        ? (!0 === e.range &&
                              (e.values ? (e.values.length && 2 !== e.values.length ? (e.values = [e.values[0], e.values[0]]) : Array.isArray(e.values) && (e.values = e.values.slice(0))) : (e.values = [this._valueMin(), this._valueMin()])),
                          this.range && this.range.length
                              ? (this._removeClass(this.range, "ui-slider-range-min ui-slider-range-max"), this.range.css({ left: "", bottom: "" }))
                              : ((this.range = t("<div>").appendTo(this.element)), this._addClass(this.range, "ui-slider-range")),
                          ("min" !== e.range && "max" !== e.range) || this._addClass(this.range, "ui-slider-range-" + e.range))
                        : (this.range && this.range.remove(), (this.range = null));
                },
                _setupEvents: function () {
                    this._off(this.handles), this._on(this.handles, this._handleEvents), this._hoverable(this.handles), this._focusable(this.handles);
                },
                _destroy: function () {
                    this.handles.remove(), this.range && this.range.remove(), this._mouseDestroy();
                },
                _mouseCapture: function (e) {
                    var i,
                        n,
                        s,
                        o,
                        r,
                        a,
                        l = this,
                        h = this.options;
                    return (
                        !h.disabled &&
                        ((this.elementSize = { width: this.element.outerWidth(), height: this.element.outerHeight() }),
                        (this.elementOffset = this.element.offset()),
                        (a = { x: e.pageX, y: e.pageY }),
                        (i = this._normValueFromMouse(a)),
                        (n = this._valueMax() - this._valueMin() + 1),
                        this.handles.each(function (e) {
                            var r = Math.abs(i - l.values(e));
                            (r < n || (n === r && (e === l._lastChangedValue || l.values(e) === h.min))) && ((n = r), (s = t(this)), (o = e));
                        }),
                        !1 !== this._start(e, o) &&
                            ((this._mouseSliding = !0),
                            (this._handleIndex = o),
                            this._addClass(s, null, "ui-state-active"),
                            s.trigger("focus"),
                            (r = s.offset()),
                            (a = !t(e.target).parents().addBack().is(".ui-slider-handle")),
                            (this._clickOffset = a
                                ? { left: 0, top: 0 }
                                : {
                                      left: e.pageX - r.left - s.width() / 2,
                                      top: e.pageY - r.top - s.height() / 2 - (parseInt(s.css("borderTopWidth"), 10) || 0) - (parseInt(s.css("borderBottomWidth"), 10) || 0) + (parseInt(s.css("marginTop"), 10) || 0),
                                  }),
                            this.handles.hasClass("ui-state-hover") || this._slide(e, o, i),
                            (this._animateOff = !0)))
                    );
                },
                _mouseStart: function () {
                    return !0;
                },
                _mouseDrag: function (t) {
                    var e = { x: t.pageX, y: t.pageY };
                    e = this._normValueFromMouse(e);
                    return this._slide(t, this._handleIndex, e), !1;
                },
                _mouseStop: function (t) {
                    return (
                        this._removeClass(this.handles, null, "ui-state-active"),
                        (this._mouseSliding = !1),
                        this._stop(t, this._handleIndex),
                        this._change(t, this._handleIndex),
                        (this._handleIndex = null),
                        (this._clickOffset = null),
                        (this._animateOff = !1)
                    );
                },
                _detectOrientation: function () {
                    this.orientation = "vertical" === this.options.orientation ? "vertical" : "horizontal";
                },
                _normValueFromMouse: function (t) {
                    var e;
                    return (
                        (t =
                            1 <
                            (t =
                                (t =
                                    "horizontal" === this.orientation
                                        ? ((e = this.elementSize.width), t.x - this.elementOffset.left - (this._clickOffset ? this._clickOffset.left : 0))
                                        : ((e = this.elementSize.height), t.y - this.elementOffset.top - (this._clickOffset ? this._clickOffset.top : 0))) / e)
                                ? 1
                                : t) < 0 && (t = 0),
                        "vertical" === this.orientation && (t = 1 - t),
                        (e = this._valueMax() - this._valueMin()),
                        (e = this._valueMin() + t * e),
                        this._trimAlignValue(e)
                    );
                },
                _uiHash: function (t, e, i) {
                    var n = { handle: this.handles[t], handleIndex: t, value: void 0 !== e ? e : this.value() };
                    return this._hasMultipleValues() && ((n.value = void 0 !== e ? e : this.values(t)), (n.values = i || this.values())), n;
                },
                _hasMultipleValues: function () {
                    return this.options.values && this.options.values.length;
                },
                _start: function (t, e) {
                    return this._trigger("start", t, this._uiHash(e));
                },
                _slide: function (t, e, i) {
                    var n,
                        s = this.value(),
                        o = this.values();
                    this._hasMultipleValues() && ((n = this.values(e ? 0 : 1)), (s = this.values(e)), 2 === this.options.values.length && !0 === this.options.range && (i = 0 === e ? Math.min(n, i) : Math.max(n, i)), (o[e] = i)),
                        i !== s && !1 !== this._trigger("slide", t, this._uiHash(e, i, o)) && (this._hasMultipleValues() ? this.values(e, i) : this.value(i));
                },
                _stop: function (t, e) {
                    this._trigger("stop", t, this._uiHash(e));
                },
                _change: function (t, e) {
                    this._keySliding || this._mouseSliding || ((this._lastChangedValue = e), this._trigger("change", t, this._uiHash(e)));
                },
                value: function (t) {
                    return arguments.length ? ((this.options.value = this._trimAlignValue(t)), this._refreshValue(), void this._change(null, 0)) : this._value();
                },
                values: function (t, e) {
                    var i, n, s;
                    if (1 < arguments.length) return (this.options.values[t] = this._trimAlignValue(e)), this._refreshValue(), void this._change(null, t);
                    if (!arguments.length) return this._values();
                    if (!Array.isArray(t)) return this._hasMultipleValues() ? this._values(t) : this.value();
                    for (i = this.options.values, n = t, s = 0; s < i.length; s += 1) (i[s] = this._trimAlignValue(n[s])), this._change(null, s);
                    this._refreshValue();
                },
                _setOption: function (t, e) {
                    var i,
                        n = 0;
                    switch (
                        ("range" === t &&
                            !0 === this.options.range &&
                            ("min" === e ? ((this.options.value = this._values(0)), (this.options.values = null)) : "max" === e && ((this.options.value = this._values(this.options.values.length - 1)), (this.options.values = null))),
                        Array.isArray(this.options.values) && (n = this.options.values.length),
                        this._super(t, e),
                        t)
                    ) {
                        case "orientation":
                            this._detectOrientation(),
                                this._removeClass("ui-slider-horizontal ui-slider-vertical")._addClass("ui-slider-" + this.orientation),
                                this._refreshValue(),
                                this.options.range && this._refreshRange(e),
                                this.handles.css("horizontal" === e ? "bottom" : "left", "");
                            break;
                        case "value":
                            (this._animateOff = !0), this._refreshValue(), this._change(null, 0), (this._animateOff = !1);
                            break;
                        case "values":
                            for (this._animateOff = !0, this._refreshValue(), i = n - 1; 0 <= i; i--) this._change(null, i);
                            this._animateOff = !1;
                            break;
                        case "step":
                        case "min":
                        case "max":
                            (this._animateOff = !0), this._calculateNewMax(), this._refreshValue(), (this._animateOff = !1);
                            break;
                        case "range":
                            (this._animateOff = !0), this._refresh(), (this._animateOff = !1);
                    }
                },
                _setOptionDisabled: function (t) {
                    this._super(t), this._toggleClass(null, "ui-state-disabled", !!t);
                },
                _value: function () {
                    var t = this.options.value;
                    return this._trimAlignValue(t);
                },
                _values: function (t) {
                    var e, i;
                    if (arguments.length) return (t = this.options.values[t]), this._trimAlignValue(t);
                    if (this._hasMultipleValues()) {
                        for (e = this.options.values.slice(), i = 0; i < e.length; i += 1) e[i] = this._trimAlignValue(e[i]);
                        return e;
                    }
                    return [];
                },
                _trimAlignValue: function (t) {
                    if (t <= this._valueMin()) return this._valueMin();
                    if (t >= this._valueMax()) return this._valueMax();
                    var e = 0 < this.options.step ? this.options.step : 1,
                        i = (t - this._valueMin()) % e;
                    t -= i;
                    return 2 * Math.abs(i) >= e && (t += 0 < i ? e : -e), parseFloat(t.toFixed(5));
                },
                _calculateNewMax: function () {
                    var t = this.options.max,
                        e = this._valueMin(),
                        i = this.options.step;
                    (t = Math.round((t - e) / i) * i + e) > this.options.max && (t -= i), (this.max = parseFloat(t.toFixed(this._precision())));
                },
                _precision: function () {
                    var t = this._precisionOf(this.options.step);
                    return null !== this.options.min ? Math.max(t, this._precisionOf(this.options.min)) : t;
                },
                _precisionOf: function (t) {
                    var e = t.toString();
                    return -1 === (t = e.indexOf(".")) ? 0 : e.length - t - 1;
                },
                _valueMin: function () {
                    return this.options.min;
                },
                _valueMax: function () {
                    return this.max;
                },
                _refreshRange: function (t) {
                    "vertical" === t && this.range.css({ width: "", left: "" }), "horizontal" === t && this.range.css({ height: "", bottom: "" });
                },
                _refreshValue: function () {
                    var e,
                        i,
                        n,
                        s,
                        o,
                        r = this.options.range,
                        a = this.options,
                        l = this,
                        h = !this._animateOff && a.animate,
                        c = {};
                    this._hasMultipleValues()
                        ? this.handles.each(function (n) {
                              (i = ((l.values(n) - l._valueMin()) / (l._valueMax() - l._valueMin())) * 100),
                                  (c["horizontal" === l.orientation ? "left" : "bottom"] = i + "%"),
                                  t(this).stop(1, 1)[h ? "animate" : "css"](c, a.animate),
                                  !0 === l.options.range &&
                                      ("horizontal" === l.orientation
                                          ? (0 === n && l.range.stop(1, 1)[h ? "animate" : "css"]({ left: i + "%" }, a.animate), 1 === n && l.range[h ? "animate" : "css"]({ width: i - e + "%" }, { queue: !1, duration: a.animate }))
                                          : (0 === n && l.range.stop(1, 1)[h ? "animate" : "css"]({ bottom: i + "%" }, a.animate), 1 === n && l.range[h ? "animate" : "css"]({ height: i - e + "%" }, { queue: !1, duration: a.animate }))),
                                  (e = i);
                          })
                        : ((n = this.value()),
                          (s = this._valueMin()),
                          (o = this._valueMax()),
                          (i = o !== s ? ((n - s) / (o - s)) * 100 : 0),
                          (c["horizontal" === this.orientation ? "left" : "bottom"] = i + "%"),
                          this.handle.stop(1, 1)[h ? "animate" : "css"](c, a.animate),
                          "min" === r && "horizontal" === this.orientation && this.range.stop(1, 1)[h ? "animate" : "css"]({ width: i + "%" }, a.animate),
                          "max" === r && "horizontal" === this.orientation && this.range.stop(1, 1)[h ? "animate" : "css"]({ width: 100 - i + "%" }, a.animate),
                          "min" === r && "vertical" === this.orientation && this.range.stop(1, 1)[h ? "animate" : "css"]({ height: i + "%" }, a.animate),
                          "max" === r && "vertical" === this.orientation && this.range.stop(1, 1)[h ? "animate" : "css"]({ height: 100 - i + "%" }, a.animate));
                },
                _handleEvents: {
                    keydown: function (e) {
                        var i,
                            n,
                            s,
                            o = t(e.target).data("ui-slider-handle-index");
                        switch (e.keyCode) {
                            case t.ui.keyCode.HOME:
                            case t.ui.keyCode.END:
                            case t.ui.keyCode.PAGE_UP:
                            case t.ui.keyCode.PAGE_DOWN:
                            case t.ui.keyCode.UP:
                            case t.ui.keyCode.RIGHT:
                            case t.ui.keyCode.DOWN:
                            case t.ui.keyCode.LEFT:
                                if ((e.preventDefault(), !this._keySliding && ((this._keySliding = !0), this._addClass(t(e.target), null, "ui-state-active"), !1 === this._start(e, o)))) return;
                        }
                        switch (((s = this.options.step), (i = n = this._hasMultipleValues() ? this.values(o) : this.value()), e.keyCode)) {
                            case t.ui.keyCode.HOME:
                                n = this._valueMin();
                                break;
                            case t.ui.keyCode.END:
                                n = this._valueMax();
                                break;
                            case t.ui.keyCode.PAGE_UP:
                                n = this._trimAlignValue(i + (this._valueMax() - this._valueMin()) / this.numPages);
                                break;
                            case t.ui.keyCode.PAGE_DOWN:
                                n = this._trimAlignValue(i - (this._valueMax() - this._valueMin()) / this.numPages);
                                break;
                            case t.ui.keyCode.UP:
                            case t.ui.keyCode.RIGHT:
                                if (i === this._valueMax()) return;
                                n = this._trimAlignValue(i + s);
                                break;
                            case t.ui.keyCode.DOWN:
                            case t.ui.keyCode.LEFT:
                                if (i === this._valueMin()) return;
                                n = this._trimAlignValue(i - s);
                        }
                        this._slide(e, o, n);
                    },
                    keyup: function (e) {
                        var i = t(e.target).data("ui-slider-handle-index");
                        this._keySliding && ((this._keySliding = !1), this._stop(e, i), this._change(e, i), this._removeClass(t(e.target), null, "ui-state-active"));
                    },
                },
            }),
            t.widget("ui.sortable", t.ui.mouse, {
                version: "1.13.2",
                widgetEventPrefix: "sort",
                ready: !1,
                options: {
                    appendTo: "parent",
                    axis: !1,
                    connectWith: !1,
                    containment: !1,
                    cursor: "auto",
                    cursorAt: !1,
                    dropOnEmpty: !0,
                    forcePlaceholderSize: !1,
                    forceHelperSize: !1,
                    grid: !1,
                    handle: !1,
                    helper: "original",
                    items: "> *",
                    opacity: !1,
                    placeholder: !1,
                    revert: !1,
                    scroll: !0,
                    scrollSensitivity: 20,
                    scrollSpeed: 20,
                    scope: "default",
                    tolerance: "intersect",
                    zIndex: 1e3,
                    activate: null,
                    beforeStop: null,
                    change: null,
                    deactivate: null,
                    out: null,
                    over: null,
                    receive: null,
                    remove: null,
                    sort: null,
                    start: null,
                    stop: null,
                    update: null,
                },
                _isOverAxis: function (t, e, i) {
                    return e <= t && t < e + i;
                },
                _isFloating: function (t) {
                    return /left|right/.test(t.css("float")) || /inline|table-cell/.test(t.css("display"));
                },
                _create: function () {
                    (this.containerCache = {}), this._addClass("ui-sortable"), this.refresh(), (this.offset = this.element.offset()), this._mouseInit(), this._setHandleClassName(), (this.ready = !0);
                },
                _setOption: function (t, e) {
                    this._super(t, e), "handle" === t && this._setHandleClassName();
                },
                _setHandleClassName: function () {
                    var e = this;
                    this._removeClass(this.element.find(".ui-sortable-handle"), "ui-sortable-handle"),
                        t.each(this.items, function () {
                            e._addClass(this.instance.options.handle ? this.item.find(this.instance.options.handle) : this.item, "ui-sortable-handle");
                        });
                },
                _destroy: function () {
                    this._mouseDestroy();
                    for (var t = this.items.length - 1; 0 <= t; t--) this.items[t].item.removeData(this.widgetName + "-item");
                    return this;
                },
                _mouseCapture: function (e, i) {
                    var n = null,
                        s = !1,
                        o = this;
                    return !(
                        this.reverting ||
                        this.options.disabled ||
                        "static" === this.options.type ||
                        (this._refreshItems(e),
                        t(e.target)
                            .parents()
                            .each(function () {
                                if (t.data(this, o.widgetName + "-item") === o) return (n = t(this)), !1;
                            }),
                        !(n = t.data(e.target, o.widgetName + "-item") === o ? t(e.target) : n) ||
                            (this.options.handle &&
                                !i &&
                                (t(this.options.handle, n)
                                    .find("*")
                                    .addBack()
                                    .each(function () {
                                        this === e.target && (s = !0);
                                    }),
                                !s)) ||
                            ((this.currentItem = n), this._removeCurrentsFromItems(), 0))
                    );
                },
                _mouseStart: function (e, i, n) {
                    var s,
                        o,
                        r = this.options;
                    if (
                        ((this.currentContainer = this).refreshPositions(),
                        (this.appendTo = t("parent" !== r.appendTo ? r.appendTo : this.currentItem.parent())),
                        (this.helper = this._createHelper(e)),
                        this._cacheHelperProportions(),
                        this._cacheMargins(),
                        (this.offset = this.currentItem.offset()),
                        (this.offset = { top: this.offset.top - this.margins.top, left: this.offset.left - this.margins.left }),
                        t.extend(this.offset, { click: { left: e.pageX - this.offset.left, top: e.pageY - this.offset.top }, relative: this._getRelativeOffset() }),
                        this.helper.css("position", "absolute"),
                        (this.cssPosition = this.helper.css("position")),
                        r.cursorAt && this._adjustOffsetFromHelper(r.cursorAt),
                        (this.domPosition = { prev: this.currentItem.prev()[0], parent: this.currentItem.parent()[0] }),
                        this.helper[0] !== this.currentItem[0] && this.currentItem.hide(),
                        this._createPlaceholder(),
                        (this.scrollParent = this.placeholder.scrollParent()),
                        t.extend(this.offset, { parent: this._getParentOffset() }),
                        r.containment && this._setContainment(),
                        r.cursor &&
                            "auto" !== r.cursor &&
                            ((o = this.document.find("body")), (this.storedCursor = o.css("cursor")), o.css("cursor", r.cursor), (this.storedStylesheet = t("<style>*{ cursor: " + r.cursor + " !important; }</style>").appendTo(o))),
                        r.zIndex && (this.helper.css("zIndex") && (this._storedZIndex = this.helper.css("zIndex")), this.helper.css("zIndex", r.zIndex)),
                        r.opacity && (this.helper.css("opacity") && (this._storedOpacity = this.helper.css("opacity")), this.helper.css("opacity", r.opacity)),
                        this.scrollParent[0] !== this.document[0] && "HTML" !== this.scrollParent[0].tagName && (this.overflowOffset = this.scrollParent.offset()),
                        this._trigger("start", e, this._uiHash()),
                        this._preserveHelperProportions || this._cacheHelperProportions(),
                        !n)
                    )
                        for (s = this.containers.length - 1; 0 <= s; s--) this.containers[s]._trigger("activate", e, this._uiHash(this));
                    return (
                        t.ui.ddmanager && (t.ui.ddmanager.current = this),
                        t.ui.ddmanager && !r.dropBehaviour && t.ui.ddmanager.prepareOffsets(this, e),
                        (this.dragging = !0),
                        this._addClass(this.helper, "ui-sortable-helper"),
                        this.helper.parent().is(this.appendTo) || (this.helper.detach().appendTo(this.appendTo), (this.offset.parent = this._getParentOffset())),
                        (this.position = this.originalPosition = this._generatePosition(e)),
                        (this.originalPageX = e.pageX),
                        (this.originalPageY = e.pageY),
                        (this.lastPositionAbs = this.positionAbs = this._convertPositionTo("absolute")),
                        this._mouseDrag(e),
                        !0
                    );
                },
                _scroll: function (t) {
                    var e = this.options,
                        i = !1;
                    return (
                        this.scrollParent[0] !== this.document[0] && "HTML" !== this.scrollParent[0].tagName
                            ? (this.overflowOffset.top + this.scrollParent[0].offsetHeight - t.pageY < e.scrollSensitivity
                                  ? (this.scrollParent[0].scrollTop = i = this.scrollParent[0].scrollTop + e.scrollSpeed)
                                  : t.pageY - this.overflowOffset.top < e.scrollSensitivity && (this.scrollParent[0].scrollTop = i = this.scrollParent[0].scrollTop - e.scrollSpeed),
                              this.overflowOffset.left + this.scrollParent[0].offsetWidth - t.pageX < e.scrollSensitivity
                                  ? (this.scrollParent[0].scrollLeft = i = this.scrollParent[0].scrollLeft + e.scrollSpeed)
                                  : t.pageX - this.overflowOffset.left < e.scrollSensitivity && (this.scrollParent[0].scrollLeft = i = this.scrollParent[0].scrollLeft - e.scrollSpeed))
                            : (t.pageY - this.document.scrollTop() < e.scrollSensitivity
                                  ? (i = this.document.scrollTop(this.document.scrollTop() - e.scrollSpeed))
                                  : this.window.height() - (t.pageY - this.document.scrollTop()) < e.scrollSensitivity && (i = this.document.scrollTop(this.document.scrollTop() + e.scrollSpeed)),
                              t.pageX - this.document.scrollLeft() < e.scrollSensitivity
                                  ? (i = this.document.scrollLeft(this.document.scrollLeft() - e.scrollSpeed))
                                  : this.window.width() - (t.pageX - this.document.scrollLeft()) < e.scrollSensitivity && (i = this.document.scrollLeft(this.document.scrollLeft() + e.scrollSpeed))),
                        i
                    );
                },
                _mouseDrag: function (e) {
                    var i,
                        n,
                        s,
                        o,
                        r = this.options;
                    for (
                        this.position = this._generatePosition(e),
                            this.positionAbs = this._convertPositionTo("absolute"),
                            (this.options.axis && "y" === this.options.axis) || (this.helper[0].style.left = this.position.left + "px"),
                            (this.options.axis && "x" === this.options.axis) || (this.helper[0].style.top = this.position.top + "px"),
                            r.scroll && !1 !== this._scroll(e) && (this._refreshItemPositions(!0), t.ui.ddmanager && !r.dropBehaviour && t.ui.ddmanager.prepareOffsets(this, e)),
                            this.dragDirection = { vertical: this._getDragVerticalDirection(), horizontal: this._getDragHorizontalDirection() },
                            i = this.items.length - 1;
                        0 <= i;
                        i--
                    )
                        if (
                            ((s = (n = this.items[i]).item[0]),
                            (o = this._intersectsWithPointer(n)) &&
                                n.instance === this.currentContainer &&
                                !(s === this.currentItem[0] || this.placeholder[1 === o ? "next" : "prev"]()[0] === s || t.contains(this.placeholder[0], s) || ("semi-dynamic" === this.options.type && t.contains(this.element[0], s))))
                        ) {
                            if (((this.direction = 1 === o ? "down" : "up"), "pointer" !== this.options.tolerance && !this._intersectsWithSides(n))) break;
                            this._rearrange(e, n), this._trigger("change", e, this._uiHash());
                            break;
                        }
                    return this._contactContainers(e), t.ui.ddmanager && t.ui.ddmanager.drag(this, e), this._trigger("sort", e, this._uiHash()), (this.lastPositionAbs = this.positionAbs), !1;
                },
                _mouseStop: function (e, i) {
                    var n, s, o, r;
                    if (e)
                        return (
                            t.ui.ddmanager && !this.options.dropBehaviour && t.ui.ddmanager.drop(this, e),
                            this.options.revert
                                ? ((s = (n = this).placeholder.offset()),
                                  (r = {}),
                                  ((o = this.options.axis) && "x" !== o) || (r.left = s.left - this.offset.parent.left - this.margins.left + (this.offsetParent[0] === this.document[0].body ? 0 : this.offsetParent[0].scrollLeft)),
                                  (o && "y" !== o) || (r.top = s.top - this.offset.parent.top - this.margins.top + (this.offsetParent[0] === this.document[0].body ? 0 : this.offsetParent[0].scrollTop)),
                                  (this.reverting = !0),
                                  t(this.helper).animate(r, parseInt(this.options.revert, 10) || 500, function () {
                                      n._clear(e);
                                  }))
                                : this._clear(e, i),
                            !1
                        );
                },
                cancel: function () {
                    if (this.dragging) {
                        this._mouseUp(new t.Event("mouseup", { target: null })),
                            "original" === this.options.helper ? (this.currentItem.css(this._storedCSS), this._removeClass(this.currentItem, "ui-sortable-helper")) : this.currentItem.show();
                        for (var e = this.containers.length - 1; 0 <= e; e--)
                            this.containers[e]._trigger("deactivate", null, this._uiHash(this)),
                                this.containers[e].containerCache.over && (this.containers[e]._trigger("out", null, this._uiHash(this)), (this.containers[e].containerCache.over = 0));
                    }
                    return (
                        this.placeholder &&
                            (this.placeholder[0].parentNode && this.placeholder[0].parentNode.removeChild(this.placeholder[0]),
                            "original" !== this.options.helper && this.helper && this.helper[0].parentNode && this.helper.remove(),
                            t.extend(this, { helper: null, dragging: !1, reverting: !1, _noFinalSort: null }),
                            this.domPosition.prev ? t(this.domPosition.prev).after(this.currentItem) : t(this.domPosition.parent).prepend(this.currentItem)),
                        this
                    );
                },
                serialize: function (e) {
                    var i = this._getItemsAsjQuery(e && e.connected),
                        n = [];
                    return (
                        (e = e || {}),
                        t(i).each(function () {
                            var i = (t(e.item || this).attr(e.attribute || "id") || "").match(e.expression || /(.+)[\-=_](.+)/);
                            i && n.push((e.key || i[1] + "[]") + "=" + (e.key && e.expression ? i[1] : i[2]));
                        }),
                        !n.length && e.key && n.push(e.key + "="),
                        n.join("&")
                    );
                },
                toArray: function (e) {
                    var i = this._getItemsAsjQuery(e && e.connected),
                        n = [];
                    return (
                        (e = e || {}),
                        i.each(function () {
                            n.push(t(e.item || this).attr(e.attribute || "id") || "");
                        }),
                        n
                    );
                },
                _intersectsWith: function (t) {
                    var e = this.positionAbs.left,
                        i = e + this.helperProportions.width,
                        n = this.positionAbs.top,
                        s = n + this.helperProportions.height,
                        o = t.left,
                        r = o + t.width,
                        a = t.top,
                        l = a + t.height,
                        h = this.offset.click.top,
                        c = this.offset.click.left;
                    (h = "x" === this.options.axis || (a < n + h && n + h < l)), (c = "y" === this.options.axis || (o < e + c && e + c < r));
                    return "pointer" === this.options.tolerance ||
                        this.options.forcePointerForContainers ||
                        ("pointer" !== this.options.tolerance && this.helperProportions[this.floating ? "width" : "height"] > t[this.floating ? "width" : "height"])
                        ? h && c
                        : o < e + this.helperProportions.width / 2 && i - this.helperProportions.width / 2 < r && a < n + this.helperProportions.height / 2 && s - this.helperProportions.height / 2 < l;
                },
                _intersectsWithPointer: function (t) {
                    var e = "x" === this.options.axis || this._isOverAxis(this.positionAbs.top + this.offset.click.top, t.top, t.height);
                    t = "y" === this.options.axis || this._isOverAxis(this.positionAbs.left + this.offset.click.left, t.left, t.width);
                    return !(!e || !t) && ((e = this.dragDirection.vertical), (t = this.dragDirection.horizontal), this.floating ? ("right" === t || "down" === e ? 2 : 1) : e && ("down" === e ? 2 : 1));
                },
                _intersectsWithSides: function (t) {
                    var e = this._isOverAxis(this.positionAbs.top + this.offset.click.top, t.top + t.height / 2, t.height),
                        i = this._isOverAxis(this.positionAbs.left + this.offset.click.left, t.left + t.width / 2, t.width),
                        n = this.dragDirection.vertical;
                    t = this.dragDirection.horizontal;
                    return this.floating && t ? ("right" === t && i) || ("left" === t && !i) : n && (("down" === n && e) || ("up" === n && !e));
                },
                _getDragVerticalDirection: function () {
                    var t = this.positionAbs.top - this.lastPositionAbs.top;
                    return 0 != t && (0 < t ? "down" : "up");
                },
                _getDragHorizontalDirection: function () {
                    var t = this.positionAbs.left - this.lastPositionAbs.left;
                    return 0 != t && (0 < t ? "right" : "left");
                },
                refresh: function (t) {
                    return this._refreshItems(t), this._setHandleClassName(), this.refreshPositions(), this;
                },
                _connectWith: function () {
                    var t = this.options;
                    return t.connectWith.constructor === String ? [t.connectWith] : t.connectWith;
                },
                _getItemsAsjQuery: function (e) {
                    var i,
                        n,
                        s,
                        o,
                        r = [],
                        a = [],
                        l = this._connectWith();
                    if (l && e)
                        for (i = l.length - 1; 0 <= i; i--)
                            for (n = (s = t(l[i], this.document[0])).length - 1; 0 <= n; n--)
                                (o = t.data(s[n], this.widgetFullName)) &&
                                    o !== this &&
                                    !o.options.disabled &&
                                    a.push(["function" == typeof o.options.items ? o.options.items.call(o.element) : t(o.options.items, o.element).not(".ui-sortable-helper").not(".ui-sortable-placeholder"), o]);
                    function h() {
                        r.push(this);
                    }
                    for (
                        a.push([
                            "function" == typeof this.options.items
                                ? this.options.items.call(this.element, null, { options: this.options, item: this.currentItem })
                                : t(this.options.items, this.element).not(".ui-sortable-helper").not(".ui-sortable-placeholder"),
                            this,
                        ]),
                            i = a.length - 1;
                        0 <= i;
                        i--
                    )
                        a[i][0].each(h);
                    return t(r);
                },
                _removeCurrentsFromItems: function () {
                    var e = this.currentItem.find(":data(" + this.widgetName + "-item)");
                    this.items = t.grep(this.items, function (t) {
                        for (var i = 0; i < e.length; i++) if (e[i] === t.item[0]) return !1;
                        return !0;
                    });
                },
                _refreshItems: function (e) {
                    (this.items = []), (this.containers = [this]);
                    var i,
                        n,
                        s,
                        o,
                        r,
                        a,
                        l,
                        h,
                        c = this.items,
                        u = [["function" == typeof this.options.items ? this.options.items.call(this.element[0], e, { item: this.currentItem }) : t(this.options.items, this.element), this]],
                        d = this._connectWith();
                    if (d && this.ready)
                        for (i = d.length - 1; 0 <= i; i--)
                            for (n = (s = t(d[i], this.document[0])).length - 1; 0 <= n; n--)
                                (o = t.data(s[n], this.widgetFullName)) &&
                                    o !== this &&
                                    !o.options.disabled &&
                                    (u.push(["function" == typeof o.options.items ? o.options.items.call(o.element[0], e, { item: this.currentItem }) : t(o.options.items, o.element), o]), this.containers.push(o));
                    for (i = u.length - 1; 0 <= i; i--) for (r = u[i][1], h = (a = u[i][(n = 0)]).length; n < h; n++) (l = t(a[n])).data(this.widgetName + "-item", r), c.push({ item: l, instance: r, width: 0, height: 0, left: 0, top: 0 });
                },
                _refreshItemPositions: function (e) {
                    for (var i, n, s = this.items.length - 1; 0 <= s; s--)
                        (i = this.items[s]),
                            (this.currentContainer && i.instance !== this.currentContainer && i.item[0] !== this.currentItem[0]) ||
                                ((n = this.options.toleranceElement ? t(this.options.toleranceElement, i.item) : i.item),
                                e || ((i.width = n.outerWidth()), (i.height = n.outerHeight())),
                                (n = n.offset()),
                                (i.left = n.left),
                                (i.top = n.top));
                },
                refreshPositions: function (t) {
                    var e, i;
                    if (
                        ((this.floating = !!this.items.length && ("x" === this.options.axis || this._isFloating(this.items[0].item))),
                        this.offsetParent && this.helper && (this.offset.parent = this._getParentOffset()),
                        this._refreshItemPositions(t),
                        this.options.custom && this.options.custom.refreshContainers)
                    )
                        this.options.custom.refreshContainers.call(this);
                    else
                        for (e = this.containers.length - 1; 0 <= e; e--)
                            (i = this.containers[e].element.offset()),
                                (this.containers[e].containerCache.left = i.left),
                                (this.containers[e].containerCache.top = i.top),
                                (this.containers[e].containerCache.width = this.containers[e].element.outerWidth()),
                                (this.containers[e].containerCache.height = this.containers[e].element.outerHeight());
                    return this;
                },
                _createPlaceholder: function (e) {
                    var i,
                        n,
                        s = (e = e || this).options;
                    (s.placeholder && s.placeholder.constructor !== String) ||
                        ((i = s.placeholder),
                        (n = e.currentItem[0].nodeName.toLowerCase()),
                        (s.placeholder = {
                            element: function () {
                                var s = t("<" + n + ">", e.document[0]);
                                return (
                                    e._addClass(s, "ui-sortable-placeholder", i || e.currentItem[0].className)._removeClass(s, "ui-sortable-helper"),
                                    "tbody" === n
                                        ? e._createTrPlaceholder(e.currentItem.find("tr").eq(0), t("<tr>", e.document[0]).appendTo(s))
                                        : "tr" === n
                                        ? e._createTrPlaceholder(e.currentItem, s)
                                        : "img" === n && s.attr("src", e.currentItem.attr("src")),
                                    i || s.css("visibility", "hidden"),
                                    s
                                );
                            },
                            update: function (t, o) {
                                (i && !s.forcePlaceholderSize) ||
                                    ((o.height() && (!s.forcePlaceholderSize || ("tbody" !== n && "tr" !== n))) ||
                                        o.height(e.currentItem.innerHeight() - parseInt(e.currentItem.css("paddingTop") || 0, 10) - parseInt(e.currentItem.css("paddingBottom") || 0, 10)),
                                    o.width() || o.width(e.currentItem.innerWidth() - parseInt(e.currentItem.css("paddingLeft") || 0, 10) - parseInt(e.currentItem.css("paddingRight") || 0, 10)));
                            },
                        })),
                        (e.placeholder = t(s.placeholder.element.call(e.element, e.currentItem))),
                        e.currentItem.after(e.placeholder),
                        s.placeholder.update(e, e.placeholder);
                },
                _createTrPlaceholder: function (e, i) {
                    var n = this;
                    e.children().each(function () {
                        t("<td>&#160;</td>", n.document[0])
                            .attr("colspan", t(this).attr("colspan") || 1)
                            .appendTo(i);
                    });
                },
                _contactContainers: function (e) {
                    for (var i, n, s, o, r, a, l, h, c, u = null, d = null, p = this.containers.length - 1; 0 <= p; p--)
                        t.contains(this.currentItem[0], this.containers[p].element[0]) ||
                            (this._intersectsWith(this.containers[p].containerCache)
                                ? (u && t.contains(this.containers[p].element[0], u.element[0])) || ((u = this.containers[p]), (d = p))
                                : this.containers[p].containerCache.over && (this.containers[p]._trigger("out", e, this._uiHash(this)), (this.containers[p].containerCache.over = 0)));
                    if (u)
                        if (1 === this.containers.length) this.containers[d].containerCache.over || (this.containers[d]._trigger("over", e, this._uiHash(this)), (this.containers[d].containerCache.over = 1));
                        else {
                            for (n = 1e4, s = null, o = (h = u.floating || this._isFloating(this.currentItem)) ? "left" : "top", r = h ? "width" : "height", c = h ? "pageX" : "pageY", i = this.items.length - 1; 0 <= i; i--)
                                t.contains(this.containers[d].element[0], this.items[i].item[0]) &&
                                    this.items[i].item[0] !== this.currentItem[0] &&
                                    ((a = this.items[i].item.offset()[o]),
                                    (l = !1),
                                    e[c] - a > this.items[i][r] / 2 && (l = !0),
                                    Math.abs(e[c] - a) < n && ((n = Math.abs(e[c] - a)), (s = this.items[i]), (this.direction = l ? "up" : "down")));
                            (s || this.options.dropOnEmpty) &&
                                (this.currentContainer !== this.containers[d]
                                    ? (s ? this._rearrange(e, s, null, !0) : this._rearrange(e, null, this.containers[d].element, !0),
                                      this._trigger("change", e, this._uiHash()),
                                      this.containers[d]._trigger("change", e, this._uiHash(this)),
                                      (this.currentContainer = this.containers[d]),
                                      this.options.placeholder.update(this.currentContainer, this.placeholder),
                                      (this.scrollParent = this.placeholder.scrollParent()),
                                      this.scrollParent[0] !== this.document[0] && "HTML" !== this.scrollParent[0].tagName && (this.overflowOffset = this.scrollParent.offset()),
                                      this.containers[d]._trigger("over", e, this._uiHash(this)),
                                      (this.containers[d].containerCache.over = 1))
                                    : this.currentContainer.containerCache.over || (this.containers[d]._trigger("over", e, this._uiHash()), (this.currentContainer.containerCache.over = 1)));
                        }
                },
                _createHelper: function (e) {
                    var i = this.options;
                    return (
                        (e = "function" == typeof i.helper ? t(i.helper.apply(this.element[0], [e, this.currentItem])) : "clone" === i.helper ? this.currentItem.clone() : this.currentItem).parents("body").length ||
                            this.appendTo[0].appendChild(e[0]),
                        e[0] === this.currentItem[0] &&
                            (this._storedCSS = {
                                width: this.currentItem[0].style.width,
                                height: this.currentItem[0].style.height,
                                position: this.currentItem.css("position"),
                                top: this.currentItem.css("top"),
                                left: this.currentItem.css("left"),
                            }),
                        (e[0].style.width && !i.forceHelperSize) || e.width(this.currentItem.width()),
                        (e[0].style.height && !i.forceHelperSize) || e.height(this.currentItem.height()),
                        e
                    );
                },
                _adjustOffsetFromHelper: function (t) {
                    "string" == typeof t && (t = t.split(" ")),
                        "left" in (t = Array.isArray(t) ? { left: +t[0], top: +t[1] || 0 } : t) && (this.offset.click.left = t.left + this.margins.left),
                        "right" in t && (this.offset.click.left = this.helperProportions.width - t.right + this.margins.left),
                        "top" in t && (this.offset.click.top = t.top + this.margins.top),
                        "bottom" in t && (this.offset.click.top = this.helperProportions.height - t.bottom + this.margins.top);
                },
                _getParentOffset: function () {
                    this.offsetParent = this.helper.offsetParent();
                    var e = this.offsetParent.offset();
                    return (
                        "absolute" === this.cssPosition &&
                            this.scrollParent[0] !== this.document[0] &&
                            t.contains(this.scrollParent[0], this.offsetParent[0]) &&
                            ((e.left += this.scrollParent.scrollLeft()), (e.top += this.scrollParent.scrollTop())),
                        {
                            top:
                                (e = this.offsetParent[0] === this.document[0].body || (this.offsetParent[0].tagName && "html" === this.offsetParent[0].tagName.toLowerCase() && t.ui.ie) ? { top: 0, left: 0 } : e).top +
                                (parseInt(this.offsetParent.css("borderTopWidth"), 10) || 0),
                            left: e.left + (parseInt(this.offsetParent.css("borderLeftWidth"), 10) || 0),
                        }
                    );
                },
                _getRelativeOffset: function () {
                    if ("relative" !== this.cssPosition) return { top: 0, left: 0 };
                    var t = this.currentItem.position();
                    return { top: t.top - (parseInt(this.helper.css("top"), 10) || 0) + this.scrollParent.scrollTop(), left: t.left - (parseInt(this.helper.css("left"), 10) || 0) + this.scrollParent.scrollLeft() };
                },
                _cacheMargins: function () {
                    this.margins = { left: parseInt(this.currentItem.css("marginLeft"), 10) || 0, top: parseInt(this.currentItem.css("marginTop"), 10) || 0 };
                },
                _cacheHelperProportions: function () {
                    this.helperProportions = { width: this.helper.outerWidth(), height: this.helper.outerHeight() };
                },
                _setContainment: function () {
                    var e,
                        i,
                        n = this.options;
                    "parent" === n.containment && (n.containment = this.helper[0].parentNode),
                        ("document" !== n.containment && "window" !== n.containment) ||
                            (this.containment = [
                                0 - this.offset.relative.left - this.offset.parent.left,
                                0 - this.offset.relative.top - this.offset.parent.top,
                                "document" === n.containment ? this.document.width() : this.window.width() - this.helperProportions.width - this.margins.left,
                                ("document" === n.containment ? this.document.height() || document.body.parentNode.scrollHeight : this.window.height() || this.document[0].body.parentNode.scrollHeight) -
                                    this.helperProportions.height -
                                    this.margins.top,
                            ]),
                        /^(document|window|parent)$/.test(n.containment) ||
                            ((e = t(n.containment)[0]),
                            (i = t(n.containment).offset()),
                            (n = "hidden" !== t(e).css("overflow")),
                            (this.containment = [
                                i.left + (parseInt(t(e).css("borderLeftWidth"), 10) || 0) + (parseInt(t(e).css("paddingLeft"), 10) || 0) - this.margins.left,
                                i.top + (parseInt(t(e).css("borderTopWidth"), 10) || 0) + (parseInt(t(e).css("paddingTop"), 10) || 0) - this.margins.top,
                                i.left +
                                    (n ? Math.max(e.scrollWidth, e.offsetWidth) : e.offsetWidth) -
                                    (parseInt(t(e).css("borderLeftWidth"), 10) || 0) -
                                    (parseInt(t(e).css("paddingRight"), 10) || 0) -
                                    this.helperProportions.width -
                                    this.margins.left,
                                i.top +
                                    (n ? Math.max(e.scrollHeight, e.offsetHeight) : e.offsetHeight) -
                                    (parseInt(t(e).css("borderTopWidth"), 10) || 0) -
                                    (parseInt(t(e).css("paddingBottom"), 10) || 0) -
                                    this.helperProportions.height -
                                    this.margins.top,
                            ]));
                },
                _convertPositionTo: function (e, i) {
                    i = i || this.position;
                    var n = "absolute" === e ? 1 : -1,
                        s = "absolute" !== this.cssPosition || (this.scrollParent[0] !== this.document[0] && t.contains(this.scrollParent[0], this.offsetParent[0])) ? this.scrollParent : this.offsetParent;
                    e = /(html|body)/i.test(s[0].tagName);
                    return {
                        top: i.top + this.offset.relative.top * n + this.offset.parent.top * n - ("fixed" === this.cssPosition ? -this.scrollParent.scrollTop() : e ? 0 : s.scrollTop()) * n,
                        left: i.left + this.offset.relative.left * n + this.offset.parent.left * n - ("fixed" === this.cssPosition ? -this.scrollParent.scrollLeft() : e ? 0 : s.scrollLeft()) * n,
                    };
                },
                _generatePosition: function (e) {
                    var i = this.options,
                        n = e.pageX,
                        s = e.pageY,
                        o = "absolute" !== this.cssPosition || (this.scrollParent[0] !== this.document[0] && t.contains(this.scrollParent[0], this.offsetParent[0])) ? this.scrollParent : this.offsetParent,
                        r = /(html|body)/i.test(o[0].tagName);
                    return (
                        "relative" !== this.cssPosition || (this.scrollParent[0] !== this.document[0] && this.scrollParent[0] !== this.offsetParent[0]) || (this.offset.relative = this._getRelativeOffset()),
                        this.originalPosition &&
                            (this.containment &&
                                (e.pageX - this.offset.click.left < this.containment[0] && (n = this.containment[0] + this.offset.click.left),
                                e.pageY - this.offset.click.top < this.containment[1] && (s = this.containment[1] + this.offset.click.top),
                                e.pageX - this.offset.click.left > this.containment[2] && (n = this.containment[2] + this.offset.click.left),
                                e.pageY - this.offset.click.top > this.containment[3] && (s = this.containment[3] + this.offset.click.top)),
                            i.grid &&
                                ((e = this.originalPageY + Math.round((s - this.originalPageY) / i.grid[1]) * i.grid[1]),
                                (s =
                                    !this.containment || (e - this.offset.click.top >= this.containment[1] && e - this.offset.click.top <= this.containment[3])
                                        ? e
                                        : e - this.offset.click.top >= this.containment[1]
                                        ? e - i.grid[1]
                                        : e + i.grid[1]),
                                (e = this.originalPageX + Math.round((n - this.originalPageX) / i.grid[0]) * i.grid[0]),
                                (n =
                                    !this.containment || (e - this.offset.click.left >= this.containment[0] && e - this.offset.click.left <= this.containment[2])
                                        ? e
                                        : e - this.offset.click.left >= this.containment[0]
                                        ? e - i.grid[0]
                                        : e + i.grid[0]))),
                        {
                            top: s - this.offset.click.top - this.offset.relative.top - this.offset.parent.top + ("fixed" === this.cssPosition ? -this.scrollParent.scrollTop() : r ? 0 : o.scrollTop()),
                            left: n - this.offset.click.left - this.offset.relative.left - this.offset.parent.left + ("fixed" === this.cssPosition ? -this.scrollParent.scrollLeft() : r ? 0 : o.scrollLeft()),
                        }
                    );
                },
                _rearrange: function (t, e, i, n) {
                    i ? i[0].appendChild(this.placeholder[0]) : e.item[0].parentNode.insertBefore(this.placeholder[0], "down" === this.direction ? e.item[0] : e.item[0].nextSibling), (this.counter = this.counter ? ++this.counter : 1);
                    var s = this.counter;
                    this._delay(function () {
                        s === this.counter && this.refreshPositions(!n);
                    });
                },
                _clear: function (t, e) {
                    this.reverting = !1;
                    var i,
                        n = [];
                    if ((!this._noFinalSort && this.currentItem.parent().length && this.placeholder.before(this.currentItem), (this._noFinalSort = null), this.helper[0] === this.currentItem[0])) {
                        for (i in this._storedCSS) ("auto" !== this._storedCSS[i] && "static" !== this._storedCSS[i]) || (this._storedCSS[i] = "");
                        this.currentItem.css(this._storedCSS), this._removeClass(this.currentItem, "ui-sortable-helper");
                    } else this.currentItem.show();
                    function s(t, e, i) {
                        return function (n) {
                            i._trigger(t, n, e._uiHash(e));
                        };
                    }
                    for (
                        this.fromOutside &&
                            !e &&
                            n.push(function (t) {
                                this._trigger("receive", t, this._uiHash(this.fromOutside));
                            }),
                            (!this.fromOutside && this.domPosition.prev === this.currentItem.prev().not(".ui-sortable-helper")[0] && this.domPosition.parent === this.currentItem.parent()[0]) ||
                                e ||
                                n.push(function (t) {
                                    this._trigger("update", t, this._uiHash());
                                }),
                            this !== this.currentContainer &&
                                (e ||
                                    (n.push(function (t) {
                                        this._trigger("remove", t, this._uiHash());
                                    }),
                                    n.push(
                                        function (t) {
                                            return function (e) {
                                                t._trigger("receive", e, this._uiHash(this));
                                            };
                                        }.call(this, this.currentContainer)
                                    ),
                                    n.push(
                                        function (t) {
                                            return function (e) {
                                                t._trigger("update", e, this._uiHash(this));
                                            };
                                        }.call(this, this.currentContainer)
                                    ))),
                            i = this.containers.length - 1;
                        0 <= i;
                        i--
                    )
                        e || n.push(s("deactivate", this, this.containers[i])), this.containers[i].containerCache.over && (n.push(s("out", this, this.containers[i])), (this.containers[i].containerCache.over = 0));
                    if (
                        (this.storedCursor && (this.document.find("body").css("cursor", this.storedCursor), this.storedStylesheet.remove()),
                        this._storedOpacity && this.helper.css("opacity", this._storedOpacity),
                        this._storedZIndex && this.helper.css("zIndex", "auto" === this._storedZIndex ? "" : this._storedZIndex),
                        (this.dragging = !1),
                        e || this._trigger("beforeStop", t, this._uiHash()),
                        this.placeholder[0].parentNode.removeChild(this.placeholder[0]),
                        this.cancelHelperRemoval || (this.helper[0] !== this.currentItem[0] && this.helper.remove(), (this.helper = null)),
                        !e)
                    ) {
                        for (i = 0; i < n.length; i++) n[i].call(this, t);
                        this._trigger("stop", t, this._uiHash());
                    }
                    return (this.fromOutside = !1), !this.cancelHelperRemoval;
                },
                _trigger: function () {
                    !1 === t.Widget.prototype._trigger.apply(this, arguments) && this.cancel();
                },
                _uiHash: function (e) {
                    var i = e || this;
                    return { helper: i.helper, placeholder: i.placeholder || t([]), position: i.position, originalPosition: i.originalPosition, offset: i.positionAbs, item: i.currentItem, sender: e ? e.element : null };
                },
            }),
            t.widget("ui.spinner", {
                version: "1.13.2",
                defaultElement: "<input>",
                widgetEventPrefix: "spin",
                options: {
                    classes: { "ui-spinner": "ui-corner-all", "ui-spinner-down": "ui-corner-br", "ui-spinner-up": "ui-corner-tr" },
                    culture: null,
                    icons: { down: "ui-icon-triangle-1-s", up: "ui-icon-triangle-1-n" },
                    incremental: !0,
                    max: null,
                    min: null,
                    numberFormat: null,
                    page: 10,
                    step: 1,
                    change: null,
                    spin: null,
                    start: null,
                    stop: null,
                },
                _create: function () {
                    this._setOption("max", this.options.max),
                        this._setOption("min", this.options.min),
                        this._setOption("step", this.options.step),
                        "" !== this.value() && this._value(this.element.val(), !0),
                        this._draw(),
                        this._on(this._events),
                        this._refresh(),
                        this._on(this.window, {
                            beforeunload: function () {
                                this.element.removeAttr("autocomplete");
                            },
                        });
                },
                _getCreateOptions: function () {
                    var e = this._super(),
                        i = this.element;
                    return (
                        t.each(["min", "max", "step"], function (t, n) {
                            var s = i.attr(n);
                            null != s && s.length && (e[n] = s);
                        }),
                        e
                    );
                },
                _events: {
                    keydown: function (t) {
                        this._start(t) && this._keydown(t) && t.preventDefault();
                    },
                    keyup: "_stop",
                    focus: function () {
                        this.previous = this.element.val();
                    },
                    blur: function (t) {
                        this.cancelBlur ? delete this.cancelBlur : (this._stop(), this._refresh(), this.previous !== this.element.val() && this._trigger("change", t));
                    },
                    mousewheel: function (e, i) {
                        var n = t.ui.safeActiveElement(this.document[0]);
                        if (this.element[0] === n && i) {
                            if (!this.spinning && !this._start(e)) return !1;
                            this._spin((0 < i ? 1 : -1) * this.options.step, e),
                                clearTimeout(this.mousewheelTimer),
                                (this.mousewheelTimer = this._delay(function () {
                                    this.spinning && this._stop(e);
                                }, 100)),
                                e.preventDefault();
                        }
                    },
                    "mousedown .ui-spinner-button": function (e) {
                        var i;
                        function n() {
                            this.element[0] === t.ui.safeActiveElement(this.document[0]) ||
                                (this.element.trigger("focus"),
                                (this.previous = i),
                                this._delay(function () {
                                    this.previous = i;
                                }));
                        }
                        (i = this.element[0] === t.ui.safeActiveElement(this.document[0]) ? this.previous : this.element.val()),
                            e.preventDefault(),
                            n.call(this),
                            (this.cancelBlur = !0),
                            this._delay(function () {
                                delete this.cancelBlur, n.call(this);
                            }),
                            !1 !== this._start(e) && this._repeat(null, t(e.currentTarget).hasClass("ui-spinner-up") ? 1 : -1, e);
                    },
                    "mouseup .ui-spinner-button": "_stop",
                    "mouseenter .ui-spinner-button": function (e) {
                        if (t(e.currentTarget).hasClass("ui-state-active")) return !1 !== this._start(e) && void this._repeat(null, t(e.currentTarget).hasClass("ui-spinner-up") ? 1 : -1, e);
                    },
                    "mouseleave .ui-spinner-button": "_stop",
                },
                _enhance: function () {
                    this.uiSpinner = this.element.attr("autocomplete", "off").wrap("<span>").parent().append("<a></a><a></a>");
                },
                _draw: function () {
                    this._enhance(),
                        this._addClass(this.uiSpinner, "ui-spinner", "ui-widget ui-widget-content"),
                        this._addClass("ui-spinner-input"),
                        this.element.attr("role", "spinbutton"),
                        (this.buttons = this.uiSpinner
                            .children("a")
                            .attr("tabIndex", -1)
                            .attr("aria-hidden", !0)
                            .button({ classes: { "ui-button": "" } })),
                        this._removeClass(this.buttons, "ui-corner-all"),
                        this._addClass(this.buttons.first(), "ui-spinner-button ui-spinner-up"),
                        this._addClass(this.buttons.last(), "ui-spinner-button ui-spinner-down"),
                        this.buttons.first().button({ icon: this.options.icons.up, showLabel: !1 }),
                        this.buttons.last().button({ icon: this.options.icons.down, showLabel: !1 }),
                        this.buttons.height() > Math.ceil(0.5 * this.uiSpinner.height()) && 0 < this.uiSpinner.height() && this.uiSpinner.height(this.uiSpinner.height());
                },
                _keydown: function (e) {
                    var i = this.options,
                        n = t.ui.keyCode;
                    switch (e.keyCode) {
                        case n.UP:
                            return this._repeat(null, 1, e), !0;
                        case n.DOWN:
                            return this._repeat(null, -1, e), !0;
                        case n.PAGE_UP:
                            return this._repeat(null, i.page, e), !0;
                        case n.PAGE_DOWN:
                            return this._repeat(null, -i.page, e), !0;
                    }
                    return !1;
                },
                _start: function (t) {
                    return !(!this.spinning && !1 === this._trigger("start", t)) && (this.counter || (this.counter = 1), (this.spinning = !0));
                },
                _repeat: function (t, e, i) {
                    (t = t || 500),
                        clearTimeout(this.timer),
                        (this.timer = this._delay(function () {
                            this._repeat(40, e, i);
                        }, t)),
                        this._spin(e * this.options.step, i);
                },
                _spin: function (t, e) {
                    var i = this.value() || 0;
                    this.counter || (this.counter = 1), (i = this._adjustValue(i + t * this._increment(this.counter))), (this.spinning && !1 === this._trigger("spin", e, { value: i })) || (this._value(i), this.counter++);
                },
                _increment: function (t) {
                    var e = this.options.incremental;
                    return e ? ("function" == typeof e ? e(t) : Math.floor((t * t * t) / 5e4 - (t * t) / 500 + (17 * t) / 200 + 1)) : 1;
                },
                _precision: function () {
                    var t = this._precisionOf(this.options.step);
                    return null !== this.options.min ? Math.max(t, this._precisionOf(this.options.min)) : t;
                },
                _precisionOf: function (t) {
                    var e = t.toString();
                    return -1 === (t = e.indexOf(".")) ? 0 : e.length - t - 1;
                },
                _adjustValue: function (t) {
                    var e = this.options,
                        i = null !== e.min ? e.min : 0,
                        n = t - i;
                    return (t = i + Math.round(n / e.step) * e.step), (t = parseFloat(t.toFixed(this._precision()))), null !== e.max && t > e.max ? e.max : null !== e.min && t < e.min ? e.min : t;
                },
                _stop: function (t) {
                    this.spinning && (clearTimeout(this.timer), clearTimeout(this.mousewheelTimer), (this.counter = 0), (this.spinning = !1), this._trigger("stop", t));
                },
                _setOption: function (t, e) {
                    var i;
                    if ("culture" === t || "numberFormat" === t) return (i = this._parse(this.element.val())), (this.options[t] = e), void this.element.val(this._format(i));
                    ("max" !== t && "min" !== t && "step" !== t) || ("string" == typeof e && (e = this._parse(e))),
                        "icons" === t &&
                            ((i = this.buttons.first().find(".ui-icon")),
                            this._removeClass(i, null, this.options.icons.up),
                            this._addClass(i, null, e.up),
                            (i = this.buttons.last().find(".ui-icon")),
                            this._removeClass(i, null, this.options.icons.down),
                            this._addClass(i, null, e.down)),
                        this._super(t, e);
                },
                _setOptionDisabled: function (t) {
                    this._super(t), this._toggleClass(this.uiSpinner, null, "ui-state-disabled", !!t), this.element.prop("disabled", !!t), this.buttons.button(t ? "disable" : "enable");
                },
                _setOptions: ct(function (t) {
                    this._super(t);
                }),
                _parse: function (t) {
                    return "" === (t = "string" == typeof t && "" !== t ? (window.Globalize && this.options.numberFormat ? Globalize.parseFloat(t, 10, this.options.culture) : +t) : t) || isNaN(t) ? null : t;
                },
                _format: function (t) {
                    return "" === t ? "" : window.Globalize && this.options.numberFormat ? Globalize.format(t, this.options.numberFormat, this.options.culture) : t;
                },
                _refresh: function () {
                    this.element.attr({ "aria-valuemin": this.options.min, "aria-valuemax": this.options.max, "aria-valuenow": this._parse(this.element.val()) });
                },
                isValid: function () {
                    var t = this.value();
                    return null !== t && t === this._adjustValue(t);
                },
                _value: function (t, e) {
                    var i;
                    "" !== t && null !== (i = this._parse(t)) && (e || (i = this._adjustValue(i)), (t = this._format(i))), this.element.val(t), this._refresh();
                },
                _destroy: function () {
                    this.element.prop("disabled", !1).removeAttr("autocomplete role aria-valuemin aria-valuemax aria-valuenow"), this.uiSpinner.replaceWith(this.element);
                },
                stepUp: ct(function (t) {
                    this._stepUp(t);
                }),
                _stepUp: function (t) {
                    this._start() && (this._spin((t || 1) * this.options.step), this._stop());
                },
                stepDown: ct(function (t) {
                    this._stepDown(t);
                }),
                _stepDown: function (t) {
                    this._start() && (this._spin((t || 1) * -this.options.step), this._stop());
                },
                pageUp: ct(function (t) {
                    this._stepUp((t || 1) * this.options.page);
                }),
                pageDown: ct(function (t) {
                    this._stepDown((t || 1) * this.options.page);
                }),
                value: function (t) {
                    if (!arguments.length) return this._parse(this.element.val());
                    ct(this._value).call(this, t);
                },
                widget: function () {
                    return this.uiSpinner;
                },
            }),
            !1 !== t.uiBackCompat &&
                t.widget("ui.spinner", t.ui.spinner, {
                    _enhance: function () {
                        this.uiSpinner = this.element.attr("autocomplete", "off").wrap(this._uiSpinnerHtml()).parent().append(this._buttonHtml());
                    },
                    _uiSpinnerHtml: function () {
                        return "<span>";
                    },
                    _buttonHtml: function () {
                        return "<a></a><a></a>";
                    },
                }),
            t.ui.spinner,
            t.widget("ui.tabs", {
                version: "1.13.2",
                delay: 300,
                options: {
                    active: null,
                    classes: { "ui-tabs": "ui-corner-all", "ui-tabs-nav": "ui-corner-all", "ui-tabs-panel": "ui-corner-bottom", "ui-tabs-tab": "ui-corner-top" },
                    collapsible: !1,
                    event: "click",
                    heightStyle: "content",
                    hide: null,
                    show: null,
                    activate: null,
                    beforeActivate: null,
                    beforeLoad: null,
                    load: null,
                },
                _isLocal:
                    ((at = /#.*$/),
                    function (t) {
                        var e = t.href.replace(at, ""),
                            i = location.href.replace(at, "");
                        try {
                            e = decodeURIComponent(e);
                        } catch (t) {}
                        try {
                            i = decodeURIComponent(i);
                        } catch (t) {}
                        return 1 < t.hash.length && e === i;
                    }),
                _create: function () {
                    var e = this,
                        i = this.options;
                    (this.running = !1),
                        this._addClass("ui-tabs", "ui-widget ui-widget-content"),
                        this._toggleClass("ui-tabs-collapsible", null, i.collapsible),
                        this._processTabs(),
                        (i.active = this._initialActive()),
                        Array.isArray(i.disabled) &&
                            (i.disabled = t
                                .uniqueSort(
                                    i.disabled.concat(
                                        t.map(this.tabs.filter(".ui-state-disabled"), function (t) {
                                            return e.tabs.index(t);
                                        })
                                    )
                                )
                                .sort()),
                        !1 !== this.options.active && this.anchors.length ? (this.active = this._findActive(i.active)) : (this.active = t()),
                        this._refresh(),
                        this.active.length && this.load(i.active);
                },
                _initialActive: function () {
                    var e = this.options.active,
                        i = this.options.collapsible,
                        n = location.hash.substring(1);
                    return (
                        null === e &&
                            (n &&
                                this.tabs.each(function (i, s) {
                                    if (t(s).attr("aria-controls") === n) return (e = i), !1;
                                }),
                            (null !== (e = null === e ? this.tabs.index(this.tabs.filter(".ui-tabs-active")) : e) && -1 !== e) || (e = !!this.tabs.length && 0)),
                        !1 !== e && -1 === (e = this.tabs.index(this.tabs.eq(e))) && (e = !i && 0),
                        (e = !i && !1 === e && this.anchors.length ? 0 : e)
                    );
                },
                _getCreateEventData: function () {
                    return { tab: this.active, panel: this.active.length ? this._getPanelForTab(this.active) : t() };
                },
                _tabKeydown: function (e) {
                    var i = t(t.ui.safeActiveElement(this.document[0])).closest("li"),
                        n = this.tabs.index(i),
                        s = !0;
                    if (!this._handlePageNav(e)) {
                        switch (e.keyCode) {
                            case t.ui.keyCode.RIGHT:
                            case t.ui.keyCode.DOWN:
                                n++;
                                break;
                            case t.ui.keyCode.UP:
                            case t.ui.keyCode.LEFT:
                                (s = !1), n--;
                                break;
                            case t.ui.keyCode.END:
                                n = this.anchors.length - 1;
                                break;
                            case t.ui.keyCode.HOME:
                                n = 0;
                                break;
                            case t.ui.keyCode.SPACE:
                                return e.preventDefault(), clearTimeout(this.activating), void this._activate(n);
                            case t.ui.keyCode.ENTER:
                                return e.preventDefault(), clearTimeout(this.activating), void this._activate(n !== this.options.active && n);
                            default:
                                return;
                        }
                        e.preventDefault(),
                            clearTimeout(this.activating),
                            (n = this._focusNextTab(n, s)),
                            e.ctrlKey ||
                                e.metaKey ||
                                (i.attr("aria-selected", "false"),
                                this.tabs.eq(n).attr("aria-selected", "true"),
                                (this.activating = this._delay(function () {
                                    this.option("active", n);
                                }, this.delay)));
                    }
                },
                _panelKeydown: function (e) {
                    this._handlePageNav(e) || (e.ctrlKey && e.keyCode === t.ui.keyCode.UP && (e.preventDefault(), this.active.trigger("focus")));
                },
                _handlePageNav: function (e) {
                    return e.altKey && e.keyCode === t.ui.keyCode.PAGE_UP
                        ? (this._activate(this._focusNextTab(this.options.active - 1, !1)), !0)
                        : e.altKey && e.keyCode === t.ui.keyCode.PAGE_DOWN
                        ? (this._activate(this._focusNextTab(this.options.active + 1, !0)), !0)
                        : void 0;
                },
                _findNextTab: function (e, i) {
                    for (var n = this.tabs.length - 1; -1 !== t.inArray((e = (e = n < e ? 0 : e) < 0 ? n : e), this.options.disabled); ) e = i ? e + 1 : e - 1;
                    return e;
                },
                _focusNextTab: function (t, e) {
                    return (t = this._findNextTab(t, e)), this.tabs.eq(t).trigger("focus"), t;
                },
                _setOption: function (t, e) {
                    "active" !== t
                        ? (this._super(t, e),
                          "collapsible" === t && (this._toggleClass("ui-tabs-collapsible", null, e), e || !1 !== this.options.active || this._activate(0)),
                          "event" === t && this._setupEvents(e),
                          "heightStyle" === t && this._setupHeightStyle(e))
                        : this._activate(e);
                },
                _sanitizeSelector: function (t) {
                    return t ? t.replace(/[!"$%&'()*+,.\/:;<=>?@\[\]\^`{|}~]/g, "\\$&") : "";
                },
                refresh: function () {
                    var e = this.options,
                        i = this.tablist.children(":has(a[href])");
                    (e.disabled = t.map(i.filter(".ui-state-disabled"), function (t) {
                        return i.index(t);
                    })),
                        this._processTabs(),
                        !1 !== e.active && this.anchors.length
                            ? this.active.length && !t.contains(this.tablist[0], this.active[0])
                                ? this.tabs.length === e.disabled.length
                                    ? ((e.active = !1), (this.active = t()))
                                    : this._activate(this._findNextTab(Math.max(0, e.active - 1), !1))
                                : (e.active = this.tabs.index(this.active))
                            : ((e.active = !1), (this.active = t())),
                        this._refresh();
                },
                _refresh: function () {
                    this._setOptionDisabled(this.options.disabled),
                        this._setupEvents(this.options.event),
                        this._setupHeightStyle(this.options.heightStyle),
                        this.tabs.not(this.active).attr({ "aria-selected": "false", "aria-expanded": "false", tabIndex: -1 }),
                        this.panels.not(this._getPanelForTab(this.active)).hide().attr({ "aria-hidden": "true" }),
                        this.active.length
                            ? (this.active.attr({ "aria-selected": "true", "aria-expanded": "true", tabIndex: 0 }),
                              this._addClass(this.active, "ui-tabs-active", "ui-state-active"),
                              this._getPanelForTab(this.active).show().attr({ "aria-hidden": "false" }))
                            : this.tabs.eq(0).attr("tabIndex", 0);
                },
                _processTabs: function () {
                    var e = this,
                        i = this.tabs,
                        n = this.anchors,
                        s = this.panels;
                    (this.tablist = this._getList().attr("role", "tablist")),
                        this._addClass(this.tablist, "ui-tabs-nav", "ui-helper-reset ui-helper-clearfix ui-widget-header"),
                        this.tablist
                            .on("mousedown" + this.eventNamespace, "> li", function (e) {
                                t(this).is(".ui-state-disabled") && e.preventDefault();
                            })
                            .on("focus" + this.eventNamespace, ".ui-tabs-anchor", function () {
                                t(this).closest("li").is(".ui-state-disabled") && this.blur();
                            }),
                        (this.tabs = this.tablist.find("> li:has(a[href])").attr({ role: "tab", tabIndex: -1 })),
                        this._addClass(this.tabs, "ui-tabs-tab", "ui-state-default"),
                        (this.anchors = this.tabs
                            .map(function () {
                                return t("a", this)[0];
                            })
                            .attr({ tabIndex: -1 })),
                        this._addClass(this.anchors, "ui-tabs-anchor"),
                        (this.panels = t()),
                        this.anchors.each(function (i, n) {
                            var s,
                                o,
                                r,
                                a = t(n).uniqueId().attr("id"),
                                l = t(n).closest("li"),
                                h = l.attr("aria-controls");
                            e._isLocal(n)
                                ? ((r = (s = n.hash).substring(1)), (o = e.element.find(e._sanitizeSelector(s))))
                                : ((r = l.attr("aria-controls") || t({}).uniqueId()[0].id), (o = e.element.find((s = "#" + r))).length || (o = e._createPanel(r)).insertAfter(e.panels[i - 1] || e.tablist), o.attr("aria-live", "polite")),
                                o.length && (e.panels = e.panels.add(o)),
                                h && l.data("ui-tabs-aria-controls", h),
                                l.attr({ "aria-controls": r, "aria-labelledby": a }),
                                o.attr("aria-labelledby", a);
                        }),
                        this.panels.attr("role", "tabpanel"),
                        this._addClass(this.panels, "ui-tabs-panel", "ui-widget-content"),
                        i && (this._off(i.not(this.tabs)), this._off(n.not(this.anchors)), this._off(s.not(this.panels)));
                },
                _getList: function () {
                    return this.tablist || this.element.find("ol, ul").eq(0);
                },
                _createPanel: function (e) {
                    return t("<div>").attr("id", e).data("ui-tabs-destroy", !0);
                },
                _setOptionDisabled: function (e) {
                    var i, n;
                    for (Array.isArray(e) && (e.length ? e.length === this.anchors.length && (e = !0) : (e = !1)), n = 0; (i = this.tabs[n]); n++)
                        (i = t(i)), !0 === e || -1 !== t.inArray(n, e) ? (i.attr("aria-disabled", "true"), this._addClass(i, null, "ui-state-disabled")) : (i.removeAttr("aria-disabled"), this._removeClass(i, null, "ui-state-disabled"));
                    (this.options.disabled = e), this._toggleClass(this.widget(), this.widgetFullName + "-disabled", null, !0 === e);
                },
                _setupEvents: function (e) {
                    var i = {};
                    e &&
                        t.each(e.split(" "), function (t, e) {
                            i[e] = "_eventHandler";
                        }),
                        this._off(this.anchors.add(this.tabs).add(this.panels)),
                        this._on(!0, this.anchors, {
                            click: function (t) {
                                t.preventDefault();
                            },
                        }),
                        this._on(this.anchors, i),
                        this._on(this.tabs, { keydown: "_tabKeydown" }),
                        this._on(this.panels, { keydown: "_panelKeydown" }),
                        this._focusable(this.tabs),
                        this._hoverable(this.tabs);
                },
                _setupHeightStyle: function (e) {
                    var i,
                        n = this.element.parent();
                    "fill" === e
                        ? ((i = n.height()),
                          (i -= this.element.outerHeight() - this.element.height()),
                          this.element.siblings(":visible").each(function () {
                              var e = t(this),
                                  n = e.css("position");
                              "absolute" !== n && "fixed" !== n && (i -= e.outerHeight(!0));
                          }),
                          this.element
                              .children()
                              .not(this.panels)
                              .each(function () {
                                  i -= t(this).outerHeight(!0);
                              }),
                          this.panels
                              .each(function () {
                                  t(this).height(Math.max(0, i - t(this).innerHeight() + t(this).height()));
                              })
                              .css("overflow", "auto"))
                        : "auto" === e &&
                          ((i = 0),
                          this.panels
                              .each(function () {
                                  i = Math.max(i, t(this).height("").height());
                              })
                              .height(i));
                },
                _eventHandler: function (e) {
                    var i = this.options,
                        n = this.active,
                        s = t(e.currentTarget).closest("li"),
                        o = s[0] === n[0],
                        r = o && i.collapsible,
                        a = r ? t() : this._getPanelForTab(s),
                        l = n.length ? this._getPanelForTab(n) : t();
                    n = { oldTab: n, oldPanel: l, newTab: r ? t() : s, newPanel: a };
                    e.preventDefault(),
                        s.hasClass("ui-state-disabled") ||
                            s.hasClass("ui-tabs-loading") ||
                            this.running ||
                            (o && !i.collapsible) ||
                            !1 === this._trigger("beforeActivate", e, n) ||
                            ((i.active = !r && this.tabs.index(s)),
                            (this.active = o ? t() : s),
                            this.xhr && this.xhr.abort(),
                            l.length || a.length || t.error("jQuery UI Tabs: Mismatching fragment identifier."),
                            a.length && this.load(this.tabs.index(s), e),
                            this._toggle(e, n));
                },
                _toggle: function (e, i) {
                    var n = this,
                        s = i.newPanel,
                        o = i.oldPanel;
                    function r() {
                        (n.running = !1), n._trigger("activate", e, i);
                    }
                    function a() {
                        n._addClass(i.newTab.closest("li"), "ui-tabs-active", "ui-state-active"), s.length && n.options.show ? n._show(s, n.options.show, r) : (s.show(), r());
                    }
                    (this.running = !0),
                        o.length && this.options.hide
                            ? this._hide(o, this.options.hide, function () {
                                  n._removeClass(i.oldTab.closest("li"), "ui-tabs-active", "ui-state-active"), a();
                              })
                            : (this._removeClass(i.oldTab.closest("li"), "ui-tabs-active", "ui-state-active"), o.hide(), a()),
                        o.attr("aria-hidden", "true"),
                        i.oldTab.attr({ "aria-selected": "false", "aria-expanded": "false" }),
                        s.length && o.length
                            ? i.oldTab.attr("tabIndex", -1)
                            : s.length &&
                              this.tabs
                                  .filter(function () {
                                      return 0 === t(this).attr("tabIndex");
                                  })
                                  .attr("tabIndex", -1),
                        s.attr("aria-hidden", "false"),
                        i.newTab.attr({ "aria-selected": "true", "aria-expanded": "true", tabIndex: 0 });
                },
                _activate: function (e) {
                    (e = this._findActive(e))[0] !== this.active[0] && ((e = (e = e.length ? e : this.active).find(".ui-tabs-anchor")[0]), this._eventHandler({ target: e, currentTarget: e, preventDefault: t.noop }));
                },
                _findActive: function (e) {
                    return !1 === e ? t() : this.tabs.eq(e);
                },
                _getIndex: function (e) {
                    return "string" == typeof e ? this.anchors.index(this.anchors.filter("[href$='" + t.escapeSelector(e) + "']")) : e;
                },
                _destroy: function () {
                    this.xhr && this.xhr.abort(),
                        this.tablist.removeAttr("role").off(this.eventNamespace),
                        this.anchors.removeAttr("role tabIndex").removeUniqueId(),
                        this.tabs.add(this.panels).each(function () {
                            t.data(this, "ui-tabs-destroy") ? t(this).remove() : t(this).removeAttr("role tabIndex aria-live aria-busy aria-selected aria-labelledby aria-hidden aria-expanded");
                        }),
                        this.tabs.each(function () {
                            var e = t(this),
                                i = e.data("ui-tabs-aria-controls");
                            i ? e.attr("aria-controls", i).removeData("ui-tabs-aria-controls") : e.removeAttr("aria-controls");
                        }),
                        this.panels.show(),
                        "content" !== this.options.heightStyle && this.panels.css("height", "");
                },
                enable: function (e) {
                    var i = this.options.disabled;
                    !1 !== i &&
                        ((i =
                            void 0 !== e &&
                            ((e = this._getIndex(e)),
                            Array.isArray(i)
                                ? t.map(i, function (t) {
                                      return t !== e ? t : null;
                                  })
                                : t.map(this.tabs, function (t, i) {
                                      return i !== e ? i : null;
                                  }))),
                        this._setOptionDisabled(i));
                },
                disable: function (e) {
                    var i = this.options.disabled;
                    if (!0 !== i) {
                        if (void 0 === e) i = !0;
                        else {
                            if (((e = this._getIndex(e)), -1 !== t.inArray(e, i))) return;
                            i = Array.isArray(i) ? t.merge([e], i).sort() : [e];
                        }
                        this._setOptionDisabled(i);
                    }
                },
                load: function (e, i) {
                    function n(t, e) {
                        "abort" === e && s.panels.stop(!1, !0), s._removeClass(o, "ui-tabs-loading"), r.removeAttr("aria-busy"), t === s.xhr && delete s.xhr;
                    }
                    e = this._getIndex(e);
                    var s = this,
                        o = this.tabs.eq(e),
                        r = ((e = o.find(".ui-tabs-anchor")), this._getPanelForTab(o)),
                        a = { tab: o, panel: r };
                    this._isLocal(e[0]) ||
                        ((this.xhr = t.ajax(this._ajaxSettings(e, i, a))),
                        this.xhr &&
                            "canceled" !== this.xhr.statusText &&
                            (this._addClass(o, "ui-tabs-loading"),
                            r.attr("aria-busy", "true"),
                            this.xhr
                                .done(function (t, e, o) {
                                    setTimeout(function () {
                                        r.html(t), s._trigger("load", i, a), n(o, e);
                                    }, 1);
                                })
                                .fail(function (t, e) {
                                    setTimeout(function () {
                                        n(t, e);
                                    }, 1);
                                })));
                },
                _ajaxSettings: function (e, i, n) {
                    var s = this;
                    return {
                        url: e.attr("href").replace(/#.*$/, ""),
                        beforeSend: function (e, o) {
                            return s._trigger("beforeLoad", i, t.extend({ jqXHR: e, ajaxSettings: o }, n));
                        },
                    };
                },
                _getPanelForTab: function (e) {
                    return (e = t(e).attr("aria-controls")), this.element.find(this._sanitizeSelector("#" + e));
                },
            }),
            !1 !== t.uiBackCompat &&
                t.widget("ui.tabs", t.ui.tabs, {
                    _processTabs: function () {
                        this._superApply(arguments), this._addClass(this.tabs, "ui-tab");
                    },
                }),
            t.ui.tabs,
            t.widget("ui.tooltip", {
                version: "1.13.2",
                options: {
                    classes: { "ui-tooltip": "ui-corner-all ui-widget-shadow" },
                    content: function () {
                        var e = t(this).attr("title");
                        return t("<a>").text(e).html();
                    },
                    hide: !0,
                    items: "[title]:not([disabled])",
                    position: { my: "left top+15", at: "left bottom", collision: "flipfit flip" },
                    show: !0,
                    track: !1,
                    close: null,
                    open: null,
                },
                _addDescribedBy: function (t, e) {
                    var i = (t.attr("aria-describedby") || "").split(/\s+/);
                    i.push(e), t.data("ui-tooltip-id", e).attr("aria-describedby", String.prototype.trim.call(i.join(" ")));
                },
                _removeDescribedBy: function (e) {
                    var i = e.data("ui-tooltip-id"),
                        n = (e.attr("aria-describedby") || "").split(/\s+/);
                    -1 !== (i = t.inArray(i, n)) && n.splice(i, 1), e.removeData("ui-tooltip-id"), (n = String.prototype.trim.call(n.join(" "))) ? e.attr("aria-describedby", n) : e.removeAttr("aria-describedby");
                },
                _create: function () {
                    this._on({ mouseover: "open", focusin: "open" }),
                        (this.tooltips = {}),
                        (this.parents = {}),
                        (this.liveRegion = t("<div>").attr({ role: "log", "aria-live": "assertive", "aria-relevant": "additions" }).appendTo(this.document[0].body)),
                        this._addClass(this.liveRegion, null, "ui-helper-hidden-accessible"),
                        (this.disabledTitles = t([]));
                },
                _setOption: function (e, i) {
                    var n = this;
                    this._super(e, i),
                        "content" === e &&
                            t.each(this.tooltips, function (t, e) {
                                n._updateContent(e.element);
                            });
                },
                _setOptionDisabled: function (t) {
                    this[t ? "_disable" : "_enable"]();
                },
                _disable: function () {
                    var e = this;
                    t.each(this.tooltips, function (i, n) {
                        var s = t.Event("blur");
                        (s.target = s.currentTarget = n.element[0]), e.close(s, !0);
                    }),
                        (this.disabledTitles = this.disabledTitles.add(
                            this.element
                                .find(this.options.items)
                                .addBack()
                                .filter(function () {
                                    var e = t(this);
                                    if (e.is("[title]")) return e.data("ui-tooltip-title", e.attr("title")).removeAttr("title");
                                })
                        ));
                },
                _enable: function () {
                    this.disabledTitles.each(function () {
                        var e = t(this);
                        e.data("ui-tooltip-title") && e.attr("title", e.data("ui-tooltip-title"));
                    }),
                        (this.disabledTitles = t([]));
                },
                open: function (e) {
                    var i = this,
                        n = t(e ? e.target : this.element).closest(this.options.items);
                    n.length &&
                        !n.data("ui-tooltip-id") &&
                        (n.attr("title") && n.data("ui-tooltip-title", n.attr("title")),
                        n.data("ui-tooltip-open", !0),
                        e &&
                            "mouseover" === e.type &&
                            n.parents().each(function () {
                                var e,
                                    n = t(this);
                                n.data("ui-tooltip-open") && (((e = t.Event("blur")).target = e.currentTarget = this), i.close(e, !0)),
                                    n.attr("title") && (n.uniqueId(), (i.parents[this.id] = { element: this, title: n.attr("title") }), n.attr("title", ""));
                            }),
                        this._registerCloseHandlers(e, n),
                        this._updateContent(n, e));
                },
                _updateContent: function (t, e) {
                    var i = this.options.content,
                        n = this,
                        s = e ? e.type : null;
                    if ("string" == typeof i || i.nodeType || i.jquery) return this._open(e, t, i);
                    (i = i.call(t[0], function (i) {
                        n._delay(function () {
                            t.data("ui-tooltip-open") && (e && (e.type = s), this._open(e, t, i));
                        });
                    })) && this._open(e, t, i);
                },
                _open: function (e, i, n) {
                    var s,
                        o,
                        r,
                        a = t.extend({}, this.options.position);
                    function l(t) {
                        (a.of = t), o.is(":hidden") || o.position(a);
                    }
                    n &&
                        ((s = this._find(i))
                            ? s.tooltip.find(".ui-tooltip-content").html(n)
                            : (i.is("[title]") && (e && "mouseover" === e.type ? i.attr("title", "") : i.removeAttr("title")),
                              (s = this._tooltip(i)),
                              (o = s.tooltip),
                              this._addDescribedBy(i, o.attr("id")),
                              o.find(".ui-tooltip-content").html(n),
                              this.liveRegion.children().hide(),
                              (n = t("<div>").html(o.find(".ui-tooltip-content").html())).removeAttr("name").find("[name]").removeAttr("name"),
                              n.removeAttr("id").find("[id]").removeAttr("id"),
                              n.appendTo(this.liveRegion),
                              this.options.track && e && /^mouse/.test(e.type) ? (this._on(this.document, { mousemove: l }), l(e)) : o.position(t.extend({ of: i }, this.options.position)),
                              o.hide(),
                              this._show(o, this.options.show),
                              this.options.track &&
                                  this.options.show &&
                                  this.options.show.delay &&
                                  (r = this.delayedShow = setInterval(function () {
                                      o.is(":visible") && (l(a.of), clearInterval(r));
                                  }, 13)),
                              this._trigger("open", e, { tooltip: o })));
                },
                _registerCloseHandlers: function (e, i) {
                    var n = {
                        keyup: function (e) {
                            e.keyCode === t.ui.keyCode.ESCAPE && (((e = t.Event(e)).currentTarget = i[0]), this.close(e, !0));
                        },
                    };
                    i[0] !== this.element[0] &&
                        (n.remove = function () {
                            var t = this._find(i);
                            t && this._removeTooltip(t.tooltip);
                        }),
                        (e && "mouseover" !== e.type) || (n.mouseleave = "close"),
                        (e && "focusin" !== e.type) || (n.focusout = "close"),
                        this._on(!0, i, n);
                },
                close: function (e) {
                    var i,
                        n = this,
                        s = t(e ? e.currentTarget : this.element),
                        o = this._find(s);
                    o
                        ? ((i = o.tooltip),
                          o.closing ||
                              (clearInterval(this.delayedShow),
                              s.data("ui-tooltip-title") && !s.attr("title") && s.attr("title", s.data("ui-tooltip-title")),
                              this._removeDescribedBy(s),
                              (o.hiding = !0),
                              i.stop(!0),
                              this._hide(i, this.options.hide, function () {
                                  n._removeTooltip(t(this));
                              }),
                              s.removeData("ui-tooltip-open"),
                              this._off(s, "mouseleave focusout keyup"),
                              s[0] !== this.element[0] && this._off(s, "remove"),
                              this._off(this.document, "mousemove"),
                              e &&
                                  "mouseleave" === e.type &&
                                  t.each(this.parents, function (e, i) {
                                      t(i.element).attr("title", i.title), delete n.parents[e];
                                  }),
                              (o.closing = !0),
                              this._trigger("close", e, { tooltip: i }),
                              o.hiding || (o.closing = !1)))
                        : s.removeData("ui-tooltip-open");
                },
                _tooltip: function (e) {
                    var i = t("<div>").attr("role", "tooltip"),
                        n = t("<div>").appendTo(i),
                        s = i.uniqueId().attr("id");
                    return this._addClass(n, "ui-tooltip-content"), this._addClass(i, "ui-tooltip", "ui-widget ui-widget-content"), i.appendTo(this._appendTo(e)), (this.tooltips[s] = { element: e, tooltip: i });
                },
                _find: function (t) {
                    return (t = t.data("ui-tooltip-id")) ? this.tooltips[t] : null;
                },
                _removeTooltip: function (t) {
                    clearInterval(this.delayedShow), t.remove(), delete this.tooltips[t.attr("id")];
                },
                _appendTo: function (t) {
                    return (t = t.closest(".ui-front, dialog")).length ? t : this.document[0].body;
                },
                _destroy: function () {
                    var e = this;
                    t.each(this.tooltips, function (i, n) {
                        var s = t.Event("blur");
                        n = n.element;
                        (s.target = s.currentTarget = n[0]), e.close(s, !0), t("#" + i).remove(), n.data("ui-tooltip-title") && (n.attr("title") || n.attr("title", n.data("ui-tooltip-title")), n.removeData("ui-tooltip-title"));
                    }),
                        this.liveRegion.remove();
                },
            }),
            !1 !== t.uiBackCompat &&
                t.widget("ui.tooltip", t.ui.tooltip, {
                    options: { tooltipClass: null },
                    _tooltip: function () {
                        var t = this._superApply(arguments);
                        return this.options.tooltipClass && t.tooltip.addClass(this.options.tooltipClass), t;
                    },
                }),
            t.ui.tooltip;
    }),
    (function (t, e) {
        "object" == typeof exports && "undefined" != typeof module ? (module.exports = e()) : "function" == typeof define && define.amd ? define(e) : ((t = "undefined" != typeof globalThis ? globalThis : t || self).bootstrap = e());
    })(this, function () {
        "use strict";
        const t = new Map(),
            e = {
                set(e, i, n) {
                    t.has(e) || t.set(e, new Map());
                    const s = t.get(e);
                    s.has(i) || 0 === s.size ? s.set(i, n) : console.error(`Bootstrap doesn't allow more than one instance per element. Bound instance: ${Array.from(s.keys())[0]}.`);
                },
                get: (e, i) => (t.has(e) && t.get(e).get(i)) || null,
                remove(e, i) {
                    if (!t.has(e)) return;
                    const n = t.get(e);
                    n.delete(i), 0 === n.size && t.delete(e);
                },
            },
            i = "transitionend",
            n = (t) => (t && window.CSS && window.CSS.escape && (t = t.replace(/#([^\s"#']+)/g, (t, e) => `#${CSS.escape(e)}`)), t),
            s = (t) => {
                t.dispatchEvent(new Event(i));
            },
            o = (t) => !(!t || "object" != typeof t) && (void 0 !== t.jquery && (t = t[0]), void 0 !== t.nodeType),
            r = (t) => (o(t) ? (t.jquery ? t[0] : t) : "string" == typeof t && t.length > 0 ? document.querySelector(n(t)) : null),
            a = (t) => {
                if (!o(t) || 0 === t.getClientRects().length) return !1;
                const e = "visible" === getComputedStyle(t).getPropertyValue("visibility"),
                    i = t.closest("details:not([open])");
                if (!i) return e;
                if (i !== t) {
                    const e = t.closest("summary");
                    if (e && e.parentNode !== i) return !1;
                    if (null === e) return !1;
                }
                return e;
            },
            l = (t) => !t || t.nodeType !== Node.ELEMENT_NODE || !!t.classList.contains("disabled") || (void 0 !== t.disabled ? t.disabled : t.hasAttribute("disabled") && "false" !== t.getAttribute("disabled")),
            h = (t) => {
                if (!document.documentElement.attachShadow) return null;
                if ("function" == typeof t.getRootNode) {
                    const e = t.getRootNode();
                    return e instanceof ShadowRoot ? e : null;
                }
                return t instanceof ShadowRoot ? t : t.parentNode ? h(t.parentNode) : null;
            },
            c = () => {},
            u = (t) => {
                t.offsetHeight;
            },
            d = () => (window.jQuery && !document.body.hasAttribute("data-bs-no-jquery") ? window.jQuery : null),
            p = [],
            f = () => "rtl" === document.documentElement.dir,
            g = (t) => {
                var e;
                (e = () => {
                    const e = d();
                    if (e) {
                        const i = t.NAME,
                            n = e.fn[i];
                        (e.fn[i] = t.jQueryInterface), (e.fn[i].Constructor = t), (e.fn[i].noConflict = () => ((e.fn[i] = n), t.jQueryInterface));
                    }
                }),
                    "loading" === document.readyState
                        ? (p.length ||
                              document.addEventListener("DOMContentLoaded", () => {
                                  for (const t of p) t();
                              }),
                          p.push(e))
                        : e();
            },
            m = (t, e = [], i = t) => ("function" == typeof t ? t(...e) : i),
            v = (t, e, n = !0) => {
                if (!n) return void m(t);
                const o =
                    ((t) => {
                        if (!t) return 0;
                        let { transitionDuration: e, transitionDelay: i } = window.getComputedStyle(t);
                        const n = Number.parseFloat(e),
                            s = Number.parseFloat(i);
                        return n || s ? ((e = e.split(",")[0]), (i = i.split(",")[0]), 1e3 * (Number.parseFloat(e) + Number.parseFloat(i))) : 0;
                    })(e) + 5;
                let r = !1;
                const a = ({ target: n }) => {
                    n === e && ((r = !0), e.removeEventListener(i, a), m(t));
                };
                e.addEventListener(i, a),
                    setTimeout(() => {
                        r || s(e);
                    }, o);
            },
            _ = (t, e, i, n) => {
                const s = t.length;
                let o = t.indexOf(e);
                return -1 === o ? (!i && n ? t[s - 1] : t[0]) : ((o += i ? 1 : -1), n && (o = (o + s) % s), t[Math.max(0, Math.min(o, s - 1))]);
            },
            b = /[^.]*(?=\..*)\.|.*/,
            y = /\..*/,
            w = /::\d+$/,
            x = {};
        let k = 1;
        const C = { mouseenter: "mouseover", mouseleave: "mouseout" },
            T = new Set([
                "click",
                "dblclick",
                "mouseup",
                "mousedown",
                "contextmenu",
                "mousewheel",
                "DOMMouseScroll",
                "mouseover",
                "mouseout",
                "mousemove",
                "selectstart",
                "selectend",
                "keydown",
                "keypress",
                "keyup",
                "orientationchange",
                "touchstart",
                "touchmove",
                "touchend",
                "touchcancel",
                "pointerdown",
                "pointermove",
                "pointerup",
                "pointerleave",
                "pointercancel",
                "gesturestart",
                "gesturechange",
                "gestureend",
                "focus",
                "blur",
                "change",
                "reset",
                "select",
                "submit",
                "focusin",
                "focusout",
                "load",
                "unload",
                "beforeunload",
                "resize",
                "move",
                "DOMContentLoaded",
                "readystatechange",
                "error",
                "abort",
                "scroll",
            ]);
        function D(t, e) {
            return (e && `${e}::${k++}`) || t.uidEvent || k++;
        }
        function S(t) {
            const e = D(t);
            return (t.uidEvent = e), (x[e] = x[e] || {}), x[e];
        }
        function A(t, e, i = null) {
            return Object.values(t).find((t) => t.callable === e && t.delegationSelector === i);
        }
        function E(t, e, i) {
            const n = "string" == typeof e,
                s = n ? i : e || i;
            let o = O(t);
            return T.has(o) || (o = t), [n, s, o];
        }
        function I(t, e, i, n, s) {
            if ("string" != typeof e || !t) return;
            let [o, r, a] = E(e, i, n);
            if (e in C) {
                const t = (t) =>
                    function (e) {
                        if (!e.relatedTarget || (e.relatedTarget !== e.delegateTarget && !e.delegateTarget.contains(e.relatedTarget))) return t.call(this, e);
                    };
                r = t(r);
            }
            const l = S(t),
                h = l[a] || (l[a] = {}),
                c = A(h, r, o ? i : null);
            if (c) return void (c.oneOff = c.oneOff && s);
            const u = D(r, e.replace(b, "")),
                d = o
                    ? (function (t, e, i) {
                          return function n(s) {
                              const o = t.querySelectorAll(e);
                              for (let { target: r } = s; r && r !== this; r = r.parentNode) for (const a of o) if (a === r) return L(s, { delegateTarget: r }), n.oneOff && H.off(t, s.type, e, i), i.apply(r, [s]);
                          };
                      })(t, i, r)
                    : (function (t, e) {
                          return function i(n) {
                              return L(n, { delegateTarget: t }), i.oneOff && H.off(t, n.type, e), e.apply(t, [n]);
                          };
                      })(t, r);
            (d.delegationSelector = o ? i : null), (d.callable = r), (d.oneOff = s), (d.uidEvent = u), (h[u] = d), t.addEventListener(a, d, o);
        }
        function P(t, e, i, n, s) {
            const o = A(e[i], n, s);
            o && (t.removeEventListener(i, o, Boolean(s)), delete e[i][o.uidEvent]);
        }
        function M(t, e, i, n) {
            const s = e[i] || {};
            for (const [o, r] of Object.entries(s)) o.includes(n) && P(t, e, i, r.callable, r.delegationSelector);
        }
        function O(t) {
            return (t = t.replace(y, "")), C[t] || t;
        }
        const H = {
            on(t, e, i, n) {
                I(t, e, i, n, !1);
            },
            one(t, e, i, n) {
                I(t, e, i, n, !0);
            },
            off(t, e, i, n) {
                if ("string" != typeof e || !t) return;
                const [s, o, r] = E(e, i, n),
                    a = r !== e,
                    l = S(t),
                    h = l[r] || {},
                    c = e.startsWith(".");
                if (void 0 === o) {
                    if (c) for (const i of Object.keys(l)) M(t, l, i, e.slice(1));
                    for (const [i, n] of Object.entries(h)) {
                        const s = i.replace(w, "");
                        (a && !e.includes(s)) || P(t, l, r, n.callable, n.delegationSelector);
                    }
                } else {
                    if (!Object.keys(h).length) return;
                    P(t, l, r, o, s ? i : null);
                }
            },
            trigger(t, e, i) {
                if ("string" != typeof e || !t) return null;
                const n = d();
                let s = null,
                    o = !0,
                    r = !0,
                    a = !1;
                e !== O(e) && n && ((s = n.Event(e, i)), n(t).trigger(s), (o = !s.isPropagationStopped()), (r = !s.isImmediatePropagationStopped()), (a = s.isDefaultPrevented()));
                const l = L(new Event(e, { bubbles: o, cancelable: !0 }), i);
                return a && l.preventDefault(), r && t.dispatchEvent(l), l.defaultPrevented && s && s.preventDefault(), l;
            },
        };
        function L(t, e = {}) {
            for (const [i, n] of Object.entries(e))
                try {
                    t[i] = n;
                } catch (e) {
                    Object.defineProperty(t, i, { configurable: !0, get: () => n });
                }
            return t;
        }
        function N(t) {
            if ("true" === t) return !0;
            if ("false" === t) return !1;
            if (t === Number(t).toString()) return Number(t);
            if ("" === t || "null" === t) return null;
            if ("string" != typeof t) return t;
            try {
                return JSON.parse(decodeURIComponent(t));
            } catch (e) {
                return t;
            }
        }
        function W(t) {
            return t.replace(/[A-Z]/g, (t) => `-${t.toLowerCase()}`);
        }
        const R = {
            setDataAttribute(t, e, i) {
                t.setAttribute(`data-bs-${W(e)}`, i);
            },
            removeDataAttribute(t, e) {
                t.removeAttribute(`data-bs-${W(e)}`);
            },
            getDataAttributes(t) {
                if (!t) return {};
                const e = {},
                    i = Object.keys(t.dataset).filter((t) => t.startsWith("bs") && !t.startsWith("bsConfig"));
                for (const n of i) {
                    let i = n.replace(/^bs/, "");
                    (i = i.charAt(0).toLowerCase() + i.slice(1, i.length)), (e[i] = N(t.dataset[n]));
                }
                return e;
            },
            getDataAttribute: (t, e) => N(t.getAttribute(`data-bs-${W(e)}`)),
        };
        class z {
            static get Default() {
                return {};
            }
            static get DefaultType() {
                return {};
            }
            static get NAME() {
                throw new Error('You have to implement the static method "NAME", for each component!');
            }
            _getConfig(t) {
                return (t = this._mergeConfigObj(t)), (t = this._configAfterMerge(t)), this._typeCheckConfig(t), t;
            }
            _configAfterMerge(t) {
                return t;
            }
            _mergeConfigObj(t, e) {
                const i = o(e) ? R.getDataAttribute(e, "config") : {};
                return { ...this.constructor.Default, ...("object" == typeof i ? i : {}), ...(o(e) ? R.getDataAttributes(e) : {}), ...("object" == typeof t ? t : {}) };
            }
            _typeCheckConfig(t, e = this.constructor.DefaultType) {
                for (const [n, s] of Object.entries(e)) {
                    const e = t[n],
                        r = o(e)
                            ? "element"
                            : null == (i = e)
                            ? `${i}`
                            : Object.prototype.toString
                                  .call(i)
                                  .match(/\s([a-z]+)/i)[1]
                                  .toLowerCase();
                    if (!new RegExp(s).test(r)) throw new TypeError(`${this.constructor.NAME.toUpperCase()}: Option "${n}" provided type "${r}" but expected type "${s}".`);
                }
                var i;
            }
        }
        class j extends z {
            constructor(t, i) {
                super(), (t = r(t)) && ((this._element = t), (this._config = this._getConfig(i)), e.set(this._element, this.constructor.DATA_KEY, this));
            }
            dispose() {
                e.remove(this._element, this.constructor.DATA_KEY), H.off(this._element, this.constructor.EVENT_KEY);
                for (const t of Object.getOwnPropertyNames(this)) this[t] = null;
            }
            _queueCallback(t, e, i = !0) {
                v(t, e, i);
            }
            _getConfig(t) {
                return (t = this._mergeConfigObj(t, this._element)), (t = this._configAfterMerge(t)), this._typeCheckConfig(t), t;
            }
            static getInstance(t) {
                return e.get(r(t), this.DATA_KEY);
            }
            static getOrCreateInstance(t, e = {}) {
                return this.getInstance(t) || new this(t, "object" == typeof e ? e : null);
            }
            static get VERSION() {
                return "5.3.2";
            }
            static get DATA_KEY() {
                return `bs.${this.NAME}`;
            }
            static get EVENT_KEY() {
                return `.${this.DATA_KEY}`;
            }
            static eventName(t) {
                return `${t}${this.EVENT_KEY}`;
            }
        }
        const F = (t) => {
                let e = t.getAttribute("data-bs-target");
                if (!e || "#" === e) {
                    let i = t.getAttribute("href");
                    if (!i || (!i.includes("#") && !i.startsWith("."))) return null;
                    i.includes("#") && !i.startsWith("#") && (i = `#${i.split("#")[1]}`), (e = i && "#" !== i ? n(i.trim()) : null);
                }
                return e;
            },
            q = {
                find: (t, e = document.documentElement) => [].concat(...Element.prototype.querySelectorAll.call(e, t)),
                findOne: (t, e = document.documentElement) => Element.prototype.querySelector.call(e, t),
                children: (t, e) => [].concat(...t.children).filter((t) => t.matches(e)),
                parents(t, e) {
                    const i = [];
                    let n = t.parentNode.closest(e);
                    for (; n; ) i.push(n), (n = n.parentNode.closest(e));
                    return i;
                },
                prev(t, e) {
                    let i = t.previousElementSibling;
                    for (; i; ) {
                        if (i.matches(e)) return [i];
                        i = i.previousElementSibling;
                    }
                    return [];
                },
                next(t, e) {
                    let i = t.nextElementSibling;
                    for (; i; ) {
                        if (i.matches(e)) return [i];
                        i = i.nextElementSibling;
                    }
                    return [];
                },
                focusableChildren(t) {
                    const e = ["a", "button", "input", "textarea", "select", "details", "[tabindex]", '[contenteditable="true"]'].map((t) => `${t}:not([tabindex^="-"])`).join(",");
                    return this.find(e, t).filter((t) => !l(t) && a(t));
                },
                getSelectorFromElement(t) {
                    const e = F(t);
                    return e && q.findOne(e) ? e : null;
                },
                getElementFromSelector(t) {
                    const e = F(t);
                    return e ? q.findOne(e) : null;
                },
                getMultipleElementsFromSelector(t) {
                    const e = F(t);
                    return e ? q.find(e) : [];
                },
            },
            Y = (t, e = "hide") => {
                const i = `click.dismiss${t.EVENT_KEY}`,
                    n = t.NAME;
                H.on(document, i, `[data-bs-dismiss="${n}"]`, function (i) {
                    if ((["A", "AREA"].includes(this.tagName) && i.preventDefault(), l(this))) return;
                    const s = q.getElementFromSelector(this) || this.closest(`.${n}`);
                    t.getOrCreateInstance(s)[e]();
                });
            },
            B = ".bs.alert",
            $ = `close${B}`,
            X = `closed${B}`;
        class U extends j {
            static get NAME() {
                return "alert";
            }
            close() {
                if (H.trigger(this._element, $).defaultPrevented) return;
                this._element.classList.remove("show");
                const t = this._element.classList.contains("fade");
                this._queueCallback(() => this._destroyElement(), this._element, t);
            }
            _destroyElement() {
                this._element.remove(), H.trigger(this._element, X), this.dispose();
            }
            static jQueryInterface(t) {
                return this.each(function () {
                    const e = U.getOrCreateInstance(this);
                    if ("string" == typeof t) {
                        if (void 0 === e[t] || t.startsWith("_") || "constructor" === t) throw new TypeError(`No method named "${t}"`);
                        e[t](this);
                    }
                });
            }
        }
        Y(U, "close"), g(U);
        const K = '[data-bs-toggle="button"]';
        class V extends j {
            static get NAME() {
                return "button";
            }
            toggle() {
                this._element.setAttribute("aria-pressed", this._element.classList.toggle("active"));
            }
            static jQueryInterface(t) {
                return this.each(function () {
                    const e = V.getOrCreateInstance(this);
                    "toggle" === t && e[t]();
                });
            }
        }
        H.on(document, "click.bs.button.data-api", K, (t) => {
            t.preventDefault();
            const e = t.target.closest(K);
            V.getOrCreateInstance(e).toggle();
        }),
            g(V);
        const G = ".bs.swipe",
            Q = `touchstart${G}`,
            J = `touchmove${G}`,
            Z = `touchend${G}`,
            tt = `pointerdown${G}`,
            et = `pointerup${G}`,
            it = { endCallback: null, leftCallback: null, rightCallback: null },
            nt = { endCallback: "(function|null)", leftCallback: "(function|null)", rightCallback: "(function|null)" };
        class st extends z {
            constructor(t, e) {
                super(), (this._element = t), t && st.isSupported() && ((this._config = this._getConfig(e)), (this._deltaX = 0), (this._supportPointerEvents = Boolean(window.PointerEvent)), this._initEvents());
            }
            static get Default() {
                return it;
            }
            static get DefaultType() {
                return nt;
            }
            static get NAME() {
                return "swipe";
            }
            dispose() {
                H.off(this._element, G);
            }
            _start(t) {
                this._supportPointerEvents ? this._eventIsPointerPenTouch(t) && (this._deltaX = t.clientX) : (this._deltaX = t.touches[0].clientX);
            }
            _end(t) {
                this._eventIsPointerPenTouch(t) && (this._deltaX = t.clientX - this._deltaX), this._handleSwipe(), m(this._config.endCallback);
            }
            _move(t) {
                this._deltaX = t.touches && t.touches.length > 1 ? 0 : t.touches[0].clientX - this._deltaX;
            }
            _handleSwipe() {
                const t = Math.abs(this._deltaX);
                if (t <= 40) return;
                const e = t / this._deltaX;
                (this._deltaX = 0), e && m(e > 0 ? this._config.rightCallback : this._config.leftCallback);
            }
            _initEvents() {
                this._supportPointerEvents
                    ? (H.on(this._element, tt, (t) => this._start(t)), H.on(this._element, et, (t) => this._end(t)), this._element.classList.add("pointer-event"))
                    : (H.on(this._element, Q, (t) => this._start(t)), H.on(this._element, J, (t) => this._move(t)), H.on(this._element, Z, (t) => this._end(t)));
            }
            _eventIsPointerPenTouch(t) {
                return this._supportPointerEvents && ("pen" === t.pointerType || "touch" === t.pointerType);
            }
            static isSupported() {
                return "ontouchstart" in document.documentElement || navigator.maxTouchPoints > 0;
            }
        }
        const ot = ".bs.carousel",
            rt = ".data-api",
            at = "next",
            lt = "prev",
            ht = "left",
            ct = "right",
            ut = `slide${ot}`,
            dt = `slid${ot}`,
            pt = `keydown${ot}`,
            ft = `mouseenter${ot}`,
            gt = `mouseleave${ot}`,
            mt = `dragstart${ot}`,
            vt = `load${ot}${rt}`,
            _t = `click${ot}${rt}`,
            bt = "carousel",
            yt = "active",
            wt = ".active",
            xt = ".carousel-item",
            kt = wt + xt,
            Ct = { ArrowLeft: ct, ArrowRight: ht },
            Tt = { interval: 5e3, keyboard: !0, pause: "hover", ride: !1, touch: !0, wrap: !0 },
            Dt = { interval: "(number|boolean)", keyboard: "boolean", pause: "(string|boolean)", ride: "(boolean|string)", touch: "boolean", wrap: "boolean" };
        class St extends j {
            constructor(t, e) {
                super(t, e),
                    (this._interval = null),
                    (this._activeElement = null),
                    (this._isSliding = !1),
                    (this.touchTimeout = null),
                    (this._swipeHelper = null),
                    (this._indicatorsElement = q.findOne(".carousel-indicators", this._element)),
                    this._addEventListeners(),
                    this._config.ride === bt && this.cycle();
            }
            static get Default() {
                return Tt;
            }
            static get DefaultType() {
                return Dt;
            }
            static get NAME() {
                return "carousel";
            }
            next() {
                this._slide(at);
            }
            nextWhenVisible() {
                !document.hidden && a(this._element) && this.next();
            }
            prev() {
                this._slide(lt);
            }
            pause() {
                this._isSliding && s(this._element), this._clearInterval();
            }
            cycle() {
                this._clearInterval(), this._updateInterval(), (this._interval = setInterval(() => this.nextWhenVisible(), this._config.interval));
            }
            _maybeEnableCycle() {
                this._config.ride && (this._isSliding ? H.one(this._element, dt, () => this.cycle()) : this.cycle());
            }
            to(t) {
                const e = this._getItems();
                if (t > e.length - 1 || t < 0) return;
                if (this._isSliding) return void H.one(this._element, dt, () => this.to(t));
                const i = this._getItemIndex(this._getActive());
                if (i === t) return;
                const n = t > i ? at : lt;
                this._slide(n, e[t]);
            }
            dispose() {
                this._swipeHelper && this._swipeHelper.dispose(), super.dispose();
            }
            _configAfterMerge(t) {
                return (t.defaultInterval = t.interval), t;
            }
            _addEventListeners() {
                this._config.keyboard && H.on(this._element, pt, (t) => this._keydown(t)),
                    "hover" === this._config.pause && (H.on(this._element, ft, () => this.pause()), H.on(this._element, gt, () => this._maybeEnableCycle())),
                    this._config.touch && st.isSupported() && this._addTouchEventListeners();
            }
            _addTouchEventListeners() {
                for (const t of q.find(".carousel-item img", this._element)) H.on(t, mt, (t) => t.preventDefault());
                const t = {
                    leftCallback: () => this._slide(this._directionToOrder(ht)),
                    rightCallback: () => this._slide(this._directionToOrder(ct)),
                    endCallback: () => {
                        "hover" === this._config.pause && (this.pause(), this.touchTimeout && clearTimeout(this.touchTimeout), (this.touchTimeout = setTimeout(() => this._maybeEnableCycle(), 500 + this._config.interval)));
                    },
                };
                this._swipeHelper = new st(this._element, t);
            }
            _keydown(t) {
                if (/input|textarea/i.test(t.target.tagName)) return;
                const e = Ct[t.key];
                e && (t.preventDefault(), this._slide(this._directionToOrder(e)));
            }
            _getItemIndex(t) {
                return this._getItems().indexOf(t);
            }
            _setActiveIndicatorElement(t) {
                if (!this._indicatorsElement) return;
                const e = q.findOne(wt, this._indicatorsElement);
                e.classList.remove(yt), e.removeAttribute("aria-current");
                const i = q.findOne(`[data-bs-slide-to="${t}"]`, this._indicatorsElement);
                i && (i.classList.add(yt), i.setAttribute("aria-current", "true"));
            }
            _updateInterval() {
                const t = this._activeElement || this._getActive();
                if (!t) return;
                const e = Number.parseInt(t.getAttribute("data-bs-interval"), 10);
                this._config.interval = e || this._config.defaultInterval;
            }
            _slide(t, e = null) {
                if (this._isSliding) return;
                const i = this._getActive(),
                    n = t === at,
                    s = e || _(this._getItems(), i, n, this._config.wrap);
                if (s === i) return;
                const o = this._getItemIndex(s),
                    r = (e) => H.trigger(this._element, e, { relatedTarget: s, direction: this._orderToDirection(t), from: this._getItemIndex(i), to: o });
                if (r(ut).defaultPrevented) return;
                if (!i || !s) return;
                const a = Boolean(this._interval);
                this.pause(), (this._isSliding = !0), this._setActiveIndicatorElement(o), (this._activeElement = s);
                const l = n ? "carousel-item-start" : "carousel-item-end",
                    h = n ? "carousel-item-next" : "carousel-item-prev";
                s.classList.add(h),
                    u(s),
                    i.classList.add(l),
                    s.classList.add(l),
                    this._queueCallback(
                        () => {
                            s.classList.remove(l, h), s.classList.add(yt), i.classList.remove(yt, h, l), (this._isSliding = !1), r(dt);
                        },
                        i,
                        this._isAnimated()
                    ),
                    a && this.cycle();
            }
            _isAnimated() {
                return this._element.classList.contains("slide");
            }
            _getActive() {
                return q.findOne(kt, this._element);
            }
            _getItems() {
                return q.find(xt, this._element);
            }
            _clearInterval() {
                this._interval && (clearInterval(this._interval), (this._interval = null));
            }
            _directionToOrder(t) {
                return f() ? (t === ht ? lt : at) : t === ht ? at : lt;
            }
            _orderToDirection(t) {
                return f() ? (t === lt ? ht : ct) : t === lt ? ct : ht;
            }
            static jQueryInterface(t) {
                return this.each(function () {
                    const e = St.getOrCreateInstance(this, t);
                    if ("number" != typeof t) {
                        if ("string" == typeof t) {
                            if (void 0 === e[t] || t.startsWith("_") || "constructor" === t) throw new TypeError(`No method named "${t}"`);
                            e[t]();
                        }
                    } else e.to(t);
                });
            }
        }
        H.on(document, _t, "[data-bs-slide], [data-bs-slide-to]", function (t) {
            const e = q.getElementFromSelector(this);
            if (!e || !e.classList.contains(bt)) return;
            t.preventDefault();
            const i = St.getOrCreateInstance(e),
                n = this.getAttribute("data-bs-slide-to");
            return n ? (i.to(n), void i._maybeEnableCycle()) : "next" === R.getDataAttribute(this, "slide") ? (i.next(), void i._maybeEnableCycle()) : (i.prev(), void i._maybeEnableCycle());
        }),
            H.on(window, vt, () => {
                const t = q.find('[data-bs-ride="carousel"]');
                for (const e of t) St.getOrCreateInstance(e);
            }),
            g(St);
        const At = ".bs.collapse",
            Et = `show${At}`,
            It = `shown${At}`,
            Pt = `hide${At}`,
            Mt = `hidden${At}`,
            Ot = `click${At}.data-api`,
            Ht = "show",
            Lt = "collapse",
            Nt = "collapsing",
            Wt = `:scope .${Lt} .${Lt}`,
            Rt = '[data-bs-toggle="collapse"]',
            zt = { parent: null, toggle: !0 },
            jt = { parent: "(null|element)", toggle: "boolean" };
        class Ft extends j {
            constructor(t, e) {
                super(t, e), (this._isTransitioning = !1), (this._triggerArray = []);
                const i = q.find(Rt);
                for (const t of i) {
                    const e = q.getSelectorFromElement(t),
                        i = q.find(e).filter((t) => t === this._element);
                    null !== e && i.length && this._triggerArray.push(t);
                }
                this._initializeChildren(), this._config.parent || this._addAriaAndCollapsedClass(this._triggerArray, this._isShown()), this._config.toggle && this.toggle();
            }
            static get Default() {
                return zt;
            }
            static get DefaultType() {
                return jt;
            }
            static get NAME() {
                return "collapse";
            }
            toggle() {
                this._isShown() ? this.hide() : this.show();
            }
            show() {
                if (this._isTransitioning || this._isShown()) return;
                let t = [];
                if (
                    (this._config.parent &&
                        (t = this._getFirstLevelChildren(".collapse.show, .collapse.collapsing")
                            .filter((t) => t !== this._element)
                            .map((t) => Ft.getOrCreateInstance(t, { toggle: !1 }))),
                    t.length && t[0]._isTransitioning)
                )
                    return;
                if (H.trigger(this._element, Et).defaultPrevented) return;
                for (const e of t) e.hide();
                const e = this._getDimension();
                this._element.classList.remove(Lt), this._element.classList.add(Nt), (this._element.style[e] = 0), this._addAriaAndCollapsedClass(this._triggerArray, !0), (this._isTransitioning = !0);
                const i = `scroll${e[0].toUpperCase() + e.slice(1)}`;
                this._queueCallback(
                    () => {
                        (this._isTransitioning = !1), this._element.classList.remove(Nt), this._element.classList.add(Lt, Ht), (this._element.style[e] = ""), H.trigger(this._element, It);
                    },
                    this._element,
                    !0
                ),
                    (this._element.style[e] = `${this._element[i]}px`);
            }
            hide() {
                if (this._isTransitioning || !this._isShown()) return;
                if (H.trigger(this._element, Pt).defaultPrevented) return;
                const t = this._getDimension();
                (this._element.style[t] = `${this._element.getBoundingClientRect()[t]}px`), u(this._element), this._element.classList.add(Nt), this._element.classList.remove(Lt, Ht);
                for (const t of this._triggerArray) {
                    const e = q.getElementFromSelector(t);
                    e && !this._isShown(e) && this._addAriaAndCollapsedClass([t], !1);
                }
                (this._isTransitioning = !0),
                    (this._element.style[t] = ""),
                    this._queueCallback(
                        () => {
                            (this._isTransitioning = !1), this._element.classList.remove(Nt), this._element.classList.add(Lt), H.trigger(this._element, Mt);
                        },
                        this._element,
                        !0
                    );
            }
            _isShown(t = this._element) {
                return t.classList.contains(Ht);
            }
            _configAfterMerge(t) {
                return (t.toggle = Boolean(t.toggle)), (t.parent = r(t.parent)), t;
            }
            _getDimension() {
                return this._element.classList.contains("collapse-horizontal") ? "width" : "height";
            }
            _initializeChildren() {
                if (!this._config.parent) return;
                const t = this._getFirstLevelChildren(Rt);
                for (const e of t) {
                    const t = q.getElementFromSelector(e);
                    t && this._addAriaAndCollapsedClass([e], this._isShown(t));
                }
            }
            _getFirstLevelChildren(t) {
                const e = q.find(Wt, this._config.parent);
                return q.find(t, this._config.parent).filter((t) => !e.includes(t));
            }
            _addAriaAndCollapsedClass(t, e) {
                if (t.length) for (const i of t) i.classList.toggle("collapsed", !e), i.setAttribute("aria-expanded", e);
            }
            static jQueryInterface(t) {
                const e = {};
                return (
                    "string" == typeof t && /show|hide/.test(t) && (e.toggle = !1),
                    this.each(function () {
                        const i = Ft.getOrCreateInstance(this, e);
                        if ("string" == typeof t) {
                            if (void 0 === i[t]) throw new TypeError(`No method named "${t}"`);
                            i[t]();
                        }
                    })
                );
            }
        }
        H.on(document, Ot, Rt, function (t) {
            ("A" === t.target.tagName || (t.delegateTarget && "A" === t.delegateTarget.tagName)) && t.preventDefault();
            for (const t of q.getMultipleElementsFromSelector(this)) Ft.getOrCreateInstance(t, { toggle: !1 }).toggle();
        }),
            g(Ft);
        var qt = "top",
            Yt = "bottom",
            Bt = "right",
            $t = "left",
            Xt = "auto",
            Ut = [qt, Yt, Bt, $t],
            Kt = "start",
            Vt = "end",
            Gt = "clippingParents",
            Qt = "viewport",
            Jt = "popper",
            Zt = "reference",
            te = Ut.reduce(function (t, e) {
                return t.concat([e + "-" + Kt, e + "-" + Vt]);
            }, []),
            ee = [].concat(Ut, [Xt]).reduce(function (t, e) {
                return t.concat([e, e + "-" + Kt, e + "-" + Vt]);
            }, []),
            ie = "beforeRead",
            ne = "read",
            se = "afterRead",
            oe = "beforeMain",
            re = "main",
            ae = "afterMain",
            le = "beforeWrite",
            he = "write",
            ce = "afterWrite",
            ue = [ie, ne, se, oe, re, ae, le, he, ce];
        function de(t) {
            return t ? (t.nodeName || "").toLowerCase() : null;
        }
        function pe(t) {
            if (null == t) return window;
            if ("[object Window]" !== t.toString()) {
                var e = t.ownerDocument;
                return (e && e.defaultView) || window;
            }
            return t;
        }
        function fe(t) {
            return t instanceof pe(t).Element || t instanceof Element;
        }
        function ge(t) {
            return t instanceof pe(t).HTMLElement || t instanceof HTMLElement;
        }
        function me(t) {
            return "undefined" != typeof ShadowRoot && (t instanceof pe(t).ShadowRoot || t instanceof ShadowRoot);
        }
        const ve = {
            name: "applyStyles",
            enabled: !0,
            phase: "write",
            fn: function (t) {
                var e = t.state;
                Object.keys(e.elements).forEach(function (t) {
                    var i = e.styles[t] || {},
                        n = e.attributes[t] || {},
                        s = e.elements[t];
                    ge(s) &&
                        de(s) &&
                        (Object.assign(s.style, i),
                        Object.keys(n).forEach(function (t) {
                            var e = n[t];
                            !1 === e ? s.removeAttribute(t) : s.setAttribute(t, !0 === e ? "" : e);
                        }));
                });
            },
            effect: function (t) {
                var e = t.state,
                    i = { popper: { position: e.options.strategy, left: "0", top: "0", margin: "0" }, arrow: { position: "absolute" }, reference: {} };
                return (
                    Object.assign(e.elements.popper.style, i.popper),
                    (e.styles = i),
                    e.elements.arrow && Object.assign(e.elements.arrow.style, i.arrow),
                    function () {
                        Object.keys(e.elements).forEach(function (t) {
                            var n = e.elements[t],
                                s = e.attributes[t] || {},
                                o = Object.keys(e.styles.hasOwnProperty(t) ? e.styles[t] : i[t]).reduce(function (t, e) {
                                    return (t[e] = ""), t;
                                }, {});
                            ge(n) &&
                                de(n) &&
                                (Object.assign(n.style, o),
                                Object.keys(s).forEach(function (t) {
                                    n.removeAttribute(t);
                                }));
                        });
                    }
                );
            },
            requires: ["computeStyles"],
        };
        function _e(t) {
            return t.split("-")[0];
        }
        var be = Math.max,
            ye = Math.min,
            we = Math.round;
        function xe() {
            var t = navigator.userAgentData;
            return null != t && t.brands && Array.isArray(t.brands)
                ? t.brands
                      .map(function (t) {
                          return t.brand + "/" + t.version;
                      })
                      .join(" ")
                : navigator.userAgent;
        }
        function ke() {
            return !/^((?!chrome|android).)*safari/i.test(xe());
        }
        function Ce(t, e, i) {
            void 0 === e && (e = !1), void 0 === i && (i = !1);
            var n = t.getBoundingClientRect(),
                s = 1,
                o = 1;
            e && ge(t) && ((s = (t.offsetWidth > 0 && we(n.width) / t.offsetWidth) || 1), (o = (t.offsetHeight > 0 && we(n.height) / t.offsetHeight) || 1));
            var r = (fe(t) ? pe(t) : window).visualViewport,
                a = !ke() && i,
                l = (n.left + (a && r ? r.offsetLeft : 0)) / s,
                h = (n.top + (a && r ? r.offsetTop : 0)) / o,
                c = n.width / s,
                u = n.height / o;
            return { width: c, height: u, top: h, right: l + c, bottom: h + u, left: l, x: l, y: h };
        }
        function Te(t) {
            var e = Ce(t),
                i = t.offsetWidth,
                n = t.offsetHeight;
            return Math.abs(e.width - i) <= 1 && (i = e.width), Math.abs(e.height - n) <= 1 && (n = e.height), { x: t.offsetLeft, y: t.offsetTop, width: i, height: n };
        }
        function De(t, e) {
            var i = e.getRootNode && e.getRootNode();
            if (t.contains(e)) return !0;
            if (i && me(i)) {
                var n = e;
                do {
                    if (n && t.isSameNode(n)) return !0;
                    n = n.parentNode || n.host;
                } while (n);
            }
            return !1;
        }
        function Se(t) {
            return pe(t).getComputedStyle(t);
        }
        function Ae(t) {
            return ["table", "td", "th"].indexOf(de(t)) >= 0;
        }
        function Ee(t) {
            return ((fe(t) ? t.ownerDocument : t.document) || window.document).documentElement;
        }
        function Ie(t) {
            return "html" === de(t) ? t : t.assignedSlot || t.parentNode || (me(t) ? t.host : null) || Ee(t);
        }
        function Pe(t) {
            return ge(t) && "fixed" !== Se(t).position ? t.offsetParent : null;
        }
        function Me(t) {
            for (var e = pe(t), i = Pe(t); i && Ae(i) && "static" === Se(i).position; ) i = Pe(i);
            return i && ("html" === de(i) || ("body" === de(i) && "static" === Se(i).position))
                ? e
                : i ||
                      (function (t) {
                          var e = /firefox/i.test(xe());
                          if (/Trident/i.test(xe()) && ge(t) && "fixed" === Se(t).position) return null;
                          var i = Ie(t);
                          for (me(i) && (i = i.host); ge(i) && ["html", "body"].indexOf(de(i)) < 0; ) {
                              var n = Se(i);
                              if (
                                  "none" !== n.transform ||
                                  "none" !== n.perspective ||
                                  "paint" === n.contain ||
                                  -1 !== ["transform", "perspective"].indexOf(n.willChange) ||
                                  (e && "filter" === n.willChange) ||
                                  (e && n.filter && "none" !== n.filter)
                              )
                                  return i;
                              i = i.parentNode;
                          }
                          return null;
                      })(t) ||
                      e;
        }
        function Oe(t) {
            return ["top", "bottom"].indexOf(t) >= 0 ? "x" : "y";
        }
        function He(t, e, i) {
            return be(t, ye(e, i));
        }
        function Le(t) {
            return Object.assign({}, { top: 0, right: 0, bottom: 0, left: 0 }, t);
        }
        function Ne(t, e) {
            return e.reduce(function (e, i) {
                return (e[i] = t), e;
            }, {});
        }
        const We = {
            name: "arrow",
            enabled: !0,
            phase: "main",
            fn: function (t) {
                var e,
                    i = t.state,
                    n = t.name,
                    s = t.options,
                    o = i.elements.arrow,
                    r = i.modifiersData.popperOffsets,
                    a = _e(i.placement),
                    l = Oe(a),
                    h = [$t, Bt].indexOf(a) >= 0 ? "height" : "width";
                if (o && r) {
                    var c = (function (t, e) {
                            return Le("number" != typeof (t = "function" == typeof t ? t(Object.assign({}, e.rects, { placement: e.placement })) : t) ? t : Ne(t, Ut));
                        })(s.padding, i),
                        u = Te(o),
                        d = "y" === l ? qt : $t,
                        p = "y" === l ? Yt : Bt,
                        f = i.rects.reference[h] + i.rects.reference[l] - r[l] - i.rects.popper[h],
                        g = r[l] - i.rects.reference[l],
                        m = Me(o),
                        v = m ? ("y" === l ? m.clientHeight || 0 : m.clientWidth || 0) : 0,
                        _ = f / 2 - g / 2,
                        b = c[d],
                        y = v - u[h] - c[p],
                        w = v / 2 - u[h] / 2 + _,
                        x = He(b, w, y),
                        k = l;
                    i.modifiersData[n] = (((e = {})[k] = x), (e.centerOffset = x - w), e);
                }
            },
            effect: function (t) {
                var e = t.state,
                    i = t.options.element,
                    n = void 0 === i ? "[data-popper-arrow]" : i;
                null != n && ("string" != typeof n || (n = e.elements.popper.querySelector(n))) && De(e.elements.popper, n) && (e.elements.arrow = n);
            },
            requires: ["popperOffsets"],
            requiresIfExists: ["preventOverflow"],
        };
        function Re(t) {
            return t.split("-")[1];
        }
        var ze = { top: "auto", right: "auto", bottom: "auto", left: "auto" };
        function je(t) {
            var e,
                i = t.popper,
                n = t.popperRect,
                s = t.placement,
                o = t.variation,
                r = t.offsets,
                a = t.position,
                l = t.gpuAcceleration,
                h = t.adaptive,
                c = t.roundOffsets,
                u = t.isFixed,
                d = r.x,
                p = void 0 === d ? 0 : d,
                f = r.y,
                g = void 0 === f ? 0 : f,
                m = "function" == typeof c ? c({ x: p, y: g }) : { x: p, y: g };
            (p = m.x), (g = m.y);
            var v = r.hasOwnProperty("x"),
                _ = r.hasOwnProperty("y"),
                b = $t,
                y = qt,
                w = window;
            if (h) {
                var x = Me(i),
                    k = "clientHeight",
                    C = "clientWidth";
                x === pe(i) && "static" !== Se((x = Ee(i))).position && "absolute" === a && ((k = "scrollHeight"), (C = "scrollWidth")),
                    (s === qt || ((s === $t || s === Bt) && o === Vt)) && ((y = Yt), (g -= (u && x === w && w.visualViewport ? w.visualViewport.height : x[k]) - n.height), (g *= l ? 1 : -1)),
                    (s !== $t && ((s !== qt && s !== Yt) || o !== Vt)) || ((b = Bt), (p -= (u && x === w && w.visualViewport ? w.visualViewport.width : x[C]) - n.width), (p *= l ? 1 : -1));
            }
            var T,
                D = Object.assign({ position: a }, h && ze),
                S =
                    !0 === c
                        ? (function (t, e) {
                              var i = t.x,
                                  n = t.y,
                                  s = e.devicePixelRatio || 1;
                              return { x: we(i * s) / s || 0, y: we(n * s) / s || 0 };
                          })({ x: p, y: g }, pe(i))
                        : { x: p, y: g };
            return (
                (p = S.x),
                (g = S.y),
                l
                    ? Object.assign({}, D, (((T = {})[y] = _ ? "0" : ""), (T[b] = v ? "0" : ""), (T.transform = (w.devicePixelRatio || 1) <= 1 ? "translate(" + p + "px, " + g + "px)" : "translate3d(" + p + "px, " + g + "px, 0)"), T))
                    : Object.assign({}, D, (((e = {})[y] = _ ? g + "px" : ""), (e[b] = v ? p + "px" : ""), (e.transform = ""), e))
            );
        }
        const Fe = {
            name: "computeStyles",
            enabled: !0,
            phase: "beforeWrite",
            fn: function (t) {
                var e = t.state,
                    i = t.options,
                    n = i.gpuAcceleration,
                    s = void 0 === n || n,
                    o = i.adaptive,
                    r = void 0 === o || o,
                    a = i.roundOffsets,
                    l = void 0 === a || a,
                    h = { placement: _e(e.placement), variation: Re(e.placement), popper: e.elements.popper, popperRect: e.rects.popper, gpuAcceleration: s, isFixed: "fixed" === e.options.strategy };
                null != e.modifiersData.popperOffsets &&
                    (e.styles.popper = Object.assign({}, e.styles.popper, je(Object.assign({}, h, { offsets: e.modifiersData.popperOffsets, position: e.options.strategy, adaptive: r, roundOffsets: l })))),
                    null != e.modifiersData.arrow && (e.styles.arrow = Object.assign({}, e.styles.arrow, je(Object.assign({}, h, { offsets: e.modifiersData.arrow, position: "absolute", adaptive: !1, roundOffsets: l })))),
                    (e.attributes.popper = Object.assign({}, e.attributes.popper, { "data-popper-placement": e.placement }));
            },
            data: {},
        };
        var qe = { passive: !0 };
        const Ye = {
            name: "eventListeners",
            enabled: !0,
            phase: "write",
            fn: function () {},
            effect: function (t) {
                var e = t.state,
                    i = t.instance,
                    n = t.options,
                    s = n.scroll,
                    o = void 0 === s || s,
                    r = n.resize,
                    a = void 0 === r || r,
                    l = pe(e.elements.popper),
                    h = [].concat(e.scrollParents.reference, e.scrollParents.popper);
                return (
                    o &&
                        h.forEach(function (t) {
                            t.addEventListener("scroll", i.update, qe);
                        }),
                    a && l.addEventListener("resize", i.update, qe),
                    function () {
                        o &&
                            h.forEach(function (t) {
                                t.removeEventListener("scroll", i.update, qe);
                            }),
                            a && l.removeEventListener("resize", i.update, qe);
                    }
                );
            },
            data: {},
        };
        var Be = { left: "right", right: "left", bottom: "top", top: "bottom" };
        function $e(t) {
            return t.replace(/left|right|bottom|top/g, function (t) {
                return Be[t];
            });
        }
        var Xe = { start: "end", end: "start" };
        function Ue(t) {
            return t.replace(/start|end/g, function (t) {
                return Xe[t];
            });
        }
        function Ke(t) {
            var e = pe(t);
            return { scrollLeft: e.pageXOffset, scrollTop: e.pageYOffset };
        }
        function Ve(t) {
            return Ce(Ee(t)).left + Ke(t).scrollLeft;
        }
        function Ge(t) {
            var e = Se(t),
                i = e.overflow,
                n = e.overflowX,
                s = e.overflowY;
            return /auto|scroll|overlay|hidden/.test(i + s + n);
        }
        function Qe(t) {
            return ["html", "body", "#document"].indexOf(de(t)) >= 0 ? t.ownerDocument.body : ge(t) && Ge(t) ? t : Qe(Ie(t));
        }
        function Je(t, e) {
            var i;
            void 0 === e && (e = []);
            var n = Qe(t),
                s = n === (null == (i = t.ownerDocument) ? void 0 : i.body),
                o = pe(n),
                r = s ? [o].concat(o.visualViewport || [], Ge(n) ? n : []) : n,
                a = e.concat(r);
            return s ? a : a.concat(Je(Ie(r)));
        }
        function Ze(t) {
            return Object.assign({}, t, { left: t.x, top: t.y, right: t.x + t.width, bottom: t.y + t.height });
        }
        function ti(t, e, i) {
            return e === Qt
                ? Ze(
                      (function (t, e) {
                          var i = pe(t),
                              n = Ee(t),
                              s = i.visualViewport,
                              o = n.clientWidth,
                              r = n.clientHeight,
                              a = 0,
                              l = 0;
                          if (s) {
                              (o = s.width), (r = s.height);
                              var h = ke();
                              (h || (!h && "fixed" === e)) && ((a = s.offsetLeft), (l = s.offsetTop));
                          }
                          return { width: o, height: r, x: a + Ve(t), y: l };
                      })(t, i)
                  )
                : fe(e)
                ? (function (t, e) {
                      var i = Ce(t, !1, "fixed" === e);
                      return (
                          (i.top = i.top + t.clientTop),
                          (i.left = i.left + t.clientLeft),
                          (i.bottom = i.top + t.clientHeight),
                          (i.right = i.left + t.clientWidth),
                          (i.width = t.clientWidth),
                          (i.height = t.clientHeight),
                          (i.x = i.left),
                          (i.y = i.top),
                          i
                      );
                  })(e, i)
                : Ze(
                      (function (t) {
                          var e,
                              i = Ee(t),
                              n = Ke(t),
                              s = null == (e = t.ownerDocument) ? void 0 : e.body,
                              o = be(i.scrollWidth, i.clientWidth, s ? s.scrollWidth : 0, s ? s.clientWidth : 0),
                              r = be(i.scrollHeight, i.clientHeight, s ? s.scrollHeight : 0, s ? s.clientHeight : 0),
                              a = -n.scrollLeft + Ve(t),
                              l = -n.scrollTop;
                          return "rtl" === Se(s || i).direction && (a += be(i.clientWidth, s ? s.clientWidth : 0) - o), { width: o, height: r, x: a, y: l };
                      })(Ee(t))
                  );
        }
        function ei(t) {
            var e,
                i = t.reference,
                n = t.element,
                s = t.placement,
                o = s ? _e(s) : null,
                r = s ? Re(s) : null,
                a = i.x + i.width / 2 - n.width / 2,
                l = i.y + i.height / 2 - n.height / 2;
            switch (o) {
                case qt:
                    e = { x: a, y: i.y - n.height };
                    break;
                case Yt:
                    e = { x: a, y: i.y + i.height };
                    break;
                case Bt:
                    e = { x: i.x + i.width, y: l };
                    break;
                case $t:
                    e = { x: i.x - n.width, y: l };
                    break;
                default:
                    e = { x: i.x, y: i.y };
            }
            var h = o ? Oe(o) : null;
            if (null != h) {
                var c = "y" === h ? "height" : "width";
                switch (r) {
                    case Kt:
                        e[h] = e[h] - (i[c] / 2 - n[c] / 2);
                        break;
                    case Vt:
                        e[h] = e[h] + (i[c] / 2 - n[c] / 2);
                }
            }
            return e;
        }
        function ii(t, e) {
            void 0 === e && (e = {});
            var i = e,
                n = i.placement,
                s = void 0 === n ? t.placement : n,
                o = i.strategy,
                r = void 0 === o ? t.strategy : o,
                a = i.boundary,
                l = void 0 === a ? Gt : a,
                h = i.rootBoundary,
                c = void 0 === h ? Qt : h,
                u = i.elementContext,
                d = void 0 === u ? Jt : u,
                p = i.altBoundary,
                f = void 0 !== p && p,
                g = i.padding,
                m = void 0 === g ? 0 : g,
                v = Le("number" != typeof m ? m : Ne(m, Ut)),
                _ = d === Jt ? Zt : Jt,
                b = t.rects.popper,
                y = t.elements[f ? _ : d],
                w = (function (t, e, i, n) {
                    var s =
                            "clippingParents" === e
                                ? (function (t) {
                                      var e = Je(Ie(t)),
                                          i = ["absolute", "fixed"].indexOf(Se(t).position) >= 0 && ge(t) ? Me(t) : t;
                                      return fe(i)
                                          ? e.filter(function (t) {
                                                return fe(t) && De(t, i) && "body" !== de(t);
                                            })
                                          : [];
                                  })(t)
                                : [].concat(e),
                        o = [].concat(s, [i]),
                        r = o[0],
                        a = o.reduce(function (e, i) {
                            var s = ti(t, i, n);
                            return (e.top = be(s.top, e.top)), (e.right = ye(s.right, e.right)), (e.bottom = ye(s.bottom, e.bottom)), (e.left = be(s.left, e.left)), e;
                        }, ti(t, r, n));
                    return (a.width = a.right - a.left), (a.height = a.bottom - a.top), (a.x = a.left), (a.y = a.top), a;
                })(fe(y) ? y : y.contextElement || Ee(t.elements.popper), l, c, r),
                x = Ce(t.elements.reference),
                k = ei({ reference: x, element: b, strategy: "absolute", placement: s }),
                C = Ze(Object.assign({}, b, k)),
                T = d === Jt ? C : x,
                D = { top: w.top - T.top + v.top, bottom: T.bottom - w.bottom + v.bottom, left: w.left - T.left + v.left, right: T.right - w.right + v.right },
                S = t.modifiersData.offset;
            if (d === Jt && S) {
                var A = S[s];
                Object.keys(D).forEach(function (t) {
                    var e = [Bt, Yt].indexOf(t) >= 0 ? 1 : -1,
                        i = [qt, Yt].indexOf(t) >= 0 ? "y" : "x";
                    D[t] += A[i] * e;
                });
            }
            return D;
        }
        const ni = {
            name: "flip",
            enabled: !0,
            phase: "main",
            fn: function (t) {
                var e = t.state,
                    i = t.options,
                    n = t.name;
                if (!e.modifiersData[n]._skip) {
                    for (
                        var s = i.mainAxis,
                            o = void 0 === s || s,
                            r = i.altAxis,
                            a = void 0 === r || r,
                            l = i.fallbackPlacements,
                            h = i.padding,
                            c = i.boundary,
                            u = i.rootBoundary,
                            d = i.altBoundary,
                            p = i.flipVariations,
                            f = void 0 === p || p,
                            g = i.allowedAutoPlacements,
                            m = e.options.placement,
                            v = _e(m),
                            _ =
                                l ||
                                (v !== m && f
                                    ? (function (t) {
                                          if (_e(t) === Xt) return [];
                                          var e = $e(t);
                                          return [Ue(t), e, Ue(e)];
                                      })(m)
                                    : [$e(m)]),
                            b = [m].concat(_).reduce(function (t, i) {
                                return t.concat(
                                    _e(i) === Xt
                                        ? (function (t, e) {
                                              void 0 === e && (e = {});
                                              var i = e,
                                                  n = i.placement,
                                                  s = i.boundary,
                                                  o = i.rootBoundary,
                                                  r = i.padding,
                                                  a = i.flipVariations,
                                                  l = i.allowedAutoPlacements,
                                                  h = void 0 === l ? ee : l,
                                                  c = Re(n),
                                                  u = c
                                                      ? a
                                                          ? te
                                                          : te.filter(function (t) {
                                                                return Re(t) === c;
                                                            })
                                                      : Ut,
                                                  d = u.filter(function (t) {
                                                      return h.indexOf(t) >= 0;
                                                  });
                                              0 === d.length && (d = u);
                                              var p = d.reduce(function (e, i) {
                                                  return (e[i] = ii(t, { placement: i, boundary: s, rootBoundary: o, padding: r })[_e(i)]), e;
                                              }, {});
                                              return Object.keys(p).sort(function (t, e) {
                                                  return p[t] - p[e];
                                              });
                                          })(e, { placement: i, boundary: c, rootBoundary: u, padding: h, flipVariations: f, allowedAutoPlacements: g })
                                        : i
                                );
                            }, []),
                            y = e.rects.reference,
                            w = e.rects.popper,
                            x = new Map(),
                            k = !0,
                            C = b[0],
                            T = 0;
                        T < b.length;
                        T++
                    ) {
                        var D = b[T],
                            S = _e(D),
                            A = Re(D) === Kt,
                            E = [qt, Yt].indexOf(S) >= 0,
                            I = E ? "width" : "height",
                            P = ii(e, { placement: D, boundary: c, rootBoundary: u, altBoundary: d, padding: h }),
                            M = E ? (A ? Bt : $t) : A ? Yt : qt;
                        y[I] > w[I] && (M = $e(M));
                        var O = $e(M),
                            H = [];
                        if (
                            (o && H.push(P[S] <= 0),
                            a && H.push(P[M] <= 0, P[O] <= 0),
                            H.every(function (t) {
                                return t;
                            }))
                        ) {
                            (C = D), (k = !1);
                            break;
                        }
                        x.set(D, H);
                    }
                    if (k)
                        for (
                            var L = function (t) {
                                    var e = b.find(function (e) {
                                        var i = x.get(e);
                                        if (i)
                                            return i.slice(0, t).every(function (t) {
                                                return t;
                                            });
                                    });
                                    if (e) return (C = e), "break";
                                },
                                N = f ? 3 : 1;
                            N > 0 && "break" !== L(N);
                            N--
                        );
                    e.placement !== C && ((e.modifiersData[n]._skip = !0), (e.placement = C), (e.reset = !0));
                }
            },
            requiresIfExists: ["offset"],
            data: { _skip: !1 },
        };
        function si(t, e, i) {
            return void 0 === i && (i = { x: 0, y: 0 }), { top: t.top - e.height - i.y, right: t.right - e.width + i.x, bottom: t.bottom - e.height + i.y, left: t.left - e.width - i.x };
        }
        function oi(t) {
            return [qt, Bt, Yt, $t].some(function (e) {
                return t[e] >= 0;
            });
        }
        const ri = {
                name: "hide",
                enabled: !0,
                phase: "main",
                requiresIfExists: ["preventOverflow"],
                fn: function (t) {
                    var e = t.state,
                        i = t.name,
                        n = e.rects.reference,
                        s = e.rects.popper,
                        o = e.modifiersData.preventOverflow,
                        r = ii(e, { elementContext: "reference" }),
                        a = ii(e, { altBoundary: !0 }),
                        l = si(r, n),
                        h = si(a, s, o),
                        c = oi(l),
                        u = oi(h);
                    (e.modifiersData[i] = { referenceClippingOffsets: l, popperEscapeOffsets: h, isReferenceHidden: c, hasPopperEscaped: u }),
                        (e.attributes.popper = Object.assign({}, e.attributes.popper, { "data-popper-reference-hidden": c, "data-popper-escaped": u }));
                },
            },
            ai = {
                name: "offset",
                enabled: !0,
                phase: "main",
                requires: ["popperOffsets"],
                fn: function (t) {
                    var e = t.state,
                        i = t.options,
                        n = t.name,
                        s = i.offset,
                        o = void 0 === s ? [0, 0] : s,
                        r = ee.reduce(function (t, i) {
                            return (
                                (t[i] = (function (t, e, i) {
                                    var n = _e(t),
                                        s = [$t, qt].indexOf(n) >= 0 ? -1 : 1,
                                        o = "function" == typeof i ? i(Object.assign({}, e, { placement: t })) : i,
                                        r = o[0],
                                        a = o[1];
                                    return (r = r || 0), (a = (a || 0) * s), [$t, Bt].indexOf(n) >= 0 ? { x: a, y: r } : { x: r, y: a };
                                })(i, e.rects, o)),
                                t
                            );
                        }, {}),
                        a = r[e.placement],
                        l = a.x,
                        h = a.y;
                    null != e.modifiersData.popperOffsets && ((e.modifiersData.popperOffsets.x += l), (e.modifiersData.popperOffsets.y += h)), (e.modifiersData[n] = r);
                },
            },
            li = {
                name: "popperOffsets",
                enabled: !0,
                phase: "read",
                fn: function (t) {
                    var e = t.state,
                        i = t.name;
                    e.modifiersData[i] = ei({ reference: e.rects.reference, element: e.rects.popper, strategy: "absolute", placement: e.placement });
                },
                data: {},
            },
            hi = {
                name: "preventOverflow",
                enabled: !0,
                phase: "main",
                fn: function (t) {
                    var e = t.state,
                        i = t.options,
                        n = t.name,
                        s = i.mainAxis,
                        o = void 0 === s || s,
                        r = i.altAxis,
                        a = void 0 !== r && r,
                        l = i.boundary,
                        h = i.rootBoundary,
                        c = i.altBoundary,
                        u = i.padding,
                        d = i.tether,
                        p = void 0 === d || d,
                        f = i.tetherOffset,
                        g = void 0 === f ? 0 : f,
                        m = ii(e, { boundary: l, rootBoundary: h, padding: u, altBoundary: c }),
                        v = _e(e.placement),
                        _ = Re(e.placement),
                        b = !_,
                        y = Oe(v),
                        w = "x" === y ? "y" : "x",
                        x = e.modifiersData.popperOffsets,
                        k = e.rects.reference,
                        C = e.rects.popper,
                        T = "function" == typeof g ? g(Object.assign({}, e.rects, { placement: e.placement })) : g,
                        D = "number" == typeof T ? { mainAxis: T, altAxis: T } : Object.assign({ mainAxis: 0, altAxis: 0 }, T),
                        S = e.modifiersData.offset ? e.modifiersData.offset[e.placement] : null,
                        A = { x: 0, y: 0 };
                    if (x) {
                        if (o) {
                            var E,
                                I = "y" === y ? qt : $t,
                                P = "y" === y ? Yt : Bt,
                                M = "y" === y ? "height" : "width",
                                O = x[y],
                                H = O + m[I],
                                L = O - m[P],
                                N = p ? -C[M] / 2 : 0,
                                W = _ === Kt ? k[M] : C[M],
                                R = _ === Kt ? -C[M] : -k[M],
                                z = e.elements.arrow,
                                j = p && z ? Te(z) : { width: 0, height: 0 },
                                F = e.modifiersData["arrow#persistent"] ? e.modifiersData["arrow#persistent"].padding : { top: 0, right: 0, bottom: 0, left: 0 },
                                q = F[I],
                                Y = F[P],
                                B = He(0, k[M], j[M]),
                                $ = b ? k[M] / 2 - N - B - q - D.mainAxis : W - B - q - D.mainAxis,
                                X = b ? -k[M] / 2 + N + B + Y + D.mainAxis : R + B + Y + D.mainAxis,
                                U = e.elements.arrow && Me(e.elements.arrow),
                                K = U ? ("y" === y ? U.clientTop || 0 : U.clientLeft || 0) : 0,
                                V = null != (E = null == S ? void 0 : S[y]) ? E : 0,
                                G = O + X - V,
                                Q = He(p ? ye(H, O + $ - V - K) : H, O, p ? be(L, G) : L);
                            (x[y] = Q), (A[y] = Q - O);
                        }
                        if (a) {
                            var J,
                                Z = "x" === y ? qt : $t,
                                tt = "x" === y ? Yt : Bt,
                                et = x[w],
                                it = "y" === w ? "height" : "width",
                                nt = et + m[Z],
                                st = et - m[tt],
                                ot = -1 !== [qt, $t].indexOf(v),
                                rt = null != (J = null == S ? void 0 : S[w]) ? J : 0,
                                at = ot ? nt : et - k[it] - C[it] - rt + D.altAxis,
                                lt = ot ? et + k[it] + C[it] - rt - D.altAxis : st,
                                ht =
                                    p && ot
                                        ? (function (t, e, i) {
                                              var n = He(t, e, i);
                                              return n > i ? i : n;
                                          })(at, et, lt)
                                        : He(p ? at : nt, et, p ? lt : st);
                            (x[w] = ht), (A[w] = ht - et);
                        }
                        e.modifiersData[n] = A;
                    }
                },
                requiresIfExists: ["offset"],
            };
        function ci(t, e, i) {
            void 0 === i && (i = !1);
            var n,
                s,
                o = ge(e),
                r =
                    ge(e) &&
                    (function (t) {
                        var e = t.getBoundingClientRect(),
                            i = we(e.width) / t.offsetWidth || 1,
                            n = we(e.height) / t.offsetHeight || 1;
                        return 1 !== i || 1 !== n;
                    })(e),
                a = Ee(e),
                l = Ce(t, r, i),
                h = { scrollLeft: 0, scrollTop: 0 },
                c = { x: 0, y: 0 };
            return (
                (o || (!o && !i)) &&
                    (("body" !== de(e) || Ge(a)) && (h = (n = e) !== pe(n) && ge(n) ? { scrollLeft: (s = n).scrollLeft, scrollTop: s.scrollTop } : Ke(n)),
                    ge(e) ? (((c = Ce(e, !0)).x += e.clientLeft), (c.y += e.clientTop)) : a && (c.x = Ve(a))),
                { x: l.left + h.scrollLeft - c.x, y: l.top + h.scrollTop - c.y, width: l.width, height: l.height }
            );
        }
        function ui(t) {
            var e = new Map(),
                i = new Set(),
                n = [];
            function s(t) {
                i.add(t.name),
                    [].concat(t.requires || [], t.requiresIfExists || []).forEach(function (t) {
                        if (!i.has(t)) {
                            var n = e.get(t);
                            n && s(n);
                        }
                    }),
                    n.push(t);
            }
            return (
                t.forEach(function (t) {
                    e.set(t.name, t);
                }),
                t.forEach(function (t) {
                    i.has(t.name) || s(t);
                }),
                n
            );
        }
        var di = { placement: "bottom", modifiers: [], strategy: "absolute" };
        function pi() {
            for (var t = arguments.length, e = new Array(t), i = 0; i < t; i++) e[i] = arguments[i];
            return !e.some(function (t) {
                return !(t && "function" == typeof t.getBoundingClientRect);
            });
        }
        function fi(t) {
            void 0 === t && (t = {});
            var e = t,
                i = e.defaultModifiers,
                n = void 0 === i ? [] : i,
                s = e.defaultOptions,
                o = void 0 === s ? di : s;
            return function (t, e, i) {
                void 0 === i && (i = o);
                var s,
                    r,
                    a = { placement: "bottom", orderedModifiers: [], options: Object.assign({}, di, o), modifiersData: {}, elements: { reference: t, popper: e }, attributes: {}, styles: {} },
                    l = [],
                    h = !1,
                    c = {
                        state: a,
                        setOptions: function (i) {
                            var s = "function" == typeof i ? i(a.options) : i;
                            u(), (a.options = Object.assign({}, o, a.options, s)), (a.scrollParents = { reference: fe(t) ? Je(t) : t.contextElement ? Je(t.contextElement) : [], popper: Je(e) });
                            var r,
                                h,
                                d = (function (t) {
                                    var e = ui(t);
                                    return ue.reduce(function (t, i) {
                                        return t.concat(
                                            e.filter(function (t) {
                                                return t.phase === i;
                                            })
                                        );
                                    }, []);
                                })(
                                    ((r = [].concat(n, a.options.modifiers)),
                                    (h = r.reduce(function (t, e) {
                                        var i = t[e.name];
                                        return (t[e.name] = i ? Object.assign({}, i, e, { options: Object.assign({}, i.options, e.options), data: Object.assign({}, i.data, e.data) }) : e), t;
                                    }, {})),
                                    Object.keys(h).map(function (t) {
                                        return h[t];
                                    }))
                                );
                            return (
                                (a.orderedModifiers = d.filter(function (t) {
                                    return t.enabled;
                                })),
                                a.orderedModifiers.forEach(function (t) {
                                    var e = t.name,
                                        i = t.options,
                                        n = void 0 === i ? {} : i,
                                        s = t.effect;
                                    if ("function" == typeof s) {
                                        var o = s({ state: a, name: e, instance: c, options: n });
                                        l.push(o || function () {});
                                    }
                                }),
                                c.update()
                            );
                        },
                        forceUpdate: function () {
                            if (!h) {
                                var t = a.elements,
                                    e = t.reference,
                                    i = t.popper;
                                if (pi(e, i)) {
                                    (a.rects = { reference: ci(e, Me(i), "fixed" === a.options.strategy), popper: Te(i) }),
                                        (a.reset = !1),
                                        (a.placement = a.options.placement),
                                        a.orderedModifiers.forEach(function (t) {
                                            return (a.modifiersData[t.name] = Object.assign({}, t.data));
                                        });
                                    for (var n = 0; n < a.orderedModifiers.length; n++)
                                        if (!0 !== a.reset) {
                                            var s = a.orderedModifiers[n],
                                                o = s.fn,
                                                r = s.options,
                                                l = void 0 === r ? {} : r,
                                                u = s.name;
                                            "function" == typeof o && (a = o({ state: a, options: l, name: u, instance: c }) || a);
                                        } else (a.reset = !1), (n = -1);
                                }
                            }
                        },
                        update:
                            ((s = function () {
                                return new Promise(function (t) {
                                    c.forceUpdate(), t(a);
                                });
                            }),
                            function () {
                                return (
                                    r ||
                                        (r = new Promise(function (t) {
                                            Promise.resolve().then(function () {
                                                (r = void 0), t(s());
                                            });
                                        })),
                                    r
                                );
                            }),
                        destroy: function () {
                            u(), (h = !0);
                        },
                    };
                if (!pi(t, e)) return c;
                function u() {
                    l.forEach(function (t) {
                        return t();
                    }),
                        (l = []);
                }
                return (
                    c.setOptions(i).then(function (t) {
                        !h && i.onFirstUpdate && i.onFirstUpdate(t);
                    }),
                    c
                );
            };
        }
        var gi = fi(),
            mi = fi({ defaultModifiers: [Ye, li, Fe, ve] }),
            vi = fi({ defaultModifiers: [Ye, li, Fe, ve, ai, ni, hi, We, ri] });
        const _i = Object.freeze(
                Object.defineProperty(
                    {
                        __proto__: null,
                        afterMain: ae,
                        afterRead: se,
                        afterWrite: ce,
                        applyStyles: ve,
                        arrow: We,
                        auto: Xt,
                        basePlacements: Ut,
                        beforeMain: oe,
                        beforeRead: ie,
                        beforeWrite: le,
                        bottom: Yt,
                        clippingParents: Gt,
                        computeStyles: Fe,
                        createPopper: vi,
                        createPopperBase: gi,
                        createPopperLite: mi,
                        detectOverflow: ii,
                        end: Vt,
                        eventListeners: Ye,
                        flip: ni,
                        hide: ri,
                        left: $t,
                        main: re,
                        modifierPhases: ue,
                        offset: ai,
                        placements: ee,
                        popper: Jt,
                        popperGenerator: fi,
                        popperOffsets: li,
                        preventOverflow: hi,
                        read: ne,
                        reference: Zt,
                        right: Bt,
                        start: Kt,
                        top: qt,
                        variationPlacements: te,
                        viewport: Qt,
                        write: he,
                    },
                    Symbol.toStringTag,
                    { value: "Module" }
                )
            ),
            bi = "dropdown",
            yi = ".bs.dropdown",
            wi = ".data-api",
            xi = "ArrowUp",
            ki = "ArrowDown",
            Ci = `hide${yi}`,
            Ti = `hidden${yi}`,
            Di = `show${yi}`,
            Si = `shown${yi}`,
            Ai = `click${yi}${wi}`,
            Ei = `keydown${yi}${wi}`,
            Ii = `keyup${yi}${wi}`,
            Pi = "show",
            Mi = '[data-bs-toggle="dropdown"]:not(.disabled):not(:disabled)',
            Oi = `${Mi}.${Pi}`,
            Hi = ".dropdown-menu",
            Li = f() ? "top-end" : "top-start",
            Ni = f() ? "top-start" : "top-end",
            Wi = f() ? "bottom-end" : "bottom-start",
            Ri = f() ? "bottom-start" : "bottom-end",
            zi = f() ? "left-start" : "right-start",
            ji = f() ? "right-start" : "left-start",
            Fi = { autoClose: !0, boundary: "clippingParents", display: "dynamic", offset: [0, 2], popperConfig: null, reference: "toggle" },
            qi = { autoClose: "(boolean|string)", boundary: "(string|element)", display: "string", offset: "(array|string|function)", popperConfig: "(null|object|function)", reference: "(string|element|object)" };
        class Yi extends j {
            constructor(t, e) {
                super(t, e),
                    (this._popper = null),
                    (this._parent = this._element.parentNode),
                    (this._menu = q.next(this._element, Hi)[0] || q.prev(this._element, Hi)[0] || q.findOne(Hi, this._parent)),
                    (this._inNavbar = this._detectNavbar());
            }
            static get Default() {
                return Fi;
            }
            static get DefaultType() {
                return qi;
            }
            static get NAME() {
                return bi;
            }
            toggle() {
                return this._isShown() ? this.hide() : this.show();
            }
            show() {
                if (l(this._element) || this._isShown()) return;
                const t = { relatedTarget: this._element };
                if (!H.trigger(this._element, Di, t).defaultPrevented) {
                    if ((this._createPopper(), "ontouchstart" in document.documentElement && !this._parent.closest(".navbar-nav"))) for (const t of [].concat(...document.body.children)) H.on(t, "mouseover", c);
                    this._element.focus(), this._element.setAttribute("aria-expanded", !0), this._menu.classList.add(Pi), this._element.classList.add(Pi), H.trigger(this._element, Si, t);
                }
            }
            hide() {
                if (l(this._element) || !this._isShown()) return;
                const t = { relatedTarget: this._element };
                this._completeHide(t);
            }
            dispose() {
                this._popper && this._popper.destroy(), super.dispose();
            }
            update() {
                (this._inNavbar = this._detectNavbar()), this._popper && this._popper.update();
            }
            _completeHide(t) {
                if (!H.trigger(this._element, Ci, t).defaultPrevented) {
                    if ("ontouchstart" in document.documentElement) for (const t of [].concat(...document.body.children)) H.off(t, "mouseover", c);
                    this._popper && this._popper.destroy(),
                        this._menu.classList.remove(Pi),
                        this._element.classList.remove(Pi),
                        this._element.setAttribute("aria-expanded", "false"),
                        R.removeDataAttribute(this._menu, "popper"),
                        H.trigger(this._element, Ti, t);
                }
            }
            _getConfig(t) {
                if ("object" == typeof (t = super._getConfig(t)).reference && !o(t.reference) && "function" != typeof t.reference.getBoundingClientRect)
                    throw new TypeError(`${bi.toUpperCase()}: Option "reference" provided type "object" without a required "getBoundingClientRect" method.`);
                return t;
            }
            _createPopper() {
                if (void 0 === _i) throw new TypeError("Bootstrap's dropdowns require Popper (https://popper.js.org)");
                let t = this._element;
                "parent" === this._config.reference ? (t = this._parent) : o(this._config.reference) ? (t = r(this._config.reference)) : "object" == typeof this._config.reference && (t = this._config.reference);
                const e = this._getPopperConfig();
                this._popper = vi(t, this._menu, e);
            }
            _isShown() {
                return this._menu.classList.contains(Pi);
            }
            _getPlacement() {
                const t = this._parent;
                if (t.classList.contains("dropend")) return zi;
                if (t.classList.contains("dropstart")) return ji;
                if (t.classList.contains("dropup-center")) return "top";
                if (t.classList.contains("dropdown-center")) return "bottom";
                const e = "end" === getComputedStyle(this._menu).getPropertyValue("--bs-position").trim();
                return t.classList.contains("dropup") ? (e ? Ni : Li) : e ? Ri : Wi;
            }
            _detectNavbar() {
                return null !== this._element.closest(".navbar");
            }
            _getOffset() {
                const { offset: t } = this._config;
                return "string" == typeof t ? t.split(",").map((t) => Number.parseInt(t, 10)) : "function" == typeof t ? (e) => t(e, this._element) : t;
            }
            _getPopperConfig() {
                const t = {
                    placement: this._getPlacement(),
                    modifiers: [
                        { name: "preventOverflow", options: { boundary: this._config.boundary } },
                        { name: "offset", options: { offset: this._getOffset() } },
                    ],
                };
                return (this._inNavbar || "static" === this._config.display) && (R.setDataAttribute(this._menu, "popper", "static"), (t.modifiers = [{ name: "applyStyles", enabled: !1 }])), { ...t, ...m(this._config.popperConfig, [t]) };
            }
            _selectMenuItem({ key: t, target: e }) {
                const i = q.find(".dropdown-menu .dropdown-item:not(.disabled):not(:disabled)", this._menu).filter((t) => a(t));
                i.length && _(i, e, t === ki, !i.includes(e)).focus();
            }
            static jQueryInterface(t) {
                return this.each(function () {
                    const e = Yi.getOrCreateInstance(this, t);
                    if ("string" == typeof t) {
                        if (void 0 === e[t]) throw new TypeError(`No method named "${t}"`);
                        e[t]();
                    }
                });
            }
            static clearMenus(t) {
                if (2 === t.button || ("keyup" === t.type && "Tab" !== t.key)) return;
                const e = q.find(Oi);
                for (const i of e) {
                    const e = Yi.getInstance(i);
                    if (!e || !1 === e._config.autoClose) continue;
                    const n = t.composedPath(),
                        s = n.includes(e._menu);
                    if (n.includes(e._element) || ("inside" === e._config.autoClose && !s) || ("outside" === e._config.autoClose && s)) continue;
                    if (e._menu.contains(t.target) && (("keyup" === t.type && "Tab" === t.key) || /input|select|option|textarea|form/i.test(t.target.tagName))) continue;
                    const o = { relatedTarget: e._element };
                    "click" === t.type && (o.clickEvent = t), e._completeHide(o);
                }
            }
            static dataApiKeydownHandler(t) {
                const e = /input|textarea/i.test(t.target.tagName),
                    i = "Escape" === t.key,
                    n = [xi, ki].includes(t.key);
                if (!n && !i) return;
                if (e && !i) return;
                t.preventDefault();
                const s = this.matches(Mi) ? this : q.prev(this, Mi)[0] || q.next(this, Mi)[0] || q.findOne(Mi, t.delegateTarget.parentNode),
                    o = Yi.getOrCreateInstance(s);
                if (n) return t.stopPropagation(), o.show(), void o._selectMenuItem(t);
                o._isShown() && (t.stopPropagation(), o.hide(), s.focus());
            }
        }
        H.on(document, Ei, Mi, Yi.dataApiKeydownHandler),
            H.on(document, Ei, Hi, Yi.dataApiKeydownHandler),
            H.on(document, Ai, Yi.clearMenus),
            H.on(document, Ii, Yi.clearMenus),
            H.on(document, Ai, Mi, function (t) {
                t.preventDefault(), Yi.getOrCreateInstance(this).toggle();
            }),
            g(Yi);
        const Bi = "backdrop",
            $i = "show",
            Xi = `mousedown.bs.${Bi}`,
            Ui = { className: "modal-backdrop", clickCallback: null, isAnimated: !1, isVisible: !0, rootElement: "body" },
            Ki = { className: "string", clickCallback: "(function|null)", isAnimated: "boolean", isVisible: "boolean", rootElement: "(element|string)" };
        class Vi extends z {
            constructor(t) {
                super(), (this._config = this._getConfig(t)), (this._isAppended = !1), (this._element = null);
            }
            static get Default() {
                return Ui;
            }
            static get DefaultType() {
                return Ki;
            }
            static get NAME() {
                return Bi;
            }
            show(t) {
                if (!this._config.isVisible) return void m(t);
                this._append();
                const e = this._getElement();
                this._config.isAnimated && u(e),
                    e.classList.add($i),
                    this._emulateAnimation(() => {
                        m(t);
                    });
            }
            hide(t) {
                this._config.isVisible
                    ? (this._getElement().classList.remove($i),
                      this._emulateAnimation(() => {
                          this.dispose(), m(t);
                      }))
                    : m(t);
            }
            dispose() {
                this._isAppended && (H.off(this._element, Xi), this._element.remove(), (this._isAppended = !1));
            }
            _getElement() {
                if (!this._element) {
                    const t = document.createElement("div");
                    (t.className = this._config.className), this._config.isAnimated && t.classList.add("fade"), (this._element = t);
                }
                return this._element;
            }
            _configAfterMerge(t) {
                return (t.rootElement = r(t.rootElement)), t;
            }
            _append() {
                if (this._isAppended) return;
                const t = this._getElement();
                this._config.rootElement.append(t),
                    H.on(t, Xi, () => {
                        m(this._config.clickCallback);
                    }),
                    (this._isAppended = !0);
            }
            _emulateAnimation(t) {
                v(t, this._getElement(), this._config.isAnimated);
            }
        }
        const Gi = ".bs.focustrap",
            Qi = `focusin${Gi}`,
            Ji = `keydown.tab${Gi}`,
            Zi = "backward",
            tn = { autofocus: !0, trapElement: null },
            en = { autofocus: "boolean", trapElement: "element" };
        class nn extends z {
            constructor(t) {
                super(), (this._config = this._getConfig(t)), (this._isActive = !1), (this._lastTabNavDirection = null);
            }
            static get Default() {
                return tn;
            }
            static get DefaultType() {
                return en;
            }
            static get NAME() {
                return "focustrap";
            }
            activate() {
                this._isActive ||
                    (this._config.autofocus && this._config.trapElement.focus(), H.off(document, Gi), H.on(document, Qi, (t) => this._handleFocusin(t)), H.on(document, Ji, (t) => this._handleKeydown(t)), (this._isActive = !0));
            }
            deactivate() {
                this._isActive && ((this._isActive = !1), H.off(document, Gi));
            }
            _handleFocusin(t) {
                const { trapElement: e } = this._config;
                if (t.target === document || t.target === e || e.contains(t.target)) return;
                const i = q.focusableChildren(e);
                0 === i.length ? e.focus() : this._lastTabNavDirection === Zi ? i[i.length - 1].focus() : i[0].focus();
            }
            _handleKeydown(t) {
                "Tab" === t.key && (this._lastTabNavDirection = t.shiftKey ? Zi : "forward");
            }
        }
        const sn = ".fixed-top, .fixed-bottom, .is-fixed, .sticky-top",
            on = ".sticky-top",
            rn = "padding-right",
            an = "margin-right";
        class ln {
            constructor() {
                this._element = document.body;
            }
            getWidth() {
                const t = document.documentElement.clientWidth;
                return Math.abs(window.innerWidth - t);
            }
            hide() {
                const t = this.getWidth();
                this._disableOverFlow(), this._setElementAttributes(this._element, rn, (e) => e + t), this._setElementAttributes(sn, rn, (e) => e + t), this._setElementAttributes(on, an, (e) => e - t);
            }
            reset() {
                this._resetElementAttributes(this._element, "overflow"), this._resetElementAttributes(this._element, rn), this._resetElementAttributes(sn, rn), this._resetElementAttributes(on, an);
            }
            isOverflowing() {
                return this.getWidth() > 0;
            }
            _disableOverFlow() {
                this._saveInitialAttribute(this._element, "overflow"), (this._element.style.overflow = "hidden");
            }
            _setElementAttributes(t, e, i) {
                const n = this.getWidth();
                this._applyManipulationCallback(t, (t) => {
                    if (t !== this._element && window.innerWidth > t.clientWidth + n) return;
                    this._saveInitialAttribute(t, e);
                    const s = window.getComputedStyle(t).getPropertyValue(e);
                    t.style.setProperty(e, `${i(Number.parseFloat(s))}px`);
                });
            }
            _saveInitialAttribute(t, e) {
                const i = t.style.getPropertyValue(e);
                i && R.setDataAttribute(t, e, i);
            }
            _resetElementAttributes(t, e) {
                this._applyManipulationCallback(t, (t) => {
                    const i = R.getDataAttribute(t, e);
                    null !== i ? (R.removeDataAttribute(t, e), t.style.setProperty(e, i)) : t.style.removeProperty(e);
                });
            }
            _applyManipulationCallback(t, e) {
                if (o(t)) e(t);
                else for (const i of q.find(t, this._element)) e(i);
            }
        }
        const hn = ".bs.modal",
            cn = `hide${hn}`,
            un = `hidePrevented${hn}`,
            dn = `hidden${hn}`,
            pn = `show${hn}`,
            fn = `shown${hn}`,
            gn = `resize${hn}`,
            mn = `click.dismiss${hn}`,
            vn = `mousedown.dismiss${hn}`,
            _n = `keydown.dismiss${hn}`,
            bn = `click${hn}.data-api`,
            yn = "modal-open",
            wn = "show",
            xn = "modal-static",
            kn = { backdrop: !0, focus: !0, keyboard: !0 },
            Cn = { backdrop: "(boolean|string)", focus: "boolean", keyboard: "boolean" };
        class Tn extends j {
            constructor(t, e) {
                super(t, e),
                    (this._dialog = q.findOne(".modal-dialog", this._element)),
                    (this._backdrop = this._initializeBackDrop()),
                    (this._focustrap = this._initializeFocusTrap()),
                    (this._isShown = !1),
                    (this._isTransitioning = !1),
                    (this._scrollBar = new ln()),
                    this._addEventListeners();
            }
            static get Default() {
                return kn;
            }
            static get DefaultType() {
                return Cn;
            }
            static get NAME() {
                return "modal";
            }
            toggle(t) {
                return this._isShown ? this.hide() : this.show(t);
            }
            show(t) {
                this._isShown ||
                    this._isTransitioning ||
                    H.trigger(this._element, pn, { relatedTarget: t }).defaultPrevented ||
                    ((this._isShown = !0), (this._isTransitioning = !0), this._scrollBar.hide(), document.body.classList.add(yn), this._adjustDialog(), this._backdrop.show(() => this._showElement(t)));
            }
            hide() {
                this._isShown &&
                    !this._isTransitioning &&
                    (H.trigger(this._element, cn).defaultPrevented ||
                        ((this._isShown = !1), (this._isTransitioning = !0), this._focustrap.deactivate(), this._element.classList.remove(wn), this._queueCallback(() => this._hideModal(), this._element, this._isAnimated())));
            }
            dispose() {
                H.off(window, hn), H.off(this._dialog, hn), this._backdrop.dispose(), this._focustrap.deactivate(), super.dispose();
            }
            handleUpdate() {
                this._adjustDialog();
            }
            _initializeBackDrop() {
                return new Vi({ isVisible: Boolean(this._config.backdrop), isAnimated: this._isAnimated() });
            }
            _initializeFocusTrap() {
                return new nn({ trapElement: this._element });
            }
            _showElement(t) {
                document.body.contains(this._element) || document.body.append(this._element),
                    (this._element.style.display = "block"),
                    this._element.removeAttribute("aria-hidden"),
                    this._element.setAttribute("aria-modal", !0),
                    this._element.setAttribute("role", "dialog"),
                    (this._element.scrollTop = 0);
                const e = q.findOne(".modal-body", this._dialog);
                e && (e.scrollTop = 0),
                    u(this._element),
                    this._element.classList.add(wn),
                    this._queueCallback(
                        () => {
                            this._config.focus && this._focustrap.activate(), (this._isTransitioning = !1), H.trigger(this._element, fn, { relatedTarget: t });
                        },
                        this._dialog,
                        this._isAnimated()
                    );
            }
            _addEventListeners() {
                H.on(this._element, _n, (t) => {
                    "Escape" === t.key && (this._config.keyboard ? this.hide() : this._triggerBackdropTransition());
                }),
                    H.on(window, gn, () => {
                        this._isShown && !this._isTransitioning && this._adjustDialog();
                    }),
                    H.on(this._element, vn, (t) => {
                        H.one(this._element, mn, (e) => {
                            this._element === t.target && this._element === e.target && ("static" !== this._config.backdrop ? this._config.backdrop && this.hide() : this._triggerBackdropTransition());
                        });
                    });
            }
            _hideModal() {
                (this._element.style.display = "none"),
                    this._element.setAttribute("aria-hidden", !0),
                    this._element.removeAttribute("aria-modal"),
                    this._element.removeAttribute("role"),
                    (this._isTransitioning = !1),
                    this._backdrop.hide(() => {
                        document.body.classList.remove(yn), this._resetAdjustments(), this._scrollBar.reset(), H.trigger(this._element, dn);
                    });
            }
            _isAnimated() {
                return this._element.classList.contains("fade");
            }
            _triggerBackdropTransition() {
                if (H.trigger(this._element, un).defaultPrevented) return;
                const t = this._element.scrollHeight > document.documentElement.clientHeight,
                    e = this._element.style.overflowY;
                "hidden" === e ||
                    this._element.classList.contains(xn) ||
                    (t || (this._element.style.overflowY = "hidden"),
                    this._element.classList.add(xn),
                    this._queueCallback(() => {
                        this._element.classList.remove(xn),
                            this._queueCallback(() => {
                                this._element.style.overflowY = e;
                            }, this._dialog);
                    }, this._dialog),
                    this._element.focus());
            }
            _adjustDialog() {
                const t = this._element.scrollHeight > document.documentElement.clientHeight,
                    e = this._scrollBar.getWidth(),
                    i = e > 0;
                if (i && !t) {
                    const t = f() ? "paddingLeft" : "paddingRight";
                    this._element.style[t] = `${e}px`;
                }
                if (!i && t) {
                    const t = f() ? "paddingRight" : "paddingLeft";
                    this._element.style[t] = `${e}px`;
                }
            }
            _resetAdjustments() {
                (this._element.style.paddingLeft = ""), (this._element.style.paddingRight = "");
            }
            static jQueryInterface(t, e) {
                return this.each(function () {
                    const i = Tn.getOrCreateInstance(this, t);
                    if ("string" == typeof t) {
                        if (void 0 === i[t]) throw new TypeError(`No method named "${t}"`);
                        i[t](e);
                    }
                });
            }
        }
        H.on(document, bn, '[data-bs-toggle="modal"]', function (t) {
            const e = q.getElementFromSelector(this);
            ["A", "AREA"].includes(this.tagName) && t.preventDefault(),
                H.one(e, pn, (t) => {
                    t.defaultPrevented ||
                        H.one(e, dn, () => {
                            a(this) && this.focus();
                        });
                });
            const i = q.findOne(".modal.show");
            i && Tn.getInstance(i).hide(), Tn.getOrCreateInstance(e).toggle(this);
        }),
            Y(Tn),
            g(Tn);
        const Dn = ".bs.offcanvas",
            Sn = ".data-api",
            An = `load${Dn}${Sn}`,
            En = "show",
            In = "showing",
            Pn = "hiding",
            Mn = ".offcanvas.show",
            On = `show${Dn}`,
            Hn = `shown${Dn}`,
            Ln = `hide${Dn}`,
            Nn = `hidePrevented${Dn}`,
            Wn = `hidden${Dn}`,
            Rn = `resize${Dn}`,
            zn = `click${Dn}${Sn}`,
            jn = `keydown.dismiss${Dn}`,
            Fn = { backdrop: !0, keyboard: !0, scroll: !1 },
            qn = { backdrop: "(boolean|string)", keyboard: "boolean", scroll: "boolean" };
        class Yn extends j {
            constructor(t, e) {
                super(t, e), (this._isShown = !1), (this._backdrop = this._initializeBackDrop()), (this._focustrap = this._initializeFocusTrap()), this._addEventListeners();
            }
            static get Default() {
                return Fn;
            }
            static get DefaultType() {
                return qn;
            }
            static get NAME() {
                return "offcanvas";
            }
            toggle(t) {
                return this._isShown ? this.hide() : this.show(t);
            }
            show(t) {
                this._isShown ||
                    H.trigger(this._element, On, { relatedTarget: t }).defaultPrevented ||
                    ((this._isShown = !0),
                    this._backdrop.show(),
                    this._config.scroll || new ln().hide(),
                    this._element.setAttribute("aria-modal", !0),
                    this._element.setAttribute("role", "dialog"),
                    this._element.classList.add(In),
                    this._queueCallback(
                        () => {
                            (this._config.scroll && !this._config.backdrop) || this._focustrap.activate(), this._element.classList.add(En), this._element.classList.remove(In), H.trigger(this._element, Hn, { relatedTarget: t });
                        },
                        this._element,
                        !0
                    ));
            }
            hide() {
                this._isShown &&
                    (H.trigger(this._element, Ln).defaultPrevented ||
                        (this._focustrap.deactivate(),
                        this._element.blur(),
                        (this._isShown = !1),
                        this._element.classList.add(Pn),
                        this._backdrop.hide(),
                        this._queueCallback(
                            () => {
                                this._element.classList.remove(En, Pn), this._element.removeAttribute("aria-modal"), this._element.removeAttribute("role"), this._config.scroll || new ln().reset(), H.trigger(this._element, Wn);
                            },
                            this._element,
                            !0
                        )));
            }
            dispose() {
                this._backdrop.dispose(), this._focustrap.deactivate(), super.dispose();
            }
            _initializeBackDrop() {
                const t = Boolean(this._config.backdrop);
                return new Vi({
                    className: "offcanvas-backdrop",
                    isVisible: t,
                    isAnimated: !0,
                    rootElement: this._element.parentNode,
                    clickCallback: t
                        ? () => {
                              "static" !== this._config.backdrop ? this.hide() : H.trigger(this._element, Nn);
                          }
                        : null,
                });
            }
            _initializeFocusTrap() {
                return new nn({ trapElement: this._element });
            }
            _addEventListeners() {
                H.on(this._element, jn, (t) => {
                    "Escape" === t.key && (this._config.keyboard ? this.hide() : H.trigger(this._element, Nn));
                });
            }
            static jQueryInterface(t) {
                return this.each(function () {
                    const e = Yn.getOrCreateInstance(this, t);
                    if ("string" == typeof t) {
                        if (void 0 === e[t] || t.startsWith("_") || "constructor" === t) throw new TypeError(`No method named "${t}"`);
                        e[t](this);
                    }
                });
            }
        }
        H.on(document, zn, '[data-bs-toggle="offcanvas"]', function (t) {
            const e = q.getElementFromSelector(this);
            if ((["A", "AREA"].includes(this.tagName) && t.preventDefault(), l(this))) return;
            H.one(e, Wn, () => {
                a(this) && this.focus();
            });
            const i = q.findOne(Mn);
            i && i !== e && Yn.getInstance(i).hide(), Yn.getOrCreateInstance(e).toggle(this);
        }),
            H.on(window, An, () => {
                for (const t of q.find(Mn)) Yn.getOrCreateInstance(t).show();
            }),
            H.on(window, Rn, () => {
                for (const t of q.find("[aria-modal][class*=show][class*=offcanvas-]")) "fixed" !== getComputedStyle(t).position && Yn.getOrCreateInstance(t).hide();
            }),
            Y(Yn),
            g(Yn);
        const Bn = {
                "*": ["class", "dir", "id", "lang", "role", /^aria-[\w-]*$/i],
                a: ["target", "href", "title", "rel"],
                area: [],
                b: [],
                br: [],
                col: [],
                code: [],
                div: [],
                em: [],
                hr: [],
                h1: [],
                h2: [],
                h3: [],
                h4: [],
                h5: [],
                h6: [],
                i: [],
                img: ["src", "srcset", "alt", "title", "width", "height"],
                li: [],
                ol: [],
                p: [],
                pre: [],
                s: [],
                small: [],
                span: [],
                sub: [],
                sup: [],
                strong: [],
                u: [],
                ul: [],
            },
            $n = new Set(["background", "cite", "href", "itemtype", "longdesc", "poster", "src", "xlink:href"]),
            Xn = /^(?!javascript:)(?:[a-z0-9+.-]+:|[^&:/?#]*(?:[/?#]|$))/i,
            Un = (t, e) => {
                const i = t.nodeName.toLowerCase();
                return e.includes(i) ? !$n.has(i) || Boolean(Xn.test(t.nodeValue)) : e.filter((t) => t instanceof RegExp).some((t) => t.test(i));
            },
            Kn = { allowList: Bn, content: {}, extraClass: "", html: !1, sanitize: !0, sanitizeFn: null, template: "<div></div>" },
            Vn = { allowList: "object", content: "object", extraClass: "(string|function)", html: "boolean", sanitize: "boolean", sanitizeFn: "(null|function)", template: "string" },
            Gn = { entry: "(string|element|function|null)", selector: "(string|element)" };
        class Qn extends z {
            constructor(t) {
                super(), (this._config = this._getConfig(t));
            }
            static get Default() {
                return Kn;
            }
            static get DefaultType() {
                return Vn;
            }
            static get NAME() {
                return "TemplateFactory";
            }
            getContent() {
                return Object.values(this._config.content)
                    .map((t) => this._resolvePossibleFunction(t))
                    .filter(Boolean);
            }
            hasContent() {
                return this.getContent().length > 0;
            }
            changeContent(t) {
                return this._checkContent(t), (this._config.content = { ...this._config.content, ...t }), this;
            }
            toHtml() {
                const t = document.createElement("div");
                t.innerHTML = this._maybeSanitize(this._config.template);
                for (const [e, i] of Object.entries(this._config.content)) this._setContent(t, i, e);
                const e = t.children[0],
                    i = this._resolvePossibleFunction(this._config.extraClass);
                return i && e.classList.add(...i.split(" ")), e;
            }
            _typeCheckConfig(t) {
                super._typeCheckConfig(t), this._checkContent(t.content);
            }
            _checkContent(t) {
                for (const [e, i] of Object.entries(t)) super._typeCheckConfig({ selector: e, entry: i }, Gn);
            }
            _setContent(t, e, i) {
                const n = q.findOne(i, t);
                n && ((e = this._resolvePossibleFunction(e)) ? (o(e) ? this._putElementInTemplate(r(e), n) : this._config.html ? (n.innerHTML = this._maybeSanitize(e)) : (n.textContent = e)) : n.remove());
            }
            _maybeSanitize(t) {
                return this._config.sanitize
                    ? (function (t, e, i) {
                          if (!t.length) return t;
                          if (i && "function" == typeof i) return i(t);
                          const n = new window.DOMParser().parseFromString(t, "text/html"),
                              s = [].concat(...n.body.querySelectorAll("*"));
                          for (const t of s) {
                              const i = t.nodeName.toLowerCase();
                              if (!Object.keys(e).includes(i)) {
                                  t.remove();
                                  continue;
                              }
                              const n = [].concat(...t.attributes),
                                  s = [].concat(e["*"] || [], e[i] || []);
                              for (const e of n) Un(e, s) || t.removeAttribute(e.nodeName);
                          }
                          return n.body.innerHTML;
                      })(t, this._config.allowList, this._config.sanitizeFn)
                    : t;
            }
            _resolvePossibleFunction(t) {
                return m(t, [this]);
            }
            _putElementInTemplate(t, e) {
                if (this._config.html) return (e.innerHTML = ""), void e.append(t);
                e.textContent = t.textContent;
            }
        }
        const Jn = new Set(["sanitize", "allowList", "sanitizeFn"]),
            Zn = "fade",
            ts = "show",
            es = ".modal",
            is = "hide.bs.modal",
            ns = "hover",
            ss = "focus",
            os = { AUTO: "auto", TOP: "top", RIGHT: f() ? "left" : "right", BOTTOM: "bottom", LEFT: f() ? "right" : "left" },
            rs = {
                allowList: Bn,
                animation: !0,
                boundary: "clippingParents",
                container: !1,
                customClass: "",
                delay: 0,
                fallbackPlacements: ["top", "right", "bottom", "left"],
                html: !1,
                offset: [0, 6],
                placement: "top",
                popperConfig: null,
                sanitize: !0,
                sanitizeFn: null,
                selector: !1,
                template: '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',
                title: "",
                trigger: "hover focus",
            },
            as = {
                allowList: "object",
                animation: "boolean",
                boundary: "(string|element)",
                container: "(string|element|boolean)",
                customClass: "(string|function)",
                delay: "(number|object)",
                fallbackPlacements: "array",
                html: "boolean",
                offset: "(array|string|function)",
                placement: "(string|function)",
                popperConfig: "(null|object|function)",
                sanitize: "boolean",
                sanitizeFn: "(null|function)",
                selector: "(string|boolean)",
                template: "string",
                title: "(string|element|function)",
                trigger: "string",
            };
        class ls extends j {
            constructor(t, e) {
                if (void 0 === _i) throw new TypeError("Bootstrap's tooltips require Popper (https://popper.js.org)");
                super(t, e),
                    (this._isEnabled = !0),
                    (this._timeout = 0),
                    (this._isHovered = null),
                    (this._activeTrigger = {}),
                    (this._popper = null),
                    (this._templateFactory = null),
                    (this._newContent = null),
                    (this.tip = null),
                    this._setListeners(),
                    this._config.selector || this._fixTitle();
            }
            static get Default() {
                return rs;
            }
            static get DefaultType() {
                return as;
            }
            static get NAME() {
                return "tooltip";
            }
            enable() {
                this._isEnabled = !0;
            }
            disable() {
                this._isEnabled = !1;
            }
            toggleEnabled() {
                this._isEnabled = !this._isEnabled;
            }
            toggle() {
                this._isEnabled && ((this._activeTrigger.click = !this._activeTrigger.click), this._isShown() ? this._leave() : this._enter());
            }
            dispose() {
                clearTimeout(this._timeout),
                    H.off(this._element.closest(es), is, this._hideModalHandler),
                    this._element.getAttribute("data-bs-original-title") && this._element.setAttribute("title", this._element.getAttribute("data-bs-original-title")),
                    this._disposePopper(),
                    super.dispose();
            }
            show() {
                if ("none" === this._element.style.display) throw new Error("Please use show on visible elements");
                if (!this._isWithContent() || !this._isEnabled) return;
                const t = H.trigger(this._element, this.constructor.eventName("show")),
                    e = (h(this._element) || this._element.ownerDocument.documentElement).contains(this._element);
                if (t.defaultPrevented || !e) return;
                this._disposePopper();
                const i = this._getTipElement();
                this._element.setAttribute("aria-describedby", i.getAttribute("id"));
                const { container: n } = this._config;
                if (
                    (this._element.ownerDocument.documentElement.contains(this.tip) || (n.append(i), H.trigger(this._element, this.constructor.eventName("inserted"))),
                    (this._popper = this._createPopper(i)),
                    i.classList.add(ts),
                    "ontouchstart" in document.documentElement)
                )
                    for (const t of [].concat(...document.body.children)) H.on(t, "mouseover", c);
                this._queueCallback(
                    () => {
                        H.trigger(this._element, this.constructor.eventName("shown")), !1 === this._isHovered && this._leave(), (this._isHovered = !1);
                    },
                    this.tip,
                    this._isAnimated()
                );
            }
            hide() {
                if (this._isShown() && !H.trigger(this._element, this.constructor.eventName("hide")).defaultPrevented) {
                    if ((this._getTipElement().classList.remove(ts), "ontouchstart" in document.documentElement)) for (const t of [].concat(...document.body.children)) H.off(t, "mouseover", c);
                    (this._activeTrigger.click = !1),
                        (this._activeTrigger[ss] = !1),
                        (this._activeTrigger[ns] = !1),
                        (this._isHovered = null),
                        this._queueCallback(
                            () => {
                                this._isWithActiveTrigger() || (this._isHovered || this._disposePopper(), this._element.removeAttribute("aria-describedby"), H.trigger(this._element, this.constructor.eventName("hidden")));
                            },
                            this.tip,
                            this._isAnimated()
                        );
                }
            }
            update() {
                this._popper && this._popper.update();
            }
            _isWithContent() {
                return Boolean(this._getTitle());
            }
            _getTipElement() {
                return this.tip || (this.tip = this._createTipElement(this._newContent || this._getContentForTemplate())), this.tip;
            }
            _createTipElement(t) {
                const e = this._getTemplateFactory(t).toHtml();
                if (!e) return null;
                e.classList.remove(Zn, ts), e.classList.add(`bs-${this.constructor.NAME}-auto`);
                const i = ((t) => {
                    do {
                        t += Math.floor(1e6 * Math.random());
                    } while (document.getElementById(t));
                    return t;
                })(this.constructor.NAME).toString();
                return e.setAttribute("id", i), this._isAnimated() && e.classList.add(Zn), e;
            }
            setContent(t) {
                (this._newContent = t), this._isShown() && (this._disposePopper(), this.show());
            }
            _getTemplateFactory(t) {
                return (
                    this._templateFactory ? this._templateFactory.changeContent(t) : (this._templateFactory = new Qn({ ...this._config, content: t, extraClass: this._resolvePossibleFunction(this._config.customClass) })),
                    this._templateFactory
                );
            }
            _getContentForTemplate() {
                return { ".tooltip-inner": this._getTitle() };
            }
            _getTitle() {
                return this._resolvePossibleFunction(this._config.title) || this._element.getAttribute("data-bs-original-title");
            }
            _initializeOnDelegatedTarget(t) {
                return this.constructor.getOrCreateInstance(t.delegateTarget, this._getDelegateConfig());
            }
            _isAnimated() {
                return this._config.animation || (this.tip && this.tip.classList.contains(Zn));
            }
            _isShown() {
                return this.tip && this.tip.classList.contains(ts);
            }
            _createPopper(t) {
                const e = m(this._config.placement, [this, t, this._element]),
                    i = os[e.toUpperCase()];
                return vi(this._element, t, this._getPopperConfig(i));
            }
            _getOffset() {
                const { offset: t } = this._config;
                return "string" == typeof t ? t.split(",").map((t) => Number.parseInt(t, 10)) : "function" == typeof t ? (e) => t(e, this._element) : t;
            }
            _resolvePossibleFunction(t) {
                return m(t, [this._element]);
            }
            _getPopperConfig(t) {
                const e = {
                    placement: t,
                    modifiers: [
                        { name: "flip", options: { fallbackPlacements: this._config.fallbackPlacements } },
                        { name: "offset", options: { offset: this._getOffset() } },
                        { name: "preventOverflow", options: { boundary: this._config.boundary } },
                        { name: "arrow", options: { element: `.${this.constructor.NAME}-arrow` } },
                        {
                            name: "preSetPlacement",
                            enabled: !0,
                            phase: "beforeMain",
                            fn: (t) => {
                                this._getTipElement().setAttribute("data-popper-placement", t.state.placement);
                            },
                        },
                    ],
                };
                return { ...e, ...m(this._config.popperConfig, [e]) };
            }
            _setListeners() {
                const t = this._config.trigger.split(" ");
                for (const e of t)
                    if ("click" === e)
                        H.on(this._element, this.constructor.eventName("click"), this._config.selector, (t) => {
                            this._initializeOnDelegatedTarget(t).toggle();
                        });
                    else if ("manual" !== e) {
                        const t = e === ns ? this.constructor.eventName("mouseenter") : this.constructor.eventName("focusin"),
                            i = e === ns ? this.constructor.eventName("mouseleave") : this.constructor.eventName("focusout");
                        H.on(this._element, t, this._config.selector, (t) => {
                            const e = this._initializeOnDelegatedTarget(t);
                            (e._activeTrigger["focusin" === t.type ? ss : ns] = !0), e._enter();
                        }),
                            H.on(this._element, i, this._config.selector, (t) => {
                                const e = this._initializeOnDelegatedTarget(t);
                                (e._activeTrigger["focusout" === t.type ? ss : ns] = e._element.contains(t.relatedTarget)), e._leave();
                            });
                    }
                (this._hideModalHandler = () => {
                    this._element && this.hide();
                }),
                    H.on(this._element.closest(es), is, this._hideModalHandler);
            }
            _fixTitle() {
                const t = this._element.getAttribute("title");
                t &&
                    (this._element.getAttribute("aria-label") || this._element.textContent.trim() || this._element.setAttribute("aria-label", t),
                    this._element.setAttribute("data-bs-original-title", t),
                    this._element.removeAttribute("title"));
            }
            _enter() {
                this._isShown() || this._isHovered
                    ? (this._isHovered = !0)
                    : ((this._isHovered = !0),
                      this._setTimeout(() => {
                          this._isHovered && this.show();
                      }, this._config.delay.show));
            }
            _leave() {
                this._isWithActiveTrigger() ||
                    ((this._isHovered = !1),
                    this._setTimeout(() => {
                        this._isHovered || this.hide();
                    }, this._config.delay.hide));
            }
            _setTimeout(t, e) {
                clearTimeout(this._timeout), (this._timeout = setTimeout(t, e));
            }
            _isWithActiveTrigger() {
                return Object.values(this._activeTrigger).includes(!0);
            }
            _getConfig(t) {
                const e = R.getDataAttributes(this._element);
                for (const t of Object.keys(e)) Jn.has(t) && delete e[t];
                return (t = { ...e, ...("object" == typeof t && t ? t : {}) }), (t = this._mergeConfigObj(t)), (t = this._configAfterMerge(t)), this._typeCheckConfig(t), t;
            }
            _configAfterMerge(t) {
                return (
                    (t.container = !1 === t.container ? document.body : r(t.container)),
                    "number" == typeof t.delay && (t.delay = { show: t.delay, hide: t.delay }),
                    "number" == typeof t.title && (t.title = t.title.toString()),
                    "number" == typeof t.content && (t.content = t.content.toString()),
                    t
                );
            }
            _getDelegateConfig() {
                const t = {};
                for (const [e, i] of Object.entries(this._config)) this.constructor.Default[e] !== i && (t[e] = i);
                return (t.selector = !1), (t.trigger = "manual"), t;
            }
            _disposePopper() {
                this._popper && (this._popper.destroy(), (this._popper = null)), this.tip && (this.tip.remove(), (this.tip = null));
            }
            static jQueryInterface(t) {
                return this.each(function () {
                    const e = ls.getOrCreateInstance(this, t);
                    if ("string" == typeof t) {
                        if (void 0 === e[t]) throw new TypeError(`No method named "${t}"`);
                        e[t]();
                    }
                });
            }
        }
        g(ls);
        const hs = {
                ...ls.Default,
                content: "",
                offset: [0, 8],
                placement: "right",
                template: '<div class="popover" role="tooltip"><div class="popover-arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>',
                trigger: "click",
            },
            cs = { ...ls.DefaultType, content: "(null|string|element|function)" };
        class us extends ls {
            static get Default() {
                return hs;
            }
            static get DefaultType() {
                return cs;
            }
            static get NAME() {
                return "popover";
            }
            _isWithContent() {
                return this._getTitle() || this._getContent();
            }
            _getContentForTemplate() {
                return { ".popover-header": this._getTitle(), ".popover-body": this._getContent() };
            }
            _getContent() {
                return this._resolvePossibleFunction(this._config.content);
            }
            static jQueryInterface(t) {
                return this.each(function () {
                    const e = us.getOrCreateInstance(this, t);
                    if ("string" == typeof t) {
                        if (void 0 === e[t]) throw new TypeError(`No method named "${t}"`);
                        e[t]();
                    }
                });
            }
        }
        g(us);
        const ds = ".bs.scrollspy",
            ps = `activate${ds}`,
            fs = `click${ds}`,
            gs = `load${ds}.data-api`,
            ms = "active",
            vs = "[href]",
            _s = ".nav-link",
            bs = `${_s}, .nav-item > ${_s}, .list-group-item`,
            ys = { offset: null, rootMargin: "0px 0px -25%", smoothScroll: !1, target: null, threshold: [0.1, 0.5, 1] },
            ws = { offset: "(number|null)", rootMargin: "string", smoothScroll: "boolean", target: "element", threshold: "array" };
        class xs extends j {
            constructor(t, e) {
                super(t, e),
                    (this._targetLinks = new Map()),
                    (this._observableSections = new Map()),
                    (this._rootElement = "visible" === getComputedStyle(this._element).overflowY ? null : this._element),
                    (this._activeTarget = null),
                    (this._observer = null),
                    (this._previousScrollData = { visibleEntryTop: 0, parentScrollTop: 0 }),
                    this.refresh();
            }
            static get Default() {
                return ys;
            }
            static get DefaultType() {
                return ws;
            }
            static get NAME() {
                return "scrollspy";
            }
            refresh() {
                this._initializeTargetsAndObservables(), this._maybeEnableSmoothScroll(), this._observer ? this._observer.disconnect() : (this._observer = this._getNewObserver());
                for (const t of this._observableSections.values()) this._observer.observe(t);
            }
            dispose() {
                this._observer.disconnect(), super.dispose();
            }
            _configAfterMerge(t) {
                return (
                    (t.target = r(t.target) || document.body), (t.rootMargin = t.offset ? `${t.offset}px 0px -30%` : t.rootMargin), "string" == typeof t.threshold && (t.threshold = t.threshold.split(",").map((t) => Number.parseFloat(t))), t
                );
            }
            _maybeEnableSmoothScroll() {
                this._config.smoothScroll &&
                    (H.off(this._config.target, fs),
                    H.on(this._config.target, fs, vs, (t) => {
                        const e = this._observableSections.get(t.target.hash);
                        if (e) {
                            t.preventDefault();
                            const i = this._rootElement || window,
                                n = e.offsetTop - this._element.offsetTop;
                            if (i.scrollTo) return void i.scrollTo({ top: n, behavior: "smooth" });
                            i.scrollTop = n;
                        }
                    }));
            }
            _getNewObserver() {
                const t = { root: this._rootElement, threshold: this._config.threshold, rootMargin: this._config.rootMargin };
                return new IntersectionObserver((t) => this._observerCallback(t), t);
            }
            _observerCallback(t) {
                const e = (t) => this._targetLinks.get(`#${t.target.id}`),
                    i = (t) => {
                        (this._previousScrollData.visibleEntryTop = t.target.offsetTop), this._process(e(t));
                    },
                    n = (this._rootElement || document.documentElement).scrollTop,
                    s = n >= this._previousScrollData.parentScrollTop;
                this._previousScrollData.parentScrollTop = n;
                for (const o of t) {
                    if (!o.isIntersecting) {
                        (this._activeTarget = null), this._clearActiveClass(e(o));
                        continue;
                    }
                    const t = o.target.offsetTop >= this._previousScrollData.visibleEntryTop;
                    if (s && t) {
                        if ((i(o), !n)) return;
                    } else s || t || i(o);
                }
            }
            _initializeTargetsAndObservables() {
                (this._targetLinks = new Map()), (this._observableSections = new Map());
                const t = q.find(vs, this._config.target);
                for (const e of t) {
                    if (!e.hash || l(e)) continue;
                    const t = q.findOne(decodeURI(e.hash), this._element);
                    a(t) && (this._targetLinks.set(decodeURI(e.hash), e), this._observableSections.set(e.hash, t));
                }
            }
            _process(t) {
                this._activeTarget !== t && (this._clearActiveClass(this._config.target), (this._activeTarget = t), t.classList.add(ms), this._activateParents(t), H.trigger(this._element, ps, { relatedTarget: t }));
            }
            _activateParents(t) {
                if (t.classList.contains("dropdown-item")) q.findOne(".dropdown-toggle", t.closest(".dropdown")).classList.add(ms);
                else for (const e of q.parents(t, ".nav, .list-group")) for (const t of q.prev(e, bs)) t.classList.add(ms);
            }
            _clearActiveClass(t) {
                t.classList.remove(ms);
                const e = q.find(`${vs}.${ms}`, t);
                for (const t of e) t.classList.remove(ms);
            }
            static jQueryInterface(t) {
                return this.each(function () {
                    const e = xs.getOrCreateInstance(this, t);
                    if ("string" == typeof t) {
                        if (void 0 === e[t] || t.startsWith("_") || "constructor" === t) throw new TypeError(`No method named "${t}"`);
                        e[t]();
                    }
                });
            }
        }
        H.on(window, gs, () => {
            for (const t of q.find('[data-bs-spy="scroll"]')) xs.getOrCreateInstance(t);
        }),
            g(xs);
        const ks = ".bs.tab",
            Cs = `hide${ks}`,
            Ts = `hidden${ks}`,
            Ds = `show${ks}`,
            Ss = `shown${ks}`,
            As = `click${ks}`,
            Es = `keydown${ks}`,
            Is = `load${ks}`,
            Ps = "ArrowLeft",
            Ms = "ArrowRight",
            Os = "ArrowUp",
            Hs = "ArrowDown",
            Ls = "Home",
            Ns = "End",
            Ws = "active",
            Rs = "fade",
            zs = "show",
            js = ".dropdown-toggle",
            Fs = `:not(${js})`,
            qs = '[data-bs-toggle="tab"], [data-bs-toggle="pill"], [data-bs-toggle="list"]',
            Ys = `.nav-link${Fs}, .list-group-item${Fs}, [role="tab"]${Fs}, ${qs}`,
            Bs = `.${Ws}[data-bs-toggle="tab"], .${Ws}[data-bs-toggle="pill"], .${Ws}[data-bs-toggle="list"]`;
        class $s extends j {
            constructor(t) {
                super(t), (this._parent = this._element.closest('.list-group, .nav, [role="tablist"]')), this._parent && (this._setInitialAttributes(this._parent, this._getChildren()), H.on(this._element, Es, (t) => this._keydown(t)));
            }
            static get NAME() {
                return "tab";
            }
            show() {
                const t = this._element;
                if (this._elemIsActive(t)) return;
                const e = this._getActiveElem(),
                    i = e ? H.trigger(e, Cs, { relatedTarget: t }) : null;
                H.trigger(t, Ds, { relatedTarget: e }).defaultPrevented || (i && i.defaultPrevented) || (this._deactivate(e, t), this._activate(t, e));
            }
            _activate(t, e) {
                t &&
                    (t.classList.add(Ws),
                    this._activate(q.getElementFromSelector(t)),
                    this._queueCallback(
                        () => {
                            "tab" === t.getAttribute("role") ? (t.removeAttribute("tabindex"), t.setAttribute("aria-selected", !0), this._toggleDropDown(t, !0), H.trigger(t, Ss, { relatedTarget: e })) : t.classList.add(zs);
                        },
                        t,
                        t.classList.contains(Rs)
                    ));
            }
            _deactivate(t, e) {
                t &&
                    (t.classList.remove(Ws),
                    t.blur(),
                    this._deactivate(q.getElementFromSelector(t)),
                    this._queueCallback(
                        () => {
                            "tab" === t.getAttribute("role") ? (t.setAttribute("aria-selected", !1), t.setAttribute("tabindex", "-1"), this._toggleDropDown(t, !1), H.trigger(t, Ts, { relatedTarget: e })) : t.classList.remove(zs);
                        },
                        t,
                        t.classList.contains(Rs)
                    ));
            }
            _keydown(t) {
                if (![Ps, Ms, Os, Hs, Ls, Ns].includes(t.key)) return;
                t.stopPropagation(), t.preventDefault();
                const e = this._getChildren().filter((t) => !l(t));
                let i;
                if ([Ls, Ns].includes(t.key)) i = e[t.key === Ls ? 0 : e.length - 1];
                else {
                    const n = [Ms, Hs].includes(t.key);
                    i = _(e, t.target, n, !0);
                }
                i && (i.focus({ preventScroll: !0 }), $s.getOrCreateInstance(i).show());
            }
            _getChildren() {
                return q.find(Ys, this._parent);
            }
            _getActiveElem() {
                return this._getChildren().find((t) => this._elemIsActive(t)) || null;
            }
            _setInitialAttributes(t, e) {
                this._setAttributeIfNotExists(t, "role", "tablist");
                for (const t of e) this._setInitialAttributesOnChild(t);
            }
            _setInitialAttributesOnChild(t) {
                t = this._getInnerElement(t);
                const e = this._elemIsActive(t),
                    i = this._getOuterElement(t);
                t.setAttribute("aria-selected", e),
                    i !== t && this._setAttributeIfNotExists(i, "role", "presentation"),
                    e || t.setAttribute("tabindex", "-1"),
                    this._setAttributeIfNotExists(t, "role", "tab"),
                    this._setInitialAttributesOnTargetPanel(t);
            }
            _setInitialAttributesOnTargetPanel(t) {
                const e = q.getElementFromSelector(t);
                e && (this._setAttributeIfNotExists(e, "role", "tabpanel"), t.id && this._setAttributeIfNotExists(e, "aria-labelledby", `${t.id}`));
            }
            _toggleDropDown(t, e) {
                const i = this._getOuterElement(t);
                if (!i.classList.contains("dropdown")) return;
                const n = (t, n) => {
                    const s = q.findOne(t, i);
                    s && s.classList.toggle(n, e);
                };
                n(js, Ws), n(".dropdown-menu", zs), i.setAttribute("aria-expanded", e);
            }
            _setAttributeIfNotExists(t, e, i) {
                t.hasAttribute(e) || t.setAttribute(e, i);
            }
            _elemIsActive(t) {
                return t.classList.contains(Ws);
            }
            _getInnerElement(t) {
                return t.matches(Ys) ? t : q.findOne(Ys, t);
            }
            _getOuterElement(t) {
                return t.closest(".nav-item, .list-group-item") || t;
            }
            static jQueryInterface(t) {
                return this.each(function () {
                    const e = $s.getOrCreateInstance(this);
                    if ("string" == typeof t) {
                        if (void 0 === e[t] || t.startsWith("_") || "constructor" === t) throw new TypeError(`No method named "${t}"`);
                        e[t]();
                    }
                });
            }
        }
        H.on(document, As, qs, function (t) {
            ["A", "AREA"].includes(this.tagName) && t.preventDefault(), l(this) || $s.getOrCreateInstance(this).show();
        }),
            H.on(window, Is, () => {
                for (const t of q.find(Bs)) $s.getOrCreateInstance(t);
            }),
            g($s);
        const Xs = ".bs.toast",
            Us = `mouseover${Xs}`,
            Ks = `mouseout${Xs}`,
            Vs = `focusin${Xs}`,
            Gs = `focusout${Xs}`,
            Qs = `hide${Xs}`,
            Js = `hidden${Xs}`,
            Zs = `show${Xs}`,
            to = `shown${Xs}`,
            eo = "hide",
            io = "show",
            no = "showing",
            so = { animation: "boolean", autohide: "boolean", delay: "number" },
            oo = { animation: !0, autohide: !0, delay: 5e3 };
        class ro extends j {
            constructor(t, e) {
                super(t, e), (this._timeout = null), (this._hasMouseInteraction = !1), (this._hasKeyboardInteraction = !1), this._setListeners();
            }
            static get Default() {
                return oo;
            }
            static get DefaultType() {
                return so;
            }
            static get NAME() {
                return "toast";
            }
            show() {
                H.trigger(this._element, Zs).defaultPrevented ||
                    (this._clearTimeout(),
                    this._config.animation && this._element.classList.add("fade"),
                    this._element.classList.remove(eo),
                    u(this._element),
                    this._element.classList.add(io, no),
                    this._queueCallback(
                        () => {
                            this._element.classList.remove(no), H.trigger(this._element, to), this._maybeScheduleHide();
                        },
                        this._element,
                        this._config.animation
                    ));
            }
            hide() {
                this.isShown() &&
                    (H.trigger(this._element, Qs).defaultPrevented ||
                        (this._element.classList.add(no),
                        this._queueCallback(
                            () => {
                                this._element.classList.add(eo), this._element.classList.remove(no, io), H.trigger(this._element, Js);
                            },
                            this._element,
                            this._config.animation
                        )));
            }
            dispose() {
                this._clearTimeout(), this.isShown() && this._element.classList.remove(io), super.dispose();
            }
            isShown() {
                return this._element.classList.contains(io);
            }
            _maybeScheduleHide() {
                this._config.autohide &&
                    (this._hasMouseInteraction ||
                        this._hasKeyboardInteraction ||
                        (this._timeout = setTimeout(() => {
                            this.hide();
                        }, this._config.delay)));
            }
            _onInteraction(t, e) {
                switch (t.type) {
                    case "mouseover":
                    case "mouseout":
                        this._hasMouseInteraction = e;
                        break;
                    case "focusin":
                    case "focusout":
                        this._hasKeyboardInteraction = e;
                }
                if (e) return void this._clearTimeout();
                const i = t.relatedTarget;
                this._element === i || this._element.contains(i) || this._maybeScheduleHide();
            }
            _setListeners() {
                H.on(this._element, Us, (t) => this._onInteraction(t, !0)),
                    H.on(this._element, Ks, (t) => this._onInteraction(t, !1)),
                    H.on(this._element, Vs, (t) => this._onInteraction(t, !0)),
                    H.on(this._element, Gs, (t) => this._onInteraction(t, !1));
            }
            _clearTimeout() {
                clearTimeout(this._timeout), (this._timeout = null);
            }
            static jQueryInterface(t) {
                return this.each(function () {
                    const e = ro.getOrCreateInstance(this, t);
                    if ("string" == typeof t) {
                        if (void 0 === e[t]) throw new TypeError(`No method named "${t}"`);
                        e[t](this);
                    }
                });
            }
        }
        return Y(ro), g(ro), { Alert: U, Button: V, Carousel: St, Collapse: Ft, Dropdown: Yi, Modal: Tn, Offcanvas: Yn, Popover: us, ScrollSpy: xs, Tab: $s, Toast: ro, Tooltip: ls };
    }),
    /*!
     * perfect-scrollbar v1.5.3
     * Copyright 2021 Hyunje Jun, MDBootstrap and Contributors
     * Licensed under MIT
     */
    (function (t, e) {
        "object" == typeof exports && "undefined" != typeof module ? (module.exports = e()) : "function" == typeof define && define.amd ? define(e) : ((t = t || self).PerfectScrollbar = e());
    })(this, function () {
        "use strict";
        var t = Math.abs,
            e = Math.floor;
        function i(t) {
            return getComputedStyle(t);
        }
        function n(t, e) {
            for (var i in e) {
                var n = e[i];
                "number" == typeof n && (n += "px"), (t.style[i] = n);
            }
            return t;
        }
        function s(t) {
            var e = document.createElement("div");
            return (e.className = t), e;
        }
        function o(t, e) {
            if (!b) throw new Error("No element matching method supported");
            return b.call(t, e);
        }
        function r(t) {
            t.remove ? t.remove() : t.parentNode && t.parentNode.removeChild(t);
        }
        function a(t, e) {
            return Array.prototype.filter.call(t.children, function (t) {
                return o(t, e);
            });
        }
        function l(t, e) {
            var i = t.element.classList,
                n = y.state.scrolling(e);
            i.contains(n) ? clearTimeout(w[e]) : i.add(n);
        }
        function h(t, e) {
            w[e] = setTimeout(function () {
                return t.isAlive && t.element.classList.remove(y.state.scrolling(e));
            }, t.settings.scrollingThreshold);
        }
        function c(t, e) {
            l(t, e), h(t, e);
        }
        function u(t) {
            if ("function" == typeof window.CustomEvent) return new CustomEvent(t);
            var e = document.createEvent("CustomEvent");
            return e.initCustomEvent(t, !1, !1, void 0), e;
        }
        function d(t, e, i, n, s) {
            var o;
            if ((void 0 === n && (n = !0), void 0 === s && (s = !1), "top" === e)) o = ["contentHeight", "containerHeight", "scrollTop", "y", "up", "down"];
            else {
                if ("left" !== e) throw new Error("A proper axis should be provided");
                o = ["contentWidth", "containerWidth", "scrollLeft", "x", "left", "right"];
            }
            !(function (t, e, i, n, s) {
                var o = i[0],
                    r = i[1],
                    a = i[2],
                    l = i[3],
                    h = i[4],
                    d = i[5];
                void 0 === n && (n = !0), void 0 === s && (s = !1);
                var p = t.element;
                (t.reach[l] = null),
                    1 > p[a] && (t.reach[l] = "start"),
                    p[a] > t[o] - t[r] - 1 && (t.reach[l] = "end"),
                    e && (p.dispatchEvent(u("ps-scroll-" + l)), 0 > e ? p.dispatchEvent(u("ps-scroll-" + h)) : 0 < e && p.dispatchEvent(u("ps-scroll-" + d)), n && c(t, l)),
                    t.reach[l] && (e || s) && p.dispatchEvent(u("ps-" + l + "-reach-" + t.reach[l]));
            })(t, i, o, n, s);
        }
        function p(t) {
            return parseInt(t, 10) || 0;
        }
        function f(t) {
            return o(t, "input,[contenteditable]") || o(t, "select,[contenteditable]") || o(t, "textarea,[contenteditable]") || o(t, "button,[contenteditable]");
        }
        function g(t) {
            var i = Math.round,
                n = t.element,
                s = e(n.scrollTop),
                o = n.getBoundingClientRect();
            (t.containerWidth = i(o.width)),
                (t.containerHeight = i(o.height)),
                (t.contentWidth = n.scrollWidth),
                (t.contentHeight = n.scrollHeight),
                n.contains(t.scrollbarXRail) ||
                    (a(n, y.element.rail("x")).forEach(function (t) {
                        return r(t);
                    }),
                    n.appendChild(t.scrollbarXRail)),
                n.contains(t.scrollbarYRail) ||
                    (a(n, y.element.rail("y")).forEach(function (t) {
                        return r(t);
                    }),
                    n.appendChild(t.scrollbarYRail)),
                !t.settings.suppressScrollX && t.containerWidth + t.settings.scrollXMarginOffset < t.contentWidth
                    ? ((t.scrollbarXActive = !0),
                      (t.railXWidth = t.containerWidth - t.railXMarginWidth),
                      (t.railXRatio = t.containerWidth / t.railXWidth),
                      (t.scrollbarXWidth = m(t, p((t.railXWidth * t.containerWidth) / t.contentWidth))),
                      (t.scrollbarXLeft = p(((t.negativeScrollAdjustment + n.scrollLeft) * (t.railXWidth - t.scrollbarXWidth)) / (t.contentWidth - t.containerWidth))))
                    : (t.scrollbarXActive = !1),
                !t.settings.suppressScrollY && t.containerHeight + t.settings.scrollYMarginOffset < t.contentHeight
                    ? ((t.scrollbarYActive = !0),
                      (t.railYHeight = t.containerHeight - t.railYMarginHeight),
                      (t.railYRatio = t.containerHeight / t.railYHeight),
                      (t.scrollbarYHeight = m(t, p((t.railYHeight * t.containerHeight) / t.contentHeight))),
                      (t.scrollbarYTop = p((s * (t.railYHeight - t.scrollbarYHeight)) / (t.contentHeight - t.containerHeight))))
                    : (t.scrollbarYActive = !1),
                t.scrollbarXLeft >= t.railXWidth - t.scrollbarXWidth && (t.scrollbarXLeft = t.railXWidth - t.scrollbarXWidth),
                t.scrollbarYTop >= t.railYHeight - t.scrollbarYHeight && (t.scrollbarYTop = t.railYHeight - t.scrollbarYHeight),
                v(n, t),
                t.scrollbarXActive ? n.classList.add(y.state.active("x")) : (n.classList.remove(y.state.active("x")), (t.scrollbarXWidth = 0), (t.scrollbarXLeft = 0), (n.scrollLeft = !0 === t.isRtl ? t.contentWidth : 0)),
                t.scrollbarYActive ? n.classList.add(y.state.active("y")) : (n.classList.remove(y.state.active("y")), (t.scrollbarYHeight = 0), (t.scrollbarYTop = 0), (n.scrollTop = 0));
        }
        function m(t, e) {
            var i = Math.min,
                n = Math.max;
            return t.settings.minScrollbarLength && (e = n(e, t.settings.minScrollbarLength)), t.settings.maxScrollbarLength && (e = i(e, t.settings.maxScrollbarLength)), e;
        }
        function v(t, i) {
            var s = { width: i.railXWidth },
                o = e(t.scrollTop);
            (s.left = i.isRtl ? i.negativeScrollAdjustment + t.scrollLeft + i.containerWidth - i.contentWidth : t.scrollLeft),
                i.isScrollbarXUsingBottom ? (s.bottom = i.scrollbarXBottom - o) : (s.top = i.scrollbarXTop + o),
                n(i.scrollbarXRail, s);
            var r = { top: o, height: i.railYHeight };
            i.isScrollbarYUsingRight
                ? i.isRtl
                    ? (r.right = i.contentWidth - (i.negativeScrollAdjustment + t.scrollLeft) - i.scrollbarYRight - i.scrollbarYOuterWidth - 9)
                    : (r.right = i.scrollbarYRight - t.scrollLeft)
                : i.isRtl
                ? (r.left = i.negativeScrollAdjustment + t.scrollLeft + 2 * i.containerWidth - i.contentWidth - i.scrollbarYLeft - i.scrollbarYOuterWidth)
                : (r.left = i.scrollbarYLeft + t.scrollLeft),
                n(i.scrollbarYRail, r),
                n(i.scrollbarX, { left: i.scrollbarXLeft, width: i.scrollbarXWidth - i.railBorderXWidth }),
                n(i.scrollbarY, { top: i.scrollbarYTop, height: i.scrollbarYHeight - i.railBorderYWidth });
        }
        function _(t, e) {
            function i(e) {
                e.touches && e.touches[0] && (e[a] = e.touches[0].pageY), (v[p] = _ + w * (e[a] - b)), l(t, f), g(t), e.stopPropagation(), e.type.startsWith("touch") && 1 < e.changedTouches.length && e.preventDefault();
            }
            function n() {
                h(t, f), t[m].classList.remove(y.state.clicking), t.event.unbind(t.ownerDocument, "mousemove", i);
            }
            function s(e, s) {
                (_ = v[p]),
                    s && e.touches && (e[a] = e.touches[0].pageY),
                    (b = e[a]),
                    (w = (t[r] - t[o]) / (t[c] - t[d])),
                    s ? t.event.bind(t.ownerDocument, "touchmove", i) : (t.event.bind(t.ownerDocument, "mousemove", i), t.event.once(t.ownerDocument, "mouseup", n), e.preventDefault()),
                    t[m].classList.add(y.state.clicking),
                    e.stopPropagation();
            }
            var o = e[0],
                r = e[1],
                a = e[2],
                c = e[3],
                u = e[4],
                d = e[5],
                p = e[6],
                f = e[7],
                m = e[8],
                v = t.element,
                _ = null,
                b = null,
                w = null;
            t.event.bind(t[u], "mousedown", function (t) {
                s(t);
            }),
                t.event.bind(t[u], "touchstart", function (t) {
                    s(t, !0);
                });
        }
        var b = "undefined" != typeof Element && (Element.prototype.matches || Element.prototype.webkitMatchesSelector || Element.prototype.mozMatchesSelector || Element.prototype.msMatchesSelector),
            y = {
                main: "ps",
                rtl: "ps__rtl",
                element: {
                    thumb: function (t) {
                        return "ps__thumb-" + t;
                    },
                    rail: function (t) {
                        return "ps__rail-" + t;
                    },
                    consuming: "ps__child--consume",
                },
                state: {
                    focus: "ps--focus",
                    clicking: "ps--clicking",
                    active: function (t) {
                        return "ps--active-" + t;
                    },
                    scrolling: function (t) {
                        return "ps--scrolling-" + t;
                    },
                },
            },
            w = { x: null, y: null },
            x = function (t) {
                (this.element = t), (this.handlers = {});
            },
            k = { isEmpty: { configurable: !0 } };
        (x.prototype.bind = function (t, e) {
            void 0 === this.handlers[t] && (this.handlers[t] = []), this.handlers[t].push(e), this.element.addEventListener(t, e, !1);
        }),
            (x.prototype.unbind = function (t, e) {
                var i = this;
                this.handlers[t] = this.handlers[t].filter(function (n) {
                    return !(!e || n === e) || (i.element.removeEventListener(t, n, !1), !1);
                });
            }),
            (x.prototype.unbindAll = function () {
                for (var t in this.handlers) this.unbind(t);
            }),
            (k.isEmpty.get = function () {
                var t = this;
                return Object.keys(this.handlers).every(function (e) {
                    return 0 === t.handlers[e].length;
                });
            }),
            Object.defineProperties(x.prototype, k);
        var C = function () {
            this.eventElements = [];
        };
        (C.prototype.eventElement = function (t) {
            var e = this.eventElements.filter(function (e) {
                return e.element === t;
            })[0];
            return e || ((e = new x(t)), this.eventElements.push(e)), e;
        }),
            (C.prototype.bind = function (t, e, i) {
                this.eventElement(t).bind(e, i);
            }),
            (C.prototype.unbind = function (t, e, i) {
                var n = this.eventElement(t);
                n.unbind(e, i), n.isEmpty && this.eventElements.splice(this.eventElements.indexOf(n), 1);
            }),
            (C.prototype.unbindAll = function () {
                this.eventElements.forEach(function (t) {
                    return t.unbindAll();
                }),
                    (this.eventElements = []);
            }),
            (C.prototype.once = function (t, e, i) {
                var n = this.eventElement(t),
                    s = function (t) {
                        n.unbind(e, s), i(t);
                    };
                n.bind(e, s);
            });
        var T = {
                isWebKit: "undefined" != typeof document && "WebkitAppearance" in document.documentElement.style,
                supportsTouch:
                    "undefined" != typeof window && ("ontouchstart" in window || ("maxTouchPoints" in window.navigator && 0 < window.navigator.maxTouchPoints) || (window.DocumentTouch && document instanceof window.DocumentTouch)),
                supportsIePointer: "undefined" != typeof navigator && navigator.msMaxTouchPoints,
                isChrome: "undefined" != typeof navigator && /Chrome/i.test(navigator && navigator.userAgent),
            },
            D = {
                "click-rail": function (t) {
                    t.element,
                        t.event.bind(t.scrollbarY, "mousedown", function (t) {
                            return t.stopPropagation();
                        }),
                        t.event.bind(t.scrollbarYRail, "mousedown", function (e) {
                            var i = e.pageY - window.pageYOffset - t.scrollbarYRail.getBoundingClientRect().top > t.scrollbarYTop ? 1 : -1;
                            (t.element.scrollTop += i * t.containerHeight), g(t), e.stopPropagation();
                        }),
                        t.event.bind(t.scrollbarX, "mousedown", function (t) {
                            return t.stopPropagation();
                        }),
                        t.event.bind(t.scrollbarXRail, "mousedown", function (e) {
                            var i = e.pageX - window.pageXOffset - t.scrollbarXRail.getBoundingClientRect().left > t.scrollbarXLeft ? 1 : -1;
                            (t.element.scrollLeft += i * t.containerWidth), g(t), e.stopPropagation();
                        });
                },
                "drag-thumb": function (t) {
                    _(t, ["containerWidth", "contentWidth", "pageX", "railXWidth", "scrollbarX", "scrollbarXWidth", "scrollLeft", "x", "scrollbarXRail"]),
                        _(t, ["containerHeight", "contentHeight", "pageY", "railYHeight", "scrollbarY", "scrollbarYHeight", "scrollTop", "y", "scrollbarYRail"]);
                },
                keyboard: function (t) {
                    var i = t.element,
                        n = function () {
                            return o(i, ":hover");
                        },
                        s = function () {
                            return o(t.scrollbarX, ":focus") || o(t.scrollbarY, ":focus");
                        };
                    t.event.bind(t.ownerDocument, "keydown", function (o) {
                        if (!((o.isDefaultPrevented && o.isDefaultPrevented()) || o.defaultPrevented) && (n() || s())) {
                            var r = document.activeElement ? document.activeElement : t.ownerDocument.activeElement;
                            if (r) {
                                if ("IFRAME" === r.tagName) r = r.contentDocument.activeElement;
                                else for (; r.shadowRoot; ) r = r.shadowRoot.activeElement;
                                if (f(r)) return;
                            }
                            var a = 0,
                                l = 0;
                            switch (o.which) {
                                case 37:
                                    a = o.metaKey ? -t.contentWidth : o.altKey ? -t.containerWidth : -30;
                                    break;
                                case 38:
                                    l = o.metaKey ? t.contentHeight : o.altKey ? t.containerHeight : 30;
                                    break;
                                case 39:
                                    a = o.metaKey ? t.contentWidth : o.altKey ? t.containerWidth : 30;
                                    break;
                                case 40:
                                    l = o.metaKey ? -t.contentHeight : o.altKey ? -t.containerHeight : -30;
                                    break;
                                case 32:
                                    l = o.shiftKey ? t.containerHeight : -t.containerHeight;
                                    break;
                                case 33:
                                    l = t.containerHeight;
                                    break;
                                case 34:
                                    l = -t.containerHeight;
                                    break;
                                case 36:
                                    l = t.contentHeight;
                                    break;
                                case 35:
                                    l = -t.contentHeight;
                                    break;
                                default:
                                    return;
                            }
                            (t.settings.suppressScrollX && 0 !== a) ||
                                (t.settings.suppressScrollY && 0 !== l) ||
                                ((i.scrollTop -= l),
                                (i.scrollLeft += a),
                                g(t),
                                (function (n, s) {
                                    var o = e(i.scrollTop);
                                    if (0 === n) {
                                        if (!t.scrollbarYActive) return !1;
                                        if ((0 === o && 0 < s) || (o >= t.contentHeight - t.containerHeight && 0 > s)) return !t.settings.wheelPropagation;
                                    }
                                    var r = i.scrollLeft;
                                    if (0 === s) {
                                        if (!t.scrollbarXActive) return !1;
                                        if ((0 === r && 0 > n) || (r >= t.contentWidth - t.containerWidth && 0 < n)) return !t.settings.wheelPropagation;
                                    }
                                    return !0;
                                })(a, l) && o.preventDefault());
                        }
                    });
                },
                wheel: function (n) {
                    function s(t, e, n) {
                        if (!T.isWebKit && r.querySelector("select:focus")) return !0;
                        if (!r.contains(t)) return !1;
                        for (var s = t; s && s !== r; ) {
                            if (s.classList.contains(y.element.consuming)) return !0;
                            var o = i(s);
                            if (n && o.overflowY.match(/(scroll|auto)/)) {
                                var a = s.scrollHeight - s.clientHeight;
                                if (0 < a && ((0 < s.scrollTop && 0 > n) || (s.scrollTop < a && 0 < n))) return !0;
                            }
                            if (e && o.overflowX.match(/(scroll|auto)/)) {
                                var l = s.scrollWidth - s.clientWidth;
                                if (0 < l && ((0 < s.scrollLeft && 0 > e) || (s.scrollLeft < l && 0 < e))) return !0;
                            }
                            s = s.parentNode;
                        }
                        return !1;
                    }
                    function o(i) {
                        var o = (function (t) {
                                var e = t.deltaX,
                                    i = -1 * t.deltaY;
                                return (
                                    (void 0 === e || void 0 === i) && ((e = (-1 * t.wheelDeltaX) / 6), (i = t.wheelDeltaY / 6)),
                                    t.deltaMode && 1 === t.deltaMode && ((e *= 10), (i *= 10)),
                                    e != e && i != i && ((e = 0), (i = t.wheelDelta)),
                                    t.shiftKey ? [-i, -e] : [e, i]
                                );
                            })(i),
                            a = o[0],
                            l = o[1];
                        if (!s(i.target, a, l)) {
                            var h = !1;
                            n.settings.useBothWheelAxes
                                ? n.scrollbarYActive && !n.scrollbarXActive
                                    ? (l ? (r.scrollTop -= l * n.settings.wheelSpeed) : (r.scrollTop += a * n.settings.wheelSpeed), (h = !0))
                                    : n.scrollbarXActive && !n.scrollbarYActive && (a ? (r.scrollLeft += a * n.settings.wheelSpeed) : (r.scrollLeft -= l * n.settings.wheelSpeed), (h = !0))
                                : ((r.scrollTop -= l * n.settings.wheelSpeed), (r.scrollLeft += a * n.settings.wheelSpeed)),
                                g(n),
                                (h =
                                    h ||
                                    (function (i, s) {
                                        var o = e(r.scrollTop),
                                            a = 0 === r.scrollTop,
                                            l = o + r.offsetHeight === r.scrollHeight,
                                            h = 0 === r.scrollLeft,
                                            c = r.scrollLeft + r.offsetWidth === r.scrollWidth;
                                        return !(t(s) > t(i) ? a || l : h || c) || !n.settings.wheelPropagation;
                                    })(a, l)),
                                h && !i.ctrlKey && (i.stopPropagation(), i.preventDefault());
                        }
                    }
                    var r = n.element;
                    void 0 === window.onwheel ? void 0 !== window.onmousewheel && n.event.bind(r, "mousewheel", o) : n.event.bind(r, "wheel", o);
                },
                touch: function (n) {
                    function s(i, s) {
                        var o = e(d.scrollTop),
                            r = d.scrollLeft,
                            a = t(i),
                            l = t(s);
                        if (l > a) {
                            if ((0 > s && o === n.contentHeight - n.containerHeight) || (0 < s && 0 === o)) return 0 === window.scrollY && 0 < s && T.isChrome;
                        } else if (a > l && ((0 > i && r === n.contentWidth - n.containerWidth) || (0 < i && 0 === r))) return !0;
                        return !0;
                    }
                    function o(t, e) {
                        (d.scrollTop -= e), (d.scrollLeft -= t), g(n);
                    }
                    function r(t) {
                        return t.targetTouches ? t.targetTouches[0] : t;
                    }
                    function a(t) {
                        return !(
                            (t.pointerType && "pen" === t.pointerType && 0 === t.buttons) ||
                            ((!t.targetTouches || 1 !== t.targetTouches.length) && (!t.pointerType || "mouse" === t.pointerType || t.pointerType === t.MSPOINTER_TYPE_MOUSE))
                        );
                    }
                    function l(t) {
                        if (a(t)) {
                            var e = r(t);
                            (p.pageX = e.pageX), (p.pageY = e.pageY), (f = new Date().getTime()), null !== v && clearInterval(v);
                        }
                    }
                    function h(t, e, n) {
                        if (!d.contains(t)) return !1;
                        for (var s = t; s && s !== d; ) {
                            if (s.classList.contains(y.element.consuming)) return !0;
                            var o = i(s);
                            if (n && o.overflowY.match(/(scroll|auto)/)) {
                                var r = s.scrollHeight - s.clientHeight;
                                if (0 < r && ((0 < s.scrollTop && 0 > n) || (s.scrollTop < r && 0 < n))) return !0;
                            }
                            if (e && o.overflowX.match(/(scroll|auto)/)) {
                                var a = s.scrollWidth - s.clientWidth;
                                if (0 < a && ((0 < s.scrollLeft && 0 > e) || (s.scrollLeft < a && 0 < e))) return !0;
                            }
                            s = s.parentNode;
                        }
                        return !1;
                    }
                    function c(t) {
                        if (a(t)) {
                            var e = r(t),
                                i = { pageX: e.pageX, pageY: e.pageY },
                                n = i.pageX - p.pageX,
                                l = i.pageY - p.pageY;
                            if (h(t.target, n, l)) return;
                            o(n, l), (p = i);
                            var c = new Date().getTime(),
                                u = c - f;
                            0 < u && ((m.x = n / u), (m.y = l / u), (f = c)), s(n, l) && t.preventDefault();
                        }
                    }
                    function u() {
                        n.settings.swipeEasing &&
                            (clearInterval(v),
                            (v = setInterval(function () {
                                return n.isInitialized
                                    ? void clearInterval(v)
                                    : m.x || m.y
                                    ? 0.01 > t(m.x) && 0.01 > t(m.y)
                                        ? void clearInterval(v)
                                        : n.element
                                        ? (o(30 * m.x, 30 * m.y), (m.x *= 0.8), void (m.y *= 0.8))
                                        : void clearInterval(v)
                                    : void clearInterval(v);
                            }, 10)));
                    }
                    if (T.supportsTouch || T.supportsIePointer) {
                        var d = n.element,
                            p = {},
                            f = 0,
                            m = {},
                            v = null;
                        T.supportsTouch
                            ? (n.event.bind(d, "touchstart", l), n.event.bind(d, "touchmove", c), n.event.bind(d, "touchend", u))
                            : T.supportsIePointer &&
                              (window.PointerEvent
                                  ? (n.event.bind(d, "pointerdown", l), n.event.bind(d, "pointermove", c), n.event.bind(d, "pointerup", u))
                                  : window.MSPointerEvent && (n.event.bind(d, "MSPointerDown", l), n.event.bind(d, "MSPointerMove", c), n.event.bind(d, "MSPointerUp", u)));
                    }
                },
            },
            S = function (t, o) {
                var r = this;
                if ((void 0 === o && (o = {}), "string" == typeof t && (t = document.querySelector(t)), !t || !t.nodeName)) throw new Error("no element is specified to initialize PerfectScrollbar");
                for (var a in ((this.element = t),
                t.classList.add(y.main),
                (this.settings = {
                    handlers: ["click-rail", "drag-thumb", "keyboard", "wheel", "touch"],
                    maxScrollbarLength: null,
                    minScrollbarLength: null,
                    scrollingThreshold: 1e3,
                    scrollXMarginOffset: 0,
                    scrollYMarginOffset: 0,
                    suppressScrollX: !1,
                    suppressScrollY: !1,
                    swipeEasing: !0,
                    useBothWheelAxes: !1,
                    wheelPropagation: !0,
                    wheelSpeed: 1,
                }),
                o))
                    this.settings[a] = o[a];
                (this.containerWidth = null), (this.containerHeight = null), (this.contentWidth = null), (this.contentHeight = null);
                var l = function () {
                        return t.classList.add(y.state.focus);
                    },
                    h = function () {
                        return t.classList.remove(y.state.focus);
                    };
                (this.isRtl = "rtl" === i(t).direction),
                    !0 === this.isRtl && t.classList.add(y.rtl),
                    (this.isNegativeScroll = (function () {
                        var e,
                            i = t.scrollLeft;
                        return (t.scrollLeft = -1), (e = 0 > t.scrollLeft), (t.scrollLeft = i), e;
                    })()),
                    (this.negativeScrollAdjustment = this.isNegativeScroll ? t.scrollWidth - t.clientWidth : 0),
                    (this.event = new C()),
                    (this.ownerDocument = t.ownerDocument || document),
                    (this.scrollbarXRail = s(y.element.rail("x"))),
                    t.appendChild(this.scrollbarXRail),
                    (this.scrollbarX = s(y.element.thumb("x"))),
                    this.scrollbarXRail.appendChild(this.scrollbarX),
                    this.scrollbarX.setAttribute("tabindex", 0),
                    this.event.bind(this.scrollbarX, "focus", l),
                    this.event.bind(this.scrollbarX, "blur", h),
                    (this.scrollbarXActive = null),
                    (this.scrollbarXWidth = null),
                    (this.scrollbarXLeft = null);
                var c = i(this.scrollbarXRail);
                (this.scrollbarXBottom = parseInt(c.bottom, 10)),
                    isNaN(this.scrollbarXBottom) ? ((this.isScrollbarXUsingBottom = !1), (this.scrollbarXTop = p(c.top))) : (this.isScrollbarXUsingBottom = !0),
                    (this.railBorderXWidth = p(c.borderLeftWidth) + p(c.borderRightWidth)),
                    n(this.scrollbarXRail, { display: "block" }),
                    (this.railXMarginWidth = p(c.marginLeft) + p(c.marginRight)),
                    n(this.scrollbarXRail, { display: "" }),
                    (this.railXWidth = null),
                    (this.railXRatio = null),
                    (this.scrollbarYRail = s(y.element.rail("y"))),
                    t.appendChild(this.scrollbarYRail),
                    (this.scrollbarY = s(y.element.thumb("y"))),
                    this.scrollbarYRail.appendChild(this.scrollbarY),
                    this.scrollbarY.setAttribute("tabindex", 0),
                    this.event.bind(this.scrollbarY, "focus", l),
                    this.event.bind(this.scrollbarY, "blur", h),
                    (this.scrollbarYActive = null),
                    (this.scrollbarYHeight = null),
                    (this.scrollbarYTop = null);
                var u = i(this.scrollbarYRail);
                (this.scrollbarYRight = parseInt(u.right, 10)),
                    isNaN(this.scrollbarYRight) ? ((this.isScrollbarYUsingRight = !1), (this.scrollbarYLeft = p(u.left))) : (this.isScrollbarYUsingRight = !0),
                    (this.scrollbarYOuterWidth = this.isRtl
                        ? (function (t) {
                              var e = i(t);
                              return p(e.width) + p(e.paddingLeft) + p(e.paddingRight) + p(e.borderLeftWidth) + p(e.borderRightWidth);
                          })(this.scrollbarY)
                        : null),
                    (this.railBorderYWidth = p(u.borderTopWidth) + p(u.borderBottomWidth)),
                    n(this.scrollbarYRail, { display: "block" }),
                    (this.railYMarginHeight = p(u.marginTop) + p(u.marginBottom)),
                    n(this.scrollbarYRail, { display: "" }),
                    (this.railYHeight = null),
                    (this.railYRatio = null),
                    (this.reach = {
                        x: 0 >= t.scrollLeft ? "start" : t.scrollLeft >= this.contentWidth - this.containerWidth ? "end" : null,
                        y: 0 >= t.scrollTop ? "start" : t.scrollTop >= this.contentHeight - this.containerHeight ? "end" : null,
                    }),
                    (this.isAlive = !0),
                    this.settings.handlers.forEach(function (t) {
                        return D[t](r);
                    }),
                    (this.lastScrollTop = e(t.scrollTop)),
                    (this.lastScrollLeft = t.scrollLeft),
                    this.event.bind(this.element, "scroll", function (t) {
                        return r.onScroll(t);
                    }),
                    g(this);
            };
        return (
            (S.prototype.update = function () {
                this.isAlive &&
                    ((this.negativeScrollAdjustment = this.isNegativeScroll ? this.element.scrollWidth - this.element.clientWidth : 0),
                    n(this.scrollbarXRail, { display: "block" }),
                    n(this.scrollbarYRail, { display: "block" }),
                    (this.railXMarginWidth = p(i(this.scrollbarXRail).marginLeft) + p(i(this.scrollbarXRail).marginRight)),
                    (this.railYMarginHeight = p(i(this.scrollbarYRail).marginTop) + p(i(this.scrollbarYRail).marginBottom)),
                    n(this.scrollbarXRail, { display: "none" }),
                    n(this.scrollbarYRail, { display: "none" }),
                    g(this),
                    d(this, "top", 0, !1, !0),
                    d(this, "left", 0, !1, !0),
                    n(this.scrollbarXRail, { display: "" }),
                    n(this.scrollbarYRail, { display: "" }));
            }),
            (S.prototype.onScroll = function () {
                this.isAlive &&
                    (g(this),
                    d(this, "top", this.element.scrollTop - this.lastScrollTop),
                    d(this, "left", this.element.scrollLeft - this.lastScrollLeft),
                    (this.lastScrollTop = e(this.element.scrollTop)),
                    (this.lastScrollLeft = this.element.scrollLeft));
            }),
            (S.prototype.destroy = function () {
                this.isAlive &&
                    (this.event.unbindAll(),
                    r(this.scrollbarX),
                    r(this.scrollbarY),
                    r(this.scrollbarXRail),
                    r(this.scrollbarYRail),
                    this.removePsClasses(),
                    (this.element = null),
                    (this.scrollbarX = null),
                    (this.scrollbarY = null),
                    (this.scrollbarXRail = null),
                    (this.scrollbarYRail = null),
                    (this.isAlive = !1));
            }),
            (S.prototype.removePsClasses = function () {
                this.element.className = this.element.className
                    .split(" ")
                    .filter(function (t) {
                        return !t.match(/^ps([-_].+|)$/);
                    })
                    .join(" ");
            }),
            S
        );
    }),
    (function (t, e) {
        "object" == typeof exports && "undefined" != typeof module
            ? (module.exports = e())
            : "function" == typeof define && define.amd
            ? define(e)
            : ((t = "undefined" != typeof globalThis ? globalThis : t || self),
              (function () {
                  var i = t.Cookies,
                      n = (t.Cookies = e());
                  n.noConflict = function () {
                      return (t.Cookies = i), n;
                  };
              })());
    })(this, function () {
        "use strict";
        function t(t) {
            for (var e = 1; e < arguments.length; e++) {
                var i = arguments[e];
                for (var n in i) t[n] = i[n];
            }
            return t;
        }
        var e = (function e(i, n) {
            function s(e, s, o) {
                if ("undefined" != typeof document) {
                    "number" == typeof (o = t({}, n, o)).expires && (o.expires = new Date(Date.now() + 864e5 * o.expires)),
                        o.expires && (o.expires = o.expires.toUTCString()),
                        (e = encodeURIComponent(e)
                            .replace(/%(2[346B]|5E|60|7C)/g, decodeURIComponent)
                            .replace(/[()]/g, escape));
                    var r = "";
                    for (var a in o) o[a] && ((r += "; " + a), !0 !== o[a] && (r += "=" + o[a].split(";")[0]));
                    return (document.cookie = e + "=" + i.write(s, e) + r);
                }
            }
            return Object.create(
                {
                    set: s,
                    get: function (t) {
                        if ("undefined" != typeof document && (!arguments.length || t)) {
                            for (var e = document.cookie ? document.cookie.split("; ") : [], n = {}, s = 0; s < e.length; s++) {
                                var o = e[s].split("="),
                                    r = o.slice(1).join("=");
                                try {
                                    var a = decodeURIComponent(o[0]);
                                    if (((n[a] = i.read(r, a)), t === a)) break;
                                } catch (t) {}
                            }
                            return t ? n[t] : n;
                        }
                    },
                    remove: function (e, i) {
                        s(e, "", t({}, i, { expires: -1 }));
                    },
                    withAttributes: function (i) {
                        return e(this.converter, t({}, this.attributes, i));
                    },
                    withConverter: function (i) {
                        return e(t({}, this.converter, i), this.attributes);
                    },
                },
                { attributes: { value: Object.freeze(n) }, converter: { value: Object.freeze(i) } }
            );
        })(
            {
                read: function (t) {
                    return '"' === t[0] && (t = t.slice(1, -1)), t.replace(/(%[\dA-F]{2})+/gi, decodeURIComponent);
                },
                write: function (t) {
                    return encodeURIComponent(t).replace(/%(2[346BF]|3[AC-F]|40|5[BDE]|60|7[BCD])/g, decodeURIComponent);
                },
            },
            { path: "/" }
        );
        return e;
    }));
