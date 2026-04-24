import { test as i } from "@playwright/test";
const u = i.extend({
  laravelBaseUrl: [void 0, { option: !0 }],
  laravelSecret: [void 0, { option: !0 }],
  laravel: async ({ laravelBaseUrl: s, laravelSecret: t, baseURL: a, request: e }, r) => {
    const n = s || a + "/playwright", c = new l(n, e, t);
    await r(c), await c.tearDown();
  }
});
class l {
  constructor(t, a, e = void 0) {
    this.baseUrl = t, this.request = a, this.secret = e;
  }
  async call(t, a = {}) {
    const e = this.baseUrl.replace(/\/$/, "") + t, r = { Accept: "application/json" };
    this.secret && (r["X-Playwright-Secret"] = this.secret);
    const n = await this.request.post(e, { data: a, headers: r });
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
  async factory(t, a = {}, e, r) {
    return await this.call("/factory", { model: t, count: e, attrs: a, states: r });
  }
  async query(t, a = [], e = {}) {
    const { connection: r = null, unprepared: n = !1 } = e;
    if (n && a.length > 0)
      throw new Error("Cannot use unprepared with bindings");
    return await this.call("/query", {
      query: t,
      bindings: a,
      connection: r,
      unprepared: n
    });
  }
  async select(t, a = {}, e = {}) {
    const { connection: r = null } = e;
    return await this.call("/select", { query: t, bindings: a, connection: r });
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
