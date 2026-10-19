from pathlib import Path
import json
import shutil
import html

root = Path.cwd()
out = root / '.lavish/assets/makers-polish'
sources = out / 'sources'
sources.mkdir(exist_ok=True)
for name, source in {
    'design': 'DESIGN.md', 'tokens': 'resources/css/tokens.css',
    'audience': 'resources/views/audience.blade.php',
    'maker': 'resources/views/applications/maker.blade.php',
    'intake': 'resources/views/applications/intake.blade.php',
    'css': 'resources/css/app.css', 'concerns': 'docs/wiki/build/01-build-concerns.md',
}.items():
    shutil.copyfile(root / source, sources / (name + '.txt'))

items = [
 dict(id='M01', title='Give the opening a red poster treatment.', category='Bolder + colorize', recommended=True,
      why='The current opening is all cream and left aligned. The back link also leaves a large gap before the headline.',
      change='Use a single red block, a cream headline and a two-column composition. Bring the back link closer and keep the date and application action together.',
      trade='The strongest visual change. The red belongs to the opening; the form stays on cream.', source='audience'),
 dict(id='M02', title='Give the process its own band.', category='Colorize', recommended=False,
      why='Commitment and process currently blend into the same background as everything around them.',
      change='Use the existing darker cream as a broad band. Red number blocks make the three real steps visible at a glance.',
      trade='Optional extra colour. It makes the middle of the page denser; skip this if the red opening feels strong enough.', source='css'),
 dict(id='M03', title='Make the pay answer a compact note.', category='Polish', recommended=True,
      why='A large section heading gives one short answer more space than it needs before the form.',
      change='Keep the question and the volunteer answer, but put them together in a compact row. Stack them on mobile.',
      trade='The answer remains explicit. This is a hierarchy and spacing change, with no change to the commitment.', source='audience'),
 dict(id='M04', title='Use the desktop width for the application.', category='Bolder + layout', recommended=True,
      why='The form currently occupies a narrow column at the left, leaving much of the desktop page empty.',
      change='Put the application title, context and privacy note in a sticky left column, with the grouped form on the right. Stack the introduction above the form on mobile.',
      trade='This is the main layout change. The form stays fully visible on one page, with its existing anchor and submission flow.', source='intake'),
 dict(id='M05', title='Keep labels together and pair short answers.', category='Polish', recommended=True,
      why='Required asterisks appear on their own lines. Name, email and language levels each consume a full row.',
      change='Keep each label and its marker on one line. Pair name and email on desktop, align language levels in a row, and let long answers use the full form width.',
      trade='Fields and requiredness stay the same. Name and email return to one column on mobile.', source='maker'),
 dict(id='M06', title='Make the fields look ready to use.', category='Polish + colorize', recommended=True,
      why='The pale fields and faint rules have little definition against the paper background. Short textareas also inherit the same minimum height as longer answers.',
      change='Use a clear red outline, the existing 10px corner radius, a cream-light fill and an ink focus ring. Size short textareas for short answers.',
      trade='A functional use of colour. Keep error copy in contrast-safe ink, with a visible border and text explanation.', source='css'),
 dict(id='M07', title='Let choices show their selected state.', category='Colorize + interaction', recommended=False,
      why='Ten theme choices and the practical checks currently read as a loose list of small checkboxes.',
      change='Make the whole label a roomy target. Selected options use red and cream while keeping their native checkbox and tick visible.',
      trade='Optional: this adds more visual weight to preferences. It changes presentation only, with no new options or defaults.', source='maker'),
 dict(id='M08', title='Finish with a clear send area.', category='Polish + interaction', recommended=True,
      why='The current square send button differs from the site\'s pill buttons, and the last action has little separation from the preceding fields.',
      change='Give the required-field note and send action a darker-cream panel. Use the established pill shape, clear focus, a sending state and a readable error summary.',
      trade='The preview shows the finish and selected practical checks. Sending, validation and receipt behaviour must be verified during implementation.', source='maker'),
]

def esc(value): return html.escape(value, quote=True)
rows = []
for item in items:
    ident = item['id']
    panes = ''
    for device in ('desktop', 'mobile'):
        figures = ''
        for version, label in [('current', 'Current page'), ('proposed', 'Proposed treatment')]:
            image = f'assets/makers-polish/{ident}-{device}-{version}.png'
            figures += f'<figure><figcaption>{label}</figcaption><a href="{image}" target="_blank" rel="noopener"><img src="{image}" loading="lazy" alt="{esc(item["title"])} {label.lower()}, {device}"></a></figure>'
        panes += f'<div class="comparison {device}" data-device="{device}" {"hidden" if device == "mobile" else ""}>{figures}</div>'
    extra = ''
    if ident == 'M08':
        extra = '<details><summary>See the proposed send panel</summary><img class="submit-detail" src="assets/makers-polish/M08-desktop-proposed-submit.png" loading="lazy" alt="Cream inset panel with required-field note and red pill send button"></details>'
    rows.append(f'''<article class="decision" id="{ident}">
<div class="decision-head"><span class="item-id">{ident}</span><div><p class="category">{item['category']} · {'Recommended' if item['recommended'] else 'Optional'}</p><h2>{item['title']}</h2></div><label class="choice"><input form="review" type="checkbox" name="items" value="{ident}" data-label="{esc(item['title'])}"> Include {ident}</label></div>
<div class="explanation"><p>{item['why']}</p><p><strong>The proposal.</strong> {item['change']}</p></div>
<div class="device-switch" role="group" aria-label="Preview size for {ident}"><button type="button" data-device-button="desktop" aria-pressed="true">Desktop</button><button type="button" data-device-button="mobile" aria-pressed="false">Mobile</button></div>{panes}{extra}
<p class="trade">{item['trade']} <a href="assets/makers-polish/sources/{item['source']}.txt" target="_blank" rel="noopener">Source</a></p></article>''')

style = '''
@font-face{font-family:Display;src:url(assets/makers-polish/fonts/a3481c02dc63831a.woff2);font-weight:600;font-display:swap}
@font-face{font-family:Body;src:url(assets/makers-polish/fonts/23310fc1bc6aca45.woff2);font-weight:400;font-display:swap}
@font-face{font-family:Body;src:url(assets/makers-polish/fonts/d5911ef167280b45.woff2);font-weight:600;font-display:swap}
:root{--cream:#f5e2ca;--light:#fff6ea;--inset:#ebd5bb;--red:#c33f40;--ink:#1e1a16;--line:rgba(195,63,64,.35)}
*{box-sizing:border-box}html{scroll-behavior:smooth;scroll-padding-top:90px}body{margin:0;background:var(--cream);color:var(--ink);font:16px/1.65 Body,Arial,sans-serif}button,input,textarea{font:inherit}button,a,input,textarea{touch-action:manipulation}button{cursor:pointer}a{color:inherit;text-underline-offset:4px}button:focus-visible,a:focus-visible,input:focus-visible,textarea:focus-visible,summary:focus-visible{outline:3px solid var(--ink);outline-offset:4px}img{display:block;max-width:100%;height:auto}h1,h2,h3,p,figure{margin:0}h1,h2,h3{font-family:Display,Impact,sans-serif;font-weight:600;text-transform:uppercase;line-height:.98}h1{font-size:clamp(64px,9vw,118px);max-width:13ch}h2{font-size:clamp(32px,3.7vw,52px)}h3{font-size:34px}p+p{margin-top:16px}[hidden]{display:none!important}.wrap{max-width:1280px;margin:auto;padding-inline:32px}.masthead{margin:18px;border-radius:18px;background:var(--red);color:var(--light);padding:54px clamp(24px,6vw,88px)}.masthead p{max-width:64ch;margin-top:28px;font-size:18px}.topline{display:flex;justify-content:space-between;gap:24px;border-bottom:1px solid currentColor;padding-bottom:20px;margin-bottom:40px;font-size:13px}.topline span:last-child{text-align:right}.toolbar{position:sticky;top:0;z-index:4;display:flex;justify-content:space-between;align-items:center;gap:16px;padding:15px 32px;background:var(--cream);border-bottom:1px solid var(--line)}.toolbar span{font-size:13px}.button,button{display:inline-flex;align-items:center;justify-content:center;min-height:44px;padding:10px 18px;border:1px solid var(--red);border-radius:999px;background:transparent;color:var(--ink);font-size:13px;text-decoration:none;font-weight:600}.button.primary,button.primary{background:var(--red);color:var(--light)}.intro{display:grid;grid-template-columns:minmax(0,1.4fr) minmax(0,1fr);gap:56px;padding-block:58px}.intro h2{color:var(--red);margin-bottom:20px}.palette{display:flex;margin-top:24px;gap:10px}.swatch{width:48px;height:48px;border-radius:50%;border:1px solid var(--line)}.intro aside{padding:26px;background:var(--inset);border-radius:18px}.intro aside h3{margin-bottom:14px}.jump{display:flex;flex-wrap:wrap;gap:10px;padding-bottom:32px}.jump a{font-size:13px}.preview-links{display:flex;gap:12px;flex-wrap:wrap;margin-top:24px}.decision{padding:52px 0;border-top:1px solid var(--red);min-width:0}.decision-head{display:grid;grid-template-columns:58px minmax(0,1fr) auto;align-items:start;gap:20px}.item-id{font-family:Display;font-size:34px;line-height:1;color:var(--red)}.category{font-size:13px;margin-bottom:10px}.choice{display:flex;align-items:center;gap:10px;min-height:48px;white-space:nowrap;padding:8px 14px;border:1px solid var(--red);border-radius:999px;cursor:pointer}.choice:has(:checked){background:var(--red);color:var(--light)}input[type=checkbox]{width:20px;height:20px;accent-color:var(--red);flex:0 0 auto}.explanation{display:grid;grid-template-columns:1fr 1fr;gap:36px;margin:28px 0}.explanation p{margin:0;max-width:62ch}.device-switch{display:flex;gap:8px;margin-bottom:16px}.device-switch button[aria-pressed=true]{background:var(--ink);border-color:var(--ink);color:var(--light)}.comparison{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:20px;align-items:start;min-width:0}.comparison>*{min-width:0}figure{background:var(--light);padding:12px;border-radius:10px;overflow:hidden}figcaption{font-size:13px;font-weight:600;padding:0 0 12px}.comparison img{width:100%}.comparison.mobile figure img{max-width:390px;margin:auto}.trade{max-width:94ch;font-size:14px;margin-top:20px}.submit-detail{max-width:640px;margin-top:20px}details{margin-top:20px}summary{cursor:pointer;text-decoration:underline;text-underline-offset:4px}.decision:has(input:checked) .item-id{text-decoration:underline;text-underline-offset:8px}.review{padding:40px;background:var(--inset);border-radius:18px;margin:32px 0 48px}.review h2{margin-bottom:20px}.review textarea{display:block;width:100%;min-height:100px;border:1px solid var(--red);border-radius:10px;background:var(--light);margin-top:10px;padding:14px}.actions{display:flex;gap:12px;flex-wrap:wrap;margin:22px 0 14px}.status{font-size:14px;min-height:28px}.sources{padding-block:30px;border-top:1px solid var(--line);font-size:14px}.sources ul{padding-left:20px}.end{display:flex;justify-content:space-between;gap:24px;padding:28px 0 40px;font-size:13px}.recommend{font-weight:600}.review small{display:block;margin-top:14px}.selection{margin-bottom:22px}.selection span{display:block;margin-top:8px}.all-preview{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin:0 0 40px}.all-preview a{display:block;max-height:490px;overflow:hidden;border-radius:10px;background:var(--light);text-decoration:none}.all-preview img{width:100%}.all-preview strong{display:block;padding:12px 16px}.all-preview .thumb{height:440px;overflow:hidden}.all-preview img{width:100%;height:auto} @media(max-width:760px){.wrap{padding-inline:20px}.masthead{margin:10px;padding:32px 24px}.topline{display:block}.topline span{display:block}.topline span:last-child{text-align:left;margin-top:8px}.toolbar{padding:12px 20px}.intro{grid-template-columns:1fr;gap:28px;padding-block:36px}.decision-head{grid-template-columns:42px minmax(0,1fr);gap:14px}.choice{grid-column:2;justify-self:start}.explanation{grid-template-columns:1fr;gap:14px}.comparison{gap:10px}.comparison figure{padding:7px}figcaption{font-size:11px}.review{padding:26px 20px}.end{display:block}.end span{display:block;margin-top:10px}.all-preview{gap:10px}.all-preview .thumb{height:260px}} @media(prefers-reduced-motion:reduce){html{scroll-behavior:auto}}
'''

script = '''
const items=ITEMS;
const boxes=[...document.querySelectorAll('input[name=items]')],notes=document.querySelector('#notes'),receipt=document.querySelector('#receipt');let queued=false;
const key='makeamark-makers-polish-2026-10-18';
function selected(){return boxes.filter(b=>b.checked).map(b=>({id:b.value,label:b.dataset.label,disposition:'implement'}))}
function update(){const s=selected();document.querySelector('#count').textContent=s.length+' of 8 selected';document.querySelector('#selection-summary').textContent=s.length?s.map(i=>i.id+' '+i.label).join(' / '):'Nothing selected yet.';try{localStorage.setItem(key,JSON.stringify({ids:s.map(i=>i.id),notes:notes.value}))}catch{}if(queued){receipt.textContent='Selection changed locally. Queue again to update the unsent request.';queued=false}}
try{const saved=JSON.parse(localStorage.getItem(key)||'null');if(saved){boxes.forEach(b=>b.checked=saved.ids.includes(b.value));notes.value=saved.notes||''}}catch{}
boxes.forEach(b=>b.addEventListener('change',update));notes.addEventListener('input',update);update();
document.querySelector('#recommended').onclick=()=>{boxes.forEach(b=>b.checked=items.find(i=>i.id===b.value).recommended);update()};
document.querySelector('#clear').onclick=()=>{boxes.forEach(b=>b.checked=false);update()};
document.querySelectorAll('[data-device-button]').forEach(button=>button.onclick=()=>{const row=button.closest('.decision');row.querySelectorAll('[data-device-button]').forEach(b=>b.setAttribute('aria-pressed',String(b===button)));row.querySelectorAll('[data-device]').forEach(p=>p.hidden=p.dataset.device!==button.dataset.deviceButton)});
function answer(){return 'Implement these selected makers-page design improvements: '+selected().map(i=>i.id+' '+i.label).join('; ')+'. Keep unselected items unchanged. Scope this request to /makers and its application form; do not restyle /organisations unless I explicitly request it. Preserve existing fields, requiredness, privacy content, inline application, validation and receipts. '+(notes.value.trim()?'Adjustments: '+notes.value.trim()+'. ':'')+'Account for every submitted ID with an outcome and verification before reporting completion.'}
document.querySelector('#review').onsubmit=e=>{e.preventDefault();if(!selected().length){receipt.textContent='Choose at least one suggestion first.';return}if(!window.lavish?.queuePrompt){receipt.textContent='Open in Lavish to queue, or copy the selection into the chat.';return}window.lavish.queuePrompt(answer(),{tag:'tracked-batch',queueKey:'makers-polish',text:'Makers page: '+selected().map(i=>i.id).join(', '),element:e.currentTarget,data:{items:selected(),notes:notes.value.trim(),scope:'/makers'}});queued=true;receipt.textContent='Queued '+selected().map(i=>i.id).join(', ')+'. Press Send to Agent in Lavish when ready.'};
document.querySelector('#copy').onclick=async()=>{if(!selected().length){receipt.textContent='Choose at least one suggestion first.';return}try{await navigator.clipboard.writeText(answer());receipt.textContent='Copied. Paste into the chat to request these changes.'}catch{const t=document.createElement('textarea');t.value=answer();t.readOnly=true;receipt.replaceChildren(t);t.select()}};
'''.replace('ITEMS', json.dumps(items))

page = f'''<!doctype html><html lang="en" data-lavish-annotate="off"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Makers page · Eight design suggestions</title><style>{style}</style></head><body>
<header class="masthead"><div class="topline"><span>Make a Mark Brussels / Makers page</span><span>Design suggestions · 18 October 2026</span></div><h1>Give the page some punch.</h1><p>More red. Better rhythm. A form that feels finished. Eight suggestions for the makers page, shown against the running version.</p></header>
<nav class="toolbar" aria-label="Review"><span id="count" aria-live="polite">0 of 8 selected</span><a class="button primary" href="#review">Review your selection</a></nav>
<main class="wrap"><section class="intro"><div><h2>Use the identity at full strength.</h2><p>My recommendation is a red opening, a quieter transition into the application and a form that uses the desktop width. Fix the label rhythm and control states at the same time.</p><p>The colour comes from the existing palette. Marker Red carries the opening, actions and selection; the two cream tones separate reading from filling in answers.</p><div class="palette" aria-label="Existing palette"><span class="swatch" style="background:#c33f40" title="Marker Red"></span><span class="swatch" style="background:#f5e2ca" title="Paper Cream"></span><span class="swatch" style="background:#ebd5bb" title="Cream Inset"></span><span class="swatch" style="background:#fff6ea" title="Cream Light"></span><span class="swatch" style="background:#1e1a16" title="Ink"></span></div></div><aside><h3>Start with six.</h3><p class="recommend">M01, M03, M04, M05, M06 and M08.</p><p>M02 and M07 add more colour and density. The combined preview shows all eight so you can judge the stronger version.</p><p>These are review mockups. The public page has not been restyled. Selection applies to the makers page only.</p></aside></section>
<div class="all-preview"><a href="assets/makers-polish/current.html" target="_blank" rel="noopener"><strong>Open the current page snapshot</strong><div class="thumb"><img src="assets/makers-polish/desktop-current-full.png" alt="Current makers page full-page screenshot"></div></a><a href="assets/makers-polish/proposed.html" target="_blank" rel="noopener"><strong>Open the combined proposal</strong><div class="thumb"><img src="assets/makers-polish/desktop-proposed-full.png" alt="Combined makers-page proposal full-page screenshot"></div></a></div>
<p>The comparisons below are real browser renders. They show the combined treatment at desktop and mobile widths; some form groups are cropped to their opening. Open either full snapshot to inspect the whole page. The proposal allows typing and selecting, but never sends an application.</p>
<nav class="jump" aria-label="Suggestions">{''.join(f'<a class="button" href="#{i["id"]}">{i["id"]}</a>' for i in items)}</nav>
{''.join(rows)}
<form id="review" class="review" data-lavish-question="makers-polish"><h2>Which changes should we make?</h2><div class="selection"><strong>Your current selection</strong><span id="selection-summary">Nothing selected yet.</span></div><div class="actions"><button id="recommended" type="button">Select the recommended six</button><button id="clear" type="button">Clear</button></div><label for="notes">Anything to adjust?</label><textarea id="notes" name="notes" placeholder="For example: include the red hero, but keep theme checkboxes plain."></textarea><div class="actions"><button class="primary" type="submit">Queue selected changes</button><button id="copy" type="button">Copy selection</button></div><p id="receipt" class="status" role="status" aria-live="polite">Choices stay local until you queue them.</p><small>After queueing, use Send to Agent in Lavish. You can also annotate any preview using the toolbar.</small></form>
<details class="sources"><summary>Evidence, scope and checks</summary><ul><li><a href="assets/makers-polish/observations.json" target="_blank">Browser measurements</a> record the current and proposed layouts at 1440px and 390px. Both proposals have zero horizontal overflow. This is a bounded design review, with no claim of measured conversion improvement.</li><li><a href="assets/makers-polish/sources/design.txt" target="_blank">DESIGN.md snapshot</a> and <a href="assets/makers-polish/sources/tokens.txt" target="_blank">colour and type tokens</a> supplied the design direction. Existing fonts, two inks, cream surfaces and button shapes are retained.</li><li><a href="assets/makers-polish/sources/audience.txt" target="_blank">Audience template</a>, <a href="assets/makers-polish/sources/maker.txt" target="_blank">form</a> and <a href="assets/makers-polish/sources/css.txt" target="_blank">component CSS</a> supplied the current behaviour and layout. Proposed screenshots use temporary DOM and CSS overrides.</li><li><a href="assets/makers-polish/sources/concerns.txt" target="_blank">Build concerns</a> keep requiredness and mail operations open. These suggestions add no fields, promises, application steps or new brand colours.</li><li>Inputs, checkbox states and responsive layout are demonstrated. Full keyboard, validation, loading and receipt verification belongs to implementation of the selected changes.</li></ul></details>
<footer class="end"><span>Design source: the project's Brussels broadsheet identity.</span><span>Eight suggestions. Select the ones you want built.</span></footer></main><script>{script}</script></body></html>'''
(root / '.lavish/makers-polish.html').write_text(page)
print('.lavish/makers-polish.html')
