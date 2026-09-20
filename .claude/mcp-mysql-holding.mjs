// Lanza el MCP de MySQL "mysql-holding" tomando la conexión de .env (DB_*),
// para no duplicar credenciales en .mcp.json.
import { spawn } from "node:child_process";
import { readFileSync } from "node:fs";
import { dirname, resolve } from "node:path";
import { fileURLToPath } from "node:url";

const root = resolve(dirname(fileURLToPath(import.meta.url)), "..");

const env = {};
for (const line of readFileSync(resolve(root, ".env"), "utf8").split(/\r?\n/)) {
    const m = line.match(/^\s*([A-Za-z_][A-Za-z0-9_]*)\s*=\s*(.*?)\s*$/);
    if (!m || line.trimStart().startsWith("#")) continue;
    env[m[1]] = m[2].replace(/^(['"])(.*)\1$/, "$2");
}

const child = spawn("npx", ["-y", "@benborla29/mcp-server-mysql"], {
    stdio: "inherit",
    shell: true,
    env: {
        ...process.env,
        MYSQL_HOST: env.DB_HOST,
        MYSQL_PORT: env.DB_PORT || "3306",
        MYSQL_USER: env.DB_USERNAME,
        MYSQL_PASS: env.DB_PASSWORD,
        MYSQL_DB: env.DB_DATABASE,
        ALLOW_INSERT_OPERATION: "true",
        ALLOW_UPDATE_OPERATION: "true",
        ALLOW_DELETE_OPERATION: "true",
    },
});

child.on("exit", (code) => process.exit(code ?? 0));
