const {chromium}=require('playwright');const assert=require('node:assert/strict');const path=require('node:path');
(async()=>{
 const browser=await chromium.launch();try{
  for(const width of[1440,390]){
   const page=await browser.newPage({viewport:{width,height:1000}});const errors=[];page.on('pageerror',e=>errors.push(e.message));
   await page.goto('file://'+path.resolve('.lavish/makers-type.html'));await page.evaluate(()=>document.fonts.ready);
   assert.equal(await page.locator('.decision').count(),6);assert.equal(await page.locator('.weight-copy.light').evaluate(el=>getComputedStyle(el).fontWeight),'300');
   assert.equal(await page.evaluate(()=>document.fonts.check('300 17px ReviewLight')),true);
   await page.locator('#recommended').click();assert.equal(await page.locator('input[name=items]:checked').count(),5);
   await page.locator('input[name=back_style][value=icon_only]').check();assert.equal(await page.locator('#back-word').isVisible(),false);
   await page.locator('#scope').selectOption('site');
   await page.evaluate(()=>{window.queued=[];window.lavish={queuePrompt:(text,data)=>window.queued.push({text,data:{...data,element:undefined}})}});
   await page.locator('#review button[type=submit]').click();const queued=await page.evaluate(()=>window.queued);assert.equal(queued.length,1);assert.equal(queued[0].data.data.items.length,5);assert.equal(queued[0].data.data.scope,'site');assert.equal(queued[0].data.data.backLinkStyle,'icon_only');
   await page.locator('#T01 button[data-device-button=mobile]').click();assert.equal(await page.locator('#T01 [data-device=mobile]').isVisible(),true);
   assert.equal(await page.evaluate(()=>document.documentElement.scrollWidth-innerWidth),0);assert.deepEqual(errors,[]);
   await page.locator('#clear').click();await page.locator('input[name=back_style][value=arrow_label]').check();await page.locator('#scope').selectOption('makers');
   await page.evaluate(()=>{localStorage.clear();window.scrollTo({top:0,behavior:'instant'})});await page.screenshot({path:path.join(__dirname,'overview-'+width+'.png')});
   await page.close();console.log(width+': six suggestions, genuine300 specimen, five-item queue, icon/scope options, toggles and zero overflow passed');
  }
 }finally{await browser.close()}
})().catch(error=>{console.error(error);process.exit(1)});
