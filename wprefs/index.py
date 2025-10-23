"""Web helpers translated from the PHP ``index.php`` front-end."""

from __future__ import annotations

import html
import os
from typing import Mapping, Optional

from .csrf import generate_csrf_token, verify_csrf_token
from .wikibots.wikitext import get_wikipedia_text
from .work import DoChangesToText1


HEADER = """
<div class='card'>
  <div class='card-header aligncenter' style='font-weight:bold;'>
    <h3>Fix references in Wikipedia: <a href='https://hashtags.wmcloud.org/?query=mdwiki' target='_blank'>#mdwiki</a></h3>
  </div>
  <div class='card-body'>
"""

FOOTER = """
    </div>
  </div>
</div>
"""

SUBMIT_HTML = "<input class='btn btn-outline-primary' type='submit' value='start'>"
LOGIN_HTML = "<a class='btn btn-outline-primary' href='/auth/index.php?a=login'>login</a>"


def make_result(lang: str, title: str, sourcetitle: str, mdwiki_revid: str) -> str:
    text = get_wikipedia_text(title, lang)
    if not text:
        return "<h2>Wikitext not found</h2>"
    new_text = DoChangesToText1(sourcetitle, title, text, lang, mdwiki_revid)
    new_text_sanitized = html.escape(new_text, quote=True)
    no_changes = "true" if new_text.strip() == text.strip() else "false"
    return (
        "<h2>New Text: (no_changes: "
        + no_changes
        + ")</h2><textarea name=\"new_text\" rows=\"15\" cols=\"100\">"
        + new_text_sanitized
        + "</textarea>"
    )


def render_form(csrf_token: str, submit_or_login: str) -> str:
    return f"""
    <form action='/' method='POST'>
      <input name='csrf_token' value="{csrf_token}" type='hidden'/>
      <div class='container'>
        <div class='row'>
          <div class='col-md-3'>
            <div class='input-group mb-3'>
              <div class='input-group-prepend'><span class='input-group-text'>Langcode</span></div>
              <input class='form-control' type='text' name='lang' id='lang' value='ja' required />
            </div>
          </div>
          <div class='col-md-3'>
            <div class='input-group mb-3'>
              <div class='input-group-prepend'><span class='input-group-text'>title</span></div>
              <input class='form-control' type='text' id='title' name='title' value='利用者:Doc James/Rh血液型不適合' />
            </div>
          </div>
          <div class='col-md-3'>
            <div class='input-group mb-3'>
              <div class='input-group-prepend'><span class='input-group-text'>mdwiki title</span></div>
              <input class='form-control' type='text' id='sourcetitle' name='sourcetitle' value='' />
            </div>
          </div>
          <div class='col-md-3'>
            <div class='input-group mb-3'>
              <div class='input-group-prepend'><span class='input-group-text'>revid</span></div>
              <input class='form-control' type='text' id='revid' name='revid' value='' />
            </div>
          </div>
        </div>
        <div class='row'>
          <div class='col-md-3'>
            <h4 class='aligncenter'>{submit_or_login}</h4>
          </div>
        </div>
      </div>
    </form>
    """


def index_page(
    method: str,
    form_data: Mapping[str, str],
    session: Optional[dict] = None,
    user: str = "",
) -> str:
    submit_or_login = SUBMIT_HTML if user else LOGIN_HTML
    if method != "POST":
        token = generate_csrf_token(session)
        return HEADER + render_form(token, submit_or_login) + FOOTER

    token = form_data.get("csrf_token", "")
    if not verify_csrf_token(token, session):
        token = generate_csrf_token(session)
        error = "<div class='alert alert-danger' role='alert'>Invalid or Reused CSRF Token!</div>"
        return HEADER + error + render_form(token, submit_or_login) + FOOTER

    lang = form_data.get("lang", "").strip()
    title = form_data.get("title", "").strip()
    sourcetitle = form_data.get("sourcetitle", "").strip()
    mdwiki_revid = form_data.get("revid", "").strip()

    if not lang or not title:
        token = generate_csrf_token(session)
        return HEADER + render_form(token, submit_or_login) + FOOTER

    content = make_result(lang, title, sourcetitle, mdwiki_revid)
    return HEADER + content + FOOTER


def create_app():
    from flask import Flask, render_template_string, request, session

    app = Flask(__name__)
    app.secret_key = os.environ.get("WPREFS_SECRET", os.urandom(16))

    @app.route("/", methods=["GET", "POST"])
    def index_route():  # pragma: no cover - requires Flask runtime
        user = session.get("username", "")
        html_content = index_page(request.method, request.form, session, user)
        return render_template_string(html_content)

    return app


__all__ = ["make_result", "render_form", "index_page", "create_app"]
