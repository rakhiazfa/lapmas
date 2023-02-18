/**
 * Setup lodasn.
 *
 */

import _ from "lodash";
window._ = _;

/**
 * Setup axios.
 *
 */

import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

/**
 * Setup jQuery.
 *
 */

import $ from "jquery";

window.jQuery = window.$ = $;

$(document).ready(() => {
    /**
     * Preloader
     *
     */

    setTimeout(() => {
        $(".preloader").remove();
    }, 1500);

    /**
     * Handle topbar dropdown.
     *
     */

    $(".wrapper .topbar .dropdown-toggler").on("click", (e) => {
        !$(e.target)
            .closest(".topbar-dropdown")
            .find(".dropdown-menu")
            .hasClass("active")
            ? $(e.target)
                  .closest(".topbar-dropdown")
                  .find(".dropdown-menu")
                  .trigger("dropdown:show")
            : $(e.target)
                  .closest(".topbar-dropdown")
                  .find(".dropdown-menu")
                  .trigger("dropdown:hide");
    });

    $(".wrapper .topbar-dropdown .dropdown-menu").on("dropdown:show", (e) => {
        $(".wrapper .topbar-dropdown .dropdown-menu")
            .not(e.target)
            .trigger("dropdown:hide");

        $(e.target).addClass("active");
    });

    $(".wrapper .topbar-dropdown .dropdown-menu").on("dropdown:hide", (e) => {
        $(e.target).removeClass("active");
    });

    /**
     * Handle sidebar.
     *
     */

    $(".wrapper .topbar .sidebar-toggler").on("click", (e) => {
        e.stopPropagation();

        !$(".sidebar").hasClass("active")
            ? $(".wrapper .sidebar").trigger("sidebar:show")
            : $(".wrapper .sidebar").trigger("sidebar:hide");
    });

    $(".wrapper .sidebar").on("sidebar:show", (e) => {
        $(".wrapper .overlay").addClass("active");
        $(e.target).addClass("active");

        $(".wrapper .topbar-dropdown .dropdown-menu").trigger("dropdown:hide");
    });

    $(".wrapper .sidebar").on("sidebar:hide", (e) => {
        $(".wrapper .overlay").removeClass("active");
        $(e.target).removeClass("active");
    });

    /**
     * Sidebar sidebar dropdown.
     *
     */

    $(".wrapper .sidebar .dropdown-toggler").on("click", (e) => {
        !$(e.target)
            .closest(".sidebar-dropdown")
            .find(".dropdown-menu")
            .hasClass("active")
            ? $(e.target)
                  .closest(".sidebar-dropdown")
                  .find(".dropdown-menu")
                  .trigger("dropdown:show")
            : $(e.target)
                  .closest(".sidebar-dropdown")
                  .find(".dropdown-menu")
                  .trigger("dropdown:hide");
    });

    $(".wrapper .sidebar-dropdown .dropdown-menu").on("dropdown:show", (e) => {
        $(".wrapper .sidebar-dropdown .dropdown-menu")
            .not(e.target)
            .trigger("dropdown:hide");

        $(e.target).addClass("active");

        const sidebarLinkHeight = $(
            ".wrapper .sidebar .sidebar-link"
        ).innerHeight();

        const sidebarLinkCount = $(e.target).find(".sidebar-link").length;

        $(e.target).css({
            height: `${sidebarLinkHeight * sidebarLinkCount}px`,
        });
    });

    $(".wrapper .sidebar-dropdown .dropdown-menu").on("dropdown:hide", (e) => {
        $(e.target).removeClass("active");

        $(e.target).css({
            height: "0px",
        });
    });

    /**
     * Handle sidebar submenu active.
     *
     */

    if (
        $(".wrapper .sidebar-dropdown .dropdown-menu .sidebar-link.active")
            .length
    ) {
        $(".wrapper .sidebar-dropdown .dropdown-menu .sidebar-link.active")
            .closest(".dropdown-menu")
            .trigger("dropdown:show");
    }

    /**
     * Handle click outside.
     *
     */
    $(document).on("click", function (e) {
        // Sidebar

        if ($(e.target).closest(".sidebar").length === 0) {
            $(".wrapper .sidebar").trigger("sidebar:hide");
        }

        // Dropdown

        if ($(e.target).closest(".topbar-dropdown").length === 0) {
            $(".wrapper .topbar-dropdown > .dropdown-menu").trigger(
                "dropdown:hide"
            );
        }
    });
});
