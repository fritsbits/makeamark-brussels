const {chromium}=require('playwright');
const assert=require('node:assert/strict');
const fs=require('node:fs');
const path=require('node:path');
const base=process.env.BASE_URL||'https://makeamark-southern-ocean.test';
const out='/tmp/makeamark-makers-hardened';fs.mkdirSync(out,{recursive:true});
const contract=()=>[...document.querySelectorAll('form input:not([type=hidden]),form textarea,form select,form ui-checkbox')].filter(el=>!el.closest('noscript')).map(el=>({name:el.getAttribute('name'),type:el.localName==='ui-checkbox'?'checkbox':el.type,required:el.hasAttribute('required'),maxLength:el.localName==='ui-checkbox'?-1:el.maxLength,options:el.options?[...el.options].map(o=>o.value):null}));
async function fill(page){for(const [name,value]of Object.entries({name:'Élodie علي 山田 🎨',email:'fixture@example.com',motivation:'A local creative project. '.repeat(50),portfolio:'https://example.com/work',main_skills:'Design',meeting_availability:'Evenings'}))await page.locator(`[name="${name}"]`).fill(value)}
(async()=>{
 const browser=await chromium.launch();const observations=[];
 try{
  const baseline=await browser.newPage();await baseline.goto('file://'+path.resolve('.lavish/assets/makers-polish/current.html'));const previous=await baseline.evaluate(contract);await baseline.close();
  for(const width of[1440,900,720,390,320]){
   const context=await browser.newContext({ignoreHTTPSErrors:true,viewport:{width,height:900}});const page=await context.newPage();page.setDefaultTimeout(15000);
   const errors=[];page.on('pageerror',e=>errors.push(e.message));await page.goto(base+'/makers');await page.waitForLoadState('networkidle');await page.evaluate(()=>document.fonts.ready);
   assert.deepEqual(await page.evaluate(contract),previous,'business fields and requiredness retained');
   assert.equal(await page.locator('[data-flux-select-native]').count(),3);assert.equal(await page.locator('ui-checkbox[data-flux-checkbox]').count(),12);assert.ok(await page.locator('input[data-flux-control]').count()>=5);assert.equal(await page.locator('textarea[data-flux-textarea]').count(),7);assert.equal(await page.locator('.application-form__submit[data-flux-button]').count(),1);
   assert.equal(await page.locator('input[name=_submission_token]').inputValue().then(v=>v.length),97);
   const language=await page.locator('[data-flux-select-native]').first().evaluate(el=>{const c=getComputedStyle(el);return{width:el.getBoundingClientRect().width,font:parseFloat(c.fontSize),end:parseFloat(c.paddingInlineEnd),arrow:c.backgroundImage}});
   assert.ok(language.width>=190);assert.ok(language.font>=16);assert.ok(language.end>=40);assert.ok(language.arrow.includes('1E1A16'),'ink chevron remains visible');
   await page.locator('select[name=english]').selectOption('5');assert.equal(await page.locator('select[name=english]').inputValue(),'5');
   const first=page.locator('ui-checkbox').first();await first.focus();await page.keyboard.press('Space');assert.equal(await first.getAttribute('aria-checked'),'true');
   assert.deepEqual(await page.locator('form').evaluate(form=>new FormData(form).getAll('themes[]')),['Poverty reduction and social security']);
   await page.keyboard.press('Space');assert.equal(await first.getAttribute('aria-checked'),'false');
   await fill(page);
   let posts=0;page.on('request',r=>{if(r.method()==='POST')posts++});
   await page.getByRole('button',{name:'Send maker application'}).click();
   await page.waitForFunction(()=>[...document.querySelectorAll('.application-form__client-error')].filter(el=>getComputedStyle(el).display!=='none').length===2);
   assert.equal(posts,0);assert.equal(await page.locator('.application-form__client-error:visible').count(),2);assert.equal(await page.locator('#date_confirmed-control').getAttribute('aria-invalid'),'true');
   await page.locator('#date_confirmed-control').click();await page.locator('#full_commitment-control').click();
   await page.waitForFunction(()=>[...document.querySelectorAll('.application-form__client-error')].every(el=>getComputedStyle(el).display==='none'));
   assert.equal(await page.locator('.application-form__client-error:visible').count(),0);
   assert.equal(await page.evaluate(()=>document.documentElement.scrollWidth-innerWidth),0);
   const brokenRefs=await page.evaluate(()=>[...document.querySelectorAll('[aria-labelledby],[aria-describedby]')].flatMap(el=>['aria-labelledby','aria-describedby'].flatMap(attr=>(el.getAttribute(attr)||'').split(/\s+/).filter(Boolean).filter(id=>!document.getElementById(id)))));assert.deepEqual(brokenRefs,[]);
   if((width===1440||width===390)&&process.env.SCREENSHOTS!=='0'){
    await page.locator('.application-form__group').nth(1).screenshot({path:path.join(out,width+'-languages.png')});
    await page.evaluate(()=>window.scrollTo({top:0,behavior:'instant'}));await page.screenshot({path:path.join(out,width+'-page.png'),fullPage:true});
   }
   await page.locator('form').evaluate(form=>{form.addEventListener('submit',e=>e.preventDefault());form.requestSubmit()});await page.waitForFunction(()=>document.querySelector('.application-form__submit').disabled);
   assert.equal(await page.locator('.application-form__submit').getAttribute('aria-busy'),'true');
   await page.locator('form').evaluate(form=>form.requestSubmit());assert.equal(posts,0);
   await page.evaluate(()=>window.dispatchEvent(new PageTransitionEvent('pageshow',{persisted:true})));assert.equal(await page.getByRole('button',{name:'Send maker application'}).isEnabled(),true);
   if(width===390){
    await page.goto(base+'/makers');await page.waitForLoadState('networkidle');
    await Promise.all([page.waitForURL('**/makers#application'),page.locator('form').evaluate(form=>{form.noValidate=true;form.submit()})]);
    await page.waitForLoadState('networkidle');assert.equal(await page.locator('.application-form__errors').count(),1);
    assert.equal(await page.locator('.application-form__errors').evaluate(el=>document.activeElement===el),true);
    for(const href of await page.locator('.application-form__errors a').evaluateAll(links=>links.map(a=>a.getAttribute('href'))))assert.equal(await page.locator(href).count(),1);
   }
   await page.goto(base+'/organisations');assert.equal(await page.locator('.makers-page').count(),0);assert.equal(await page.locator('.application-form__submit').evaluate(el=>getComputedStyle(el).borderRadius),'999px');
   if(width===1440||width===390){await page.goto(base+'/');assert.equal(await page.locator('.photo-reveal').count(),10);assert.equal(await page.locator('.section-block').first().evaluate(el=>parseFloat(getComputedStyle(el).paddingTop)),width===1440?96:56);assert.equal(await page.evaluate(()=>document.documentElement.scrollWidth-innerWidth),0);}
   assert.deepEqual(errors,[]);observations.push({width,language,overflow:0});await context.close();
  }
  const nojs=await browser.newContext({ignoreHTTPSErrors:true,javaScriptEnabled:false,viewport:{width:390,height:900}});const p=await nojs.newPage();await p.goto(base+'/makers');
  assert.equal(await p.locator('input[type=checkbox]').count(),12);assert.equal(await p.locator('input[name=date_confirmed]').isVisible(),true);assert.equal(await p.locator('input[name=date_confirmed]').getAttribute('required'),'');
  await p.locator('input[name=date_confirmed]').check();assert.equal(await p.locator('form').evaluate(form=>new FormData(form).get('date_confirmed')),'1');
  assert.equal(await p.locator('[data-flux-select-native]').count(),3);assert.equal(await p.getByRole('button',{name:'Send maker application'}).isEnabled(),true);await nojs.close();
  const contrast=await browser.newContext({ignoreHTTPSErrors:true,forcedColors:'active',viewport:{width:720,height:900}});const c=await contrast.newPage();await c.goto(base+'/makers');assert.equal(await c.evaluate(()=>document.documentElement.scrollWidth-innerWidth),0);await contrast.close();
  fs.writeFileSync(path.join(out,'observations.json'),JSON.stringify(observations,null,2));console.log('Passed: Flux controls, selects/chevrons at five widths, field contract, Unicode drafts, checkbox FormData/keyboard/required validation, inline errors, duplicate-submit guard, pageshow, no-JS and forced-colors. '+out);
 }finally{await browser.close()}
})().catch(error=>{console.error(error);process.exit(1)});
