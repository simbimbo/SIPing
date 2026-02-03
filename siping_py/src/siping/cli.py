from __future__ import annotations

import socket
from dataclasses import dataclass

from rich.console import Console

from .settings import Settings

console = Console()


@dataclass
class AgentHello:
    agent_id: str

    def to_wire(self) -> bytes:
        # Legacy protocol appears to use lines like: "agent_id = <id>"\n
        return f"agent_id = {self.agent_id}\n".encode("utf-8")


def app() -> None:
    """Minimal CLI entrypoint.

    This is a placeholder so the repo has a clean Python target scaffold.
    """

    s = Settings()
    console.print(f"[bold]SIPing (python)[/bold] -> {s.server_host}:{s.server_port}")
    console.print("This is just a scaffold. Next: implement agent tick + HTTP server.")

    # Basic connectivity smoke test (optional)
    try:
        with socket.create_connection((s.server_host, s.server_port), timeout=2):
            console.print("[green]TCP connect OK[/green]")
    except OSError as e:
        console.print(f"[yellow]TCP connect failed (expected if server not running):[/yellow] {e}")
