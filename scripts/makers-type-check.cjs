const {chromium}=require('playwright');const assert=require('node:assert/strict');const path=require('node:path');const fs=require('node:fs');
const base=process.env.BASE_URL||'https://makeamark-southern-ocean.test';const out='/tmp/makeamark-makers-type';fs.mkdirSync(out,{recursive:true});
(async()=>{const browser=await chromium.launch();const observations=[];try{
 for(const width of[1440,900,390,320]){
  const context=await browser.newContext({ignoreHTTPSErrors:true,viewport:{width,height:1000}});const page=await context.newPage();const errors=[];page.on('pageerror',e=>errors.push(e.message));
  await page.goto(base+'/makers');await page.waitForLoadState('networkidle');await page.evaluate(()=>document.fonts.ready);
  const values=await page.evaluate(()=>{
   const type=selector=>{const el=document.querySelector(selector),c=getComputedStyle(el);return{weight:c.fontWeight,size:parseFloat(c.fontSize),line:parseFloat(c.lineHeight),family:c.fontFamily}};
   return{width:innerWidth,overflow:document.documentElement.scrollWidth-innerWidth,body:type('.commitment__lead p'),heroCopy:type('.audience-hero__intro'),date:type('.audience-hero__date'),label:type('[data-flux-label]'),input:type('input[name=name]'),helper:type('.application__privacy-note'),title:type('.application__title'),fontLoaded:[...document.fonts].some(f=>f.family.includes('Plus Jakarta Sans')&&f.weight==='300'&&f.status==='loaded')};
  });
  assert.equal(values.body.weight,'300');assert.equal(values.body.size,17);assert.equal(values.body.line,27.2);assert.equal(values.heroCopy.weight,'300');assert.equal(values.date.weight,'400');assert.equal(values.date.size,14);assert.equal(values.label.weight,'600');assert.equal(values.label.size,16);assert.equal(values.input.weight,'400');assert.equal(values.input.size,17);assert.equal(values.helper.size,14);assert.equal(values.helper.weight,'400');assert.ok(values.title.size<=68);assert.ok(values.title.size>=32);assert.equal(values.fontLoaded,true);assert.equal(values.overflow,0);
  assert.equal(await page.locator('.audience-hero__intro strong,.commitment__lead p strong,.commitment__steps p strong').count(),0,'T02 unselected');
  assert.equal(await page.locator('.commitment__lead h2').evaluate(el=>getComputedStyle(el).textWrap),'balance');
  const back=page.getByRole('link',{name:'Back to the edition',exact:true});assert.equal((await back.textContent()).trim(),'Edition');assert.equal(await back.locator('svg').count(),1);assert.equal(await back.evaluate(el=>el.getBoundingClientRect().height),44);assert.equal(await back.evaluate(el=>getComputedStyle(el).fontSize),'14px');
  await back.focus();assert.equal(await back.evaluate(el=>getComputedStyle(el).outlineWidth),'2px');
  if((width===1440||width===390)&&process.env.SCREENSHOTS!=='0'){
   await back.evaluate(el=>el.blur());await page.locator('.audience-hero').screenshot({path:path.join(out,width+'-hero.png')});await page.locator('.commitment').screenshot({path:path.join(out,width+'-commitment.png')});await page.locator('.application-form__group').first().screenshot({path:path.join(out,width+'-fields.png')});
  }
  await page.evaluate(()=>document.documentElement.style.fontSize='200%');
  assert.equal(await page.locator('.commitment__lead p').evaluate(el=>getComputedStyle(el).fontSize),'34px');assert.equal(await page.evaluate(()=>document.documentElement.scrollWidth-innerWidth),0,'200% text reflow');
  assert.deepEqual(errors,[]);observations.push(values);await context.close();
 }
 const other=await browser.newContext({ignoreHTTPSErrors:true});const p=await other.newPage();const lightRequests=[];p.on('request',r=>{if(r.url().includes('plus-jakarta-sans-light-300'))lightRequests.push(r.url())});
 for(const route of['/','/organisations']){await p.goto(base+route);await p.waitForLoadState('networkidle');assert.equal(await p.locator('link[rel=preload][href="/fonts/plus-jakarta-sans-light-300.woff2"]').count(),1);if(route==='/organisations'){assert.equal(await p.locator('.audience-hero__intro').evaluate(el=>getComputedStyle(el).fontWeight),'300');assert.equal((await p.locator('.back-link').textContent()).trim(),'Edition')}}assert.ok(lightRequests.length>=1);await other.close();
 const fallback=await browser.newContext({ignoreHTTPSErrors:true,viewport:{width:390,height:1000}});const f=await fallback.newPage();await f.route('**/plus-jakarta-sans-light-300.woff2',r=>r.abort());await f.goto(base+'/makers');await f.evaluate(()=>document.fonts.ready);assert.equal(await f.locator('.audience-hero__intro').isVisible(),true);assert.equal(await f.evaluate(()=>document.documentElement.scrollWidth-innerWidth),0);await fallback.close();
 fs.writeFileSync(path.join(out,'observations.json'),JSON.stringify(observations,null,2));console.log('Passed: real300 loaded, selected text roles/back link/headings at four widths, T02 absent, 200% reflow, font fallback and shared other-route typography. '+out);
}finally{await browser.close()}})().catch(e=>{console.error(e);process.exit(1)});
