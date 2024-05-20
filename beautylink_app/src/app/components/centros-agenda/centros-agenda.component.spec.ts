import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CentrosAgendaComponent } from './centros-agenda.component';

describe('CentrosAgendaComponent', () => {
  let component: CentrosAgendaComponent;
  let fixture: ComponentFixture<CentrosAgendaComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [CentrosAgendaComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(CentrosAgendaComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
