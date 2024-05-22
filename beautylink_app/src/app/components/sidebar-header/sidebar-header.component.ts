import { Component } from '@angular/core';
import { RouterLink, RouterLinkActive } from '@angular/router';

@Component({
  selector: 'app-sidebar-header',
  standalone: true,
  imports: [RouterLink, RouterLinkActive],
  templateUrl: './sidebar-header.component.html',
  styleUrl: './sidebar-header.component.css',
})
export class SidebarHeaderComponent {}
