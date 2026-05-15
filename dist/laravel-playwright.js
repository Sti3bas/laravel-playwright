import { test as c } from "@playwright/test";
const u = c.extend({
  laravelBaseUrl: [void 0, { option: !0 }],
  laravelSecret: [void 0, { option: !0 }],
  laravel: async ({ laravelBaseUrl: s, laravelSecret: t, baseURL: a, request: r }, e) => {
    const n = s || a + "/playwright", i = new l(n, r, t);
    await e(i), await i.tearDown();
  }
});
class l {
  constructor(t, a, r = void 0) {
    this.baseUrl = t, this.request = a, this.secret = r;
  }
  async call(t, a = {}) {
    const r = this.baseUrl.replace(/\/$/, "") + t, e = { Accept: "application/json" };
    this.secret && (e["X-Playwright-Secret"] = this.secret);
    const n = await this.request.post(r, { data: a, headers: e });
    if (n.status() !== 200)
      throw new Error(`
                Failed to call Laravel ${t}.
                Status: ${n.status()}
                Response: ${await n.text()}
            `);
    return await n.json();
  }
  async artisan(t, a = []) {
    return await this.call("/artisan", { command: t, parameters: a });
  }
  async truncate(t = []) {
    return await this.call("/truncate", { connections: t });
  }
  async factory(t, a = {}, r, e) {
    return Array.isArray(t) ? await this.call("/factory", { items: t }) : await this.call("/factory", { model: t, count: r, attrs: a, states: e });
  }
  async query(t, a = [], r = {}) {
    const { connection: e = null, unprepared: n = !1 } = r;
    if (n && a.length > 0)
      throw new Error("Cannot use unprepared with bindings");
    return await this.call("/query", {
      query: t,
      bindings: a,
      connection: e,
      unprepared: n
    });
  }
  async select(t, a = {}, r = {}) {
    const { connection: e = null } = r;
    return await this.call("/select", { query: t, bindings: a, connection: e });
  }
  async callFunction(t, a = []) {
    return await this.call("/function", { function: t, args: a });
  }
  /**
   * Sets a laravel config value until tearDown is called (or the test ends)
   */
  async config(t, a) {
    return await this.call("/dynamicConfig", { key: t, value: a });
  }
  /**
   * Travel to a specific time
   * ex: travel('2021-01-01 00:00:00')
   */
  async travel(t) {
    return await this.call("/travel", { to: t });
  }
  async registerBootFunction(t) {
    return await this.call("/registerBootFunction", { function: t });
  }
  async tearDown() {
    return await this.call("/tearDown");
  }
}
export {
  l as Laravel,
  u as test
};
