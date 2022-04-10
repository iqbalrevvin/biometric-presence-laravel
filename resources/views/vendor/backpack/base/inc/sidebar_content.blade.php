<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

@foreach (Backpack\MenuCRUD\app\Models\MenuItem::getTree(); as $item)
<li class="nav-item @if(count($item->children) != 0) nav-dropdown @endif">
  <a class="nav-link @if(count($item->children) != 0) nav-dropdown-toggle @endif" href="{{$item->url()}}""><i class="nav-icon la la-list"></i> {{ $item->name }}</a>
  <ul class="nav-dropdown-items">
    @foreach ($item->children as $children )
    <li class="nav-item"><a class="nav-link" href="{{$children->url()}}"><i class="nav-icon la la-stream"></i> <span>{{ $children->name }}</span></a></li>
    @endforeach
  </ul>
</li>
@endforeach 

{{-- <li class='nav-item'><a class='nav-link' href='{{ backpack_url('tag') }}'><i class='nav-icon la la-question'></i> Tags</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('artikel') }}'><i class='nav-icon la la-question'></i> Artikels</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('page') }}'><i class='nav-icon la la-file-o'></i> <span>Pages</span></a></li> --}}


@hasanyrole('Developer|Admin')
<li class="nav-item nav-dropdown">
  <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-users"></i> Authentication</a>
	<ul class="nav-dropdown-items">
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon la la-user"></i> <span>Users</span></a></li>
    @hasrole('Developer')
	  <li class="nav-item"><a class="nav-link" href="{{ backpack_url('role') }}"><i class="nav-icon la la-id-badge"></i> <span>Roles</span></a></li>
	  <li class="nav-item"><a class="nav-link" href="{{ backpack_url('permission') }}"><i class="nav-icon la la-key"></i> <span>Permissions</span></a></li>
    @endhasrole
  </ul>
</li>
@endhasanyrole

@hasanyrole('Developer')
<li class="nav-item nav-dropdown">
  <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-cogs"></i> Developer Area</a>
  <ul class="nav-dropdown-items">
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('menu-item') }}'><i class='nav-icon la la-list'></i> <span>Menu</span></a></li>
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('setting') }}'><i class='nav-icon la la-cog'></i> <span>Settings</span></a></li>
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('log') }}'><i class='nav-icon la la-terminal'></i> Logs</a></li>
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('backup') }}'><i class='nav-icon la la-hdd-o'></i> Backups</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('elfinder') }}"><i class="nav-icon la la-files-o"></i> <span>{{ trans('backpack::crud.file_manager') }}</span></a></li>
  </ul>
</li>
@endhasanyrole