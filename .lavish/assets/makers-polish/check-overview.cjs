const { chromium } = require('playwright');
const assert = require('node:assert/strict');
const path = require('node:path');
(async()=>{
 const browser=await chromium.launch();
 try {
  for(const width of [1440,390]){
   const page=await browser.newPage({viewport:{width,height:1000}});
   const errors=[];page.on('pageerror',e=>errors.push(e.message));
   await page.goto('file://'+path.resolve('.lavish/makers-polish.html'));
   await page.evaluate(()=>document.fonts.ready);
   assert.equal(await page.locator('.decision').count(),8);
   await page.locator('#recommended').click();
   assert.equal(await page.locator('input[name=items]:checked').count(),6);
   await page.locator('#M05 button[data-device-button=mobile]').click();
   assert.equal(await page.locator('#M05 [data-device=mobile]').isVisible(),true);
   await page.evaluate(()=>{window.prompts=[];window.lavish={queuePrompt:(text,data)=>window.prompts.push({text,data:{...data,element:undefined}})}});
   await page.locator('#review button[type=submit]').click();
   const prompts=await page.evaluate(()=>window.prompts);
   assert.equal(prompts.length,1);assert.equal(prompts[0].data.data.items.length,6);
   await page.locator('#clear').click();
   await page.evaluate(()=>{localStorage.clear();window.scrollTo({top:0,behavior:'instant'})});
   const overflow=await page.evaluate(()=>document.documentElement.scrollWidth-innerWidth);
   assert.equal(overflow,0);assert.deepEqual(errors,[]);
   await page.screenshot({path:path.join(__dirname,'overview-'+width+'.png')});
   await page.goto('file://'+path.resolve('.lavish/assets/makers-polish/proposed.html'));
   await page.evaluate(()=>document.fonts.ready);
   assert.equal(await page.locator('#application form').count(),1);
   const font=await page.locator('h1').evaluate(el=>getComputedStyle(el).fontFamily);
   assert.ok(font.includes('Big Shoulders'));
   assert.equal(await page.evaluate(()=>document.documentElement.scrollWidth-innerWidth),0);
   assert.equal(await page.locator('link[rel=stylesheet]').getAttribute('href'),'app.css');
   await page.close();console.log(width+': overview layout, eight choices, six-item batch queue and local prototype passed');
  }
 }finally{await browser.close()}
})().catch(error=>{console.error(error);process.exit(1)});
