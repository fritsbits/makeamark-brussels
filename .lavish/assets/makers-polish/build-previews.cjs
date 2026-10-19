const { chromium } = require('playwright');
const fs = require('node:fs');
const path = require('node:path');
const sharp = require('sharp');
const root = process.cwd();
const out = __dirname;
const base = 'https://makeamark-southern-ocean.test';
const css = `
.proposal .audience-hero { width:calc(100% - 2 * var(--block-margin)); max-width:none; margin:0 var(--block-margin) 64px; padding:36px clamp(24px,5vw,72px) 64px; border-radius:var(--radius-block); background:var(--red); color:var(--cream-light); display:grid; grid-template-columns:1.15fr 1fr; gap:24px 72px; align-items:end; }
.proposal .audience-hero .back-link { grid-column:1/-1; margin:0 0 20px; color:var(--cream-light); }
.proposal .audience-hero__title { grid-column:1; grid-row:2/5; margin:0; max-width:12ch; color:var(--cream-light); font-size:var(--fs-hero); }
.proposal .audience-hero__intro { grid-column:2; margin:0; max-width:43ch; }
.proposal .audience-hero > p:not(.audience-hero__intro) { grid-column:2; margin:0; }
.proposal .audience-hero > .pill { grid-column:2; justify-self:start; margin:0; color:var(--red); background:var(--cream-light); border-color:var(--cream-light); }
.proposal .commitment { padding:40px; margin-bottom:36px; border-radius:var(--radius-block); background:var(--cream-inset); gap:64px; }
.proposal .commitment__lead h2 { margin-top:0; }
.proposal .commitment__steps article { grid-template-columns:72px minmax(0,1fr); }
.proposal .commitment__number { display:flex; align-items:center; justify-content:center; min-height:90px; color:var(--cream-light); background:var(--red); border-radius:var(--radius-card); }
.proposal .faq { display:flex; align-items:baseline; gap:40px; padding-block:18px 48px; }
.proposal .faq__heading { margin:0; color:var(--ink); font-size:var(--fs-h3); }
.proposal .faq > p { margin:0; }
.proposal .application { display:grid; grid-template-columns:minmax(0,.78fr) minmax(0,1.22fr); gap:72px; padding-top:32px; align-items:start; }
.proposal .form-intro { position:sticky; top:110px; padding-top:24px; border-top:7px solid var(--red); }
.proposal .application__title { max-width:9ch; margin:0 0 24px; }
.proposal .application__privacy-note { margin:24px 0 0; }
.proposal .application__context { margin-top:20px; }
.proposal .application-form { max-width:none; margin:0; min-width:0; }
.proposal .application-form__group { padding-top:24px; margin-bottom:48px; }
.proposal .application-form__group legend { margin-bottom:8px; }
.proposal .field-label { display:block; }
.proposal .application-form__field { gap:9px; max-width:none; }
.proposal .short-row { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:20px; }
.proposal .language-row { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:14px; }
.proposal .application-form__field textarea[rows="2"] { min-height:80px; }
.proposal .application-form__field input,.proposal .application-form__field select,.proposal .application-form__field textarea { border:1px solid var(--red); border-radius:var(--radius-card); background:var(--cream-light); min-height:50px; padding:12px 14px; }
.proposal .application-form__field input:focus-visible,.proposal .application-form__field select:focus-visible,.proposal .application-form__field textarea:focus-visible { outline:2px solid var(--ink); outline-offset:3px; }
.proposal .application-form__field input[aria-invalid="true"] { border-width:2px; }
.proposal .application-form__error { color:var(--ink); }
.proposal .application-form__checks { grid-template-columns:repeat(2,minmax(0,1fr)); gap:10px; }
.proposal .application-form__checks label,.proposal .application-form__check { border:1px solid var(--red); border-radius:var(--radius-card); padding:14px; min-height:56px; max-width:none; cursor:pointer; }
.proposal .application-form__checks label:has(:checked),.proposal .application-form__check:has(:checked) { background:var(--red); color:var(--cream-light); }
.proposal .application-form__checks input,.proposal .application-form__check input { accent-color:var(--ink); }
.proposal .submit-area { background:var(--cream-inset); border-radius:var(--radius-block); padding:28px; }
.proposal .submit-area .application-form__required { margin:0 0 18px; }
.proposal .application-form__submit { display:inline-flex; justify-content:center; align-items:center; border-radius:var(--radius-pill); min-height:52px; background:var(--red); border-color:var(--red); }
.proposal .application-form__submit:hover { background:var(--ink); border-color:var(--ink); }
.proposal .application-form__errors { border:1px solid var(--red); border-radius:var(--radius-card); background:var(--cream-light); padding:20px; }
@media(max-width:860px) {
 .proposal .audience-hero { grid-template-columns:minmax(0,1fr); gap:24px; padding:28px 24px 40px; margin-bottom:40px; }
 .proposal .audience-hero > * { grid-column:1!important; grid-row:auto!important; }
 .proposal .audience-hero .back-link { margin-bottom:0; }
 .proposal .commitment { width:calc(100% - 40px); padding:28px 22px; gap:28px; margin-bottom:24px; }
 .proposal .commitment__steps article { grid-template-columns:52px minmax(0,1fr); gap:14px; }
 .proposal .commitment__number { min-height:72px; }
 .proposal .faq { display:block; padding-block:0 40px; }
 .proposal .faq__heading { margin-bottom:14px; }
 .proposal .application { grid-template-columns:minmax(0,1fr); gap:36px; padding-top:12px; }
 .proposal .form-intro { position:static; }
 .proposal .application__title { max-width:none; }
 .proposal .short-row { grid-template-columns:minmax(0,1fr); }
 .proposal .language-row { gap:8px; }
 .proposal .language-row select { padding-left:8px; font-size:var(--fs-small); }
 .proposal .application-form__checks { grid-template-columns:minmax(0,1fr); }
 .proposal .submit-area { padding:22px; }
 .proposal .application-form__submit { width:100%; }
}
`;
const targets = {
 M01:'.audience-hero', M02:'.commitment', M03:'.faq', M04:'#application',
 M05:'.application-form__group:nth-of-type(1)', M06:'.application-form__group:nth-of-type(3)',
 M07:'.application-form__group:nth-of-type(4)', M08:'.application-form__group:nth-of-type(5)'
};
async function transform(page) {
 await page.evaluate(() => {
  document.body.classList.add('proposal');
  const section=document.querySelector('#application');
  const intro=document.createElement('div');intro.className='form-intro';
  section.prepend(intro);
  for(const selector of ['.application__title','.application__context','.application__privacy-note']){const el=section.querySelector(selector);if(el)intro.append(el)}
  document.querySelectorAll('.application-form__field').forEach(label=>{
   const title=document.createElement('span'); title.className='field-label';
   while(label.firstChild && !(label.firstChild.nodeType===1 && label.firstChild.matches('input,textarea,select'))) title.append(label.firstChild);
   label.prepend(title);
  });
  const groups=[...document.querySelectorAll('.application-form__group')];
  const names=[...groups[0].querySelectorAll('.application-form__field')].slice(0,2);
  const row=document.createElement('div');row.className='short-row';names[0].before(row);row.append(...names);
  const languages=[...groups[1].querySelectorAll('.application-form__field')].slice(0,3);
  const langrow=document.createElement('div');langrow.className='language-row';languages[0].before(langrow);langrow.append(...languages);
  const area=document.createElement('div');area.className='submit-area';const required=document.querySelector('.application-form__required');required.before(area);area.append(required,document.querySelector('.application-form__submit'));
 });
 await page.addStyleTag({content:css});
}
async function shot(page, selector, filename, maxHeight=740) {
 const bytes=await page.locator(selector).first().screenshot();
 const meta=await sharp(bytes).metadata();
 await sharp(bytes).extract({left:0,top:0,width:meta.width,height:Math.min(meta.height,maxHeight)}).toFile(path.join(out,filename));
}
async function saveSnapshot(page,name) {
 let html=await page.content();
 html=html.replace(/<script\b[^>]*>[\s\S]*?<\/script>/gi,'').replace(/<link[^>]*(?:rel="preload"|rel="modulepreload")[^>]*>/g,'');
 html=html.replace(/href="https?:\/\/[^" ]+\/build\/assets\/[^" ]+\.css"/g,'href="app.css"');
 html=html.replace('</body>',`<script>document.querySelectorAll('form').forEach(f=>f.addEventListener('submit',e=>{e.preventDefault();alert('This is a design preview. No application has been sent.')}));document.querySelectorAll('a').forEach(a=>{if(!a.getAttribute('href')?.startsWith('#'))a.addEventListener('click',e=>e.preventDefault())});</script></body>`);
 fs.writeFileSync(path.join(out,name+'.html'),html);
}
(async()=>{
 fs.mkdirSync(out,{recursive:true});fs.mkdirSync(path.join(out,'fonts'),{recursive:true});
 const manifest=JSON.parse(fs.readFileSync(path.join(root,'public/build/manifest.json'),'utf8'));
 let appcss=fs.readFileSync(path.join(root,'public/build',manifest['resources/css/app.css'].file),'utf8');
 appcss=appcss.replace(/url\(\/?fonts\//g,'url(fonts/');fs.writeFileSync(path.join(out,'app.css'),appcss);
 for(const name of fs.readdirSync(path.join(root,'public/fonts')).filter(name=>name.endsWith('.woff2')))fs.copyFileSync(path.join(root,'public/fonts',name),path.join(out,'fonts',name));
 const browser=await chromium.launch(); const observations=[];
 try {
  for(const [device,width] of [['desktop',1440],['mobile',390]]) {
   const context=await browser.newContext({ignoreHTTPSErrors:true,viewport:{width,height:1000},deviceScaleFactor:1});
   const page=await context.newPage();await page.goto(base+'/makers');await page.waitForLoadState('networkidle');await page.evaluate(()=>document.fonts.ready);
   const state=await page.evaluate(()=>({width:innerWidth,overflow:document.documentElement.scrollWidth-innerWidth,formWidth:document.querySelector('form').getBoundingClientRect().width,firstFieldWidth:document.querySelector('input[name=name]').getBoundingClientRect().width,backLinkMargin:getComputedStyle(document.querySelector('.back-link')).marginBottom,submitRadius:getComputedStyle(document.querySelector('.application-form__submit')).borderRadius}));
   observations.push({device,current:state});
   await page.screenshot({path:path.join(out,device+'-current-full.png'),fullPage:true});
   for(const [id,selector]of Object.entries(targets))await shot(page,selector,`${id}-${device}-current.png`,id==='M03'?400:740);
   if(device==='desktop')await saveSnapshot(page,'current');
   await transform(page);
   await page.locator('.application-form__checks input').first().check();
   await page.locator('input[name=date_confirmed]').check();
   observations.at(-1).proposed=await page.evaluate(()=>({overflow:document.documentElement.scrollWidth-innerWidth,formWidth:document.querySelector('form').getBoundingClientRect().width}));
   await page.screenshot({path:path.join(out,device+'-proposed-full.png'),fullPage:true});
   for(const [id,selector]of Object.entries(targets))await shot(page,selector,`${id}-${device}-proposed.png`,id==='M03'?400:740);
   await shot(page,'.submit-area',`M08-${device}-proposed-submit.png`,400);
   if(device==='desktop')await saveSnapshot(page,'proposed');
   if(observations.at(-1).proposed.overflow!==0)throw new Error(device+' proposal overflow');
   await context.close();
  }
 } finally {await browser.close()}
 fs.writeFileSync(path.join(out,'observations.json'),JSON.stringify(observations,null,2));
 console.log(JSON.stringify(observations));
})();
